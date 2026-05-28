<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermintaanSparepartController extends Controller
{
    public function index()
    {
        return view('MasterData.PermintaanSparepart.index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $requests = DB::table('bengkel_work_order_details')
            ->join('bengkel_work_orders', 'bengkel_work_order_details.NoPKB', '=', 'bengkel_work_orders.NoPKB')
            ->leftJoin('itemmaster', function($join) use ($recordOwnerID) {
                $join->on('bengkel_work_order_details.KodeItem', '=', 'itemmaster.KodeItem')
                     ->where('itemmaster.RecordOwnerID', '=', $recordOwnerID);
            })
            ->select('bengkel_work_order_details.*', 'bengkel_work_orders.PlatNomor', 'bengkel_work_orders.NamaPelanggan', 'bengkel_work_orders.TglPKB', 'itemmaster.TypeItem')
            ->where('bengkel_work_order_details.RecordOwnerID', $recordOwnerID)
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
            ->where(function($q) {
                // Tampilkan hanya Barang (bukan Jasa)
                $q->where('itemmaster.TypeItem', 'Barang')
                  ->orWhere('itemmaster.TypeItem', '1'); // Sometimes 1 is used for Barang
            })
            ->orderBy('bengkel_work_order_details.StatusGudang', 'ASC')
            ->orderBy('bengkel_work_order_details.created_at', 'DESC')
            ->get();

        return response()->json(['data' => $requests]);
    }

    public function serahkan(Request $request, $id)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        DB::beginTransaction();
        try {
            $detail = DB::table('bengkel_work_order_details')
                ->where('id', $id)
                ->where('RecordOwnerID', $recordOwnerID)
                ->first();

            if (!$detail) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            if ($detail->StatusGudang == 1) {
                return response()->json(['success' => false, 'message' => 'Barang sudah diserahkan sebelumnya']);
            }

            // Update status gudang
            DB::table('bengkel_work_order_details')
                ->where('id', $id)
                ->update(['StatusGudang' => 1, 'updated_at' => Carbon::now('Asia/Jakarta')]);

            // Deduct stock
            $gudang = DB::table('gudang')->where('RecordOwnerID', $recordOwnerID)->first();
            if ($gudang) {
                DB::table('itemwarehouses')
                    ->where('KodeItem', $detail->KodeItem)
                    ->where('KodeGudang', $gudang->KodeGudang)
                    ->where('RecordOwnerID', $recordOwnerID)
                    ->decrement('Qty', $detail->Qty);

                // Add to item_moving_history
                DB::table('item_moving_history')->insert([
                    'KodeItem' => $detail->KodeItem,
                    'TglTransaksi' => Carbon::today('Asia/Jakarta')->toDateString(),
                    'TipeTransaksi' => 'ISSUE_BENGKEL',
                    'NoTransaksi' => $detail->NoPKB,
                    'Qty' => -$detail->Qty,
                    'Harga' => $detail->Harga,
                    'KodeGudang' => $gudang->KodeGudang,
                    'RecordOwnerID' => $recordOwnerID,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Barang berhasil diserahkan dan stok dipotong']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }

    public function tolak(Request $request, $id)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        DB::table('bengkel_work_order_details')
            ->where('id', $id)
            ->where('RecordOwnerID', $recordOwnerID)
            ->update(['StatusGudang' => 2, 'updated_at' => Carbon::now('Asia/Jakarta')]); // 2: Kosong / Indent

        return response()->json(['success' => true, 'message' => 'Barang ditandai kosong / indent']);
    }
}
