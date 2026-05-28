<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardMekanikController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $mekaniks = DB::table('mekanik')->where('RecordOwnerID', $recordOwnerID)->get();

        return view('MasterData.DashboardMekanik.index', compact('mekaniks'));
    }

    public function queueDisplay()
    {
        return view('MasterData.DashboardMekanik.queue');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;
        $kodeMekanik = $request->KodeMekanik;

        $query = DB::table('bengkel_work_orders')
            ->leftJoin('mekanik', function ($join) use ($recordOwnerID) {
                $join->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                     ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
            })
            ->leftJoin('kendaraan', function ($join) {
                $join->on('bengkel_work_orders.PlatNomor', '=', 'kendaraan.PlatNomor');
            })
            ->select('bengkel_work_orders.*', 'mekanik.NamaMekanik', 'kendaraan.JenisKendaraan')
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID);

        if ($request->type === 'queue') {
            $query->whereIn('bengkel_work_orders.StatusServis', [0, 1, 2]); // Include Selesai for queue
            $query->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('fakturpenjualanheader')
                  ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
                  ->where('fakturpenjualanheader.Status', '!=', 'D');
            });
        } else {
            $query->whereIn('bengkel_work_orders.StatusServis', [0, 1]); // Active only for dashboard
        }

        if ($kodeMekanik) {
            $query->where('bengkel_work_orders.KodeMekanik', $kodeMekanik);
        }

        $pkbs = $query->orderBy('bengkel_work_orders.created_at', 'ASC')->get();

        return response()->json(['data' => $pkbs]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $status = $request->StatusServis;
        $laporan = $request->LaporanMekanik;

        $updateData = [
            'StatusServis' => $status,
            'updated_at' => Carbon::now('Asia/Jakarta')
        ];

        if ($laporan !== null) {
            $updateData['LaporanMekanik'] = $laporan;
        }

        if ($request->has('KodeMekanikUpdate') && $request->KodeMekanikUpdate !== '') {
            $updateData['KodeMekanik'] = $request->KodeMekanikUpdate;
        }

        if ($request->has('keluhanText') && is_array($request->keluhanText)) {
            $keluhanJson = [];
            foreach ($request->keluhanText as $idx => $text) {
                $keluhanJson[] = [
                    'text' => $text,
                    'done' => isset($request->keluhanDone[$idx]) ? true : false
                ];
            }
            $updateData['Keluhan'] = json_encode($keluhanJson);
        }

        DB::table('bengkel_work_orders')
            ->where('id', $id)
            ->where('RecordOwnerID', $recordOwnerID)
            ->update($updateData);

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui!']);
    }

    /**
     * API untuk halaman Antrean Bengkel (queue-bengkel)
     * Filter: Menunggu(0), Dikerjakan(1), Selesai(2) tapi Selesai hilang jika sudah >60 menit atau sudah ada di transaksi penjualan
     */
    public function queueGetData(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        // Ambil semua PKB yang aktif (Menunggu & Dikerjakan)
        $menunggu = DB::table('bengkel_work_orders')
            ->leftJoin('mekanik', function($j) use ($recordOwnerID) {
                $j->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                  ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
            })
            ->leftJoin('kendaraan', 'bengkel_work_orders.PlatNomor', '=', 'kendaraan.PlatNomor')
            ->select('bengkel_work_orders.*', 'mekanik.NamaMekanik', 'kendaraan.JenisKendaraan', 'kendaraan.Merek')
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
            ->where('bengkel_work_orders.StatusServis', 0)
            ->orderBy('bengkel_work_orders.created_at', 'ASC')
            ->get();

        $dikerjakan = DB::table('bengkel_work_orders')
            ->leftJoin('mekanik', function($j) use ($recordOwnerID) {
                $j->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                  ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
            })
            ->leftJoin('kendaraan', 'bengkel_work_orders.PlatNomor', '=', 'kendaraan.PlatNomor')
            ->select('bengkel_work_orders.*', 'mekanik.NamaMekanik', 'kendaraan.JenisKendaraan', 'kendaraan.Merek')
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
            ->where('bengkel_work_orders.StatusServis', 1)
            ->orderBy('bengkel_work_orders.created_at', 'ASC')
            ->get();

        // Selesai: tampilkan hanya yang belum dibayar di kasir dan belum lewat 60 menit
        $cutoff = Carbon::now('Asia/Jakarta')->subMinutes(60);
        $selesai = DB::table('bengkel_work_orders')
            ->leftJoin('mekanik', function($j) use ($recordOwnerID) {
                $j->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                  ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
            })
            ->leftJoin('kendaraan', 'bengkel_work_orders.PlatNomor', '=', 'kendaraan.PlatNomor')
            ->select('bengkel_work_orders.*', 'mekanik.NamaMekanik', 'kendaraan.JenisKendaraan', 'kendaraan.Merek')
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
            ->where('bengkel_work_orders.StatusServis', 2) // Selesai servis
            ->where('bengkel_work_orders.updated_at', '>=', $cutoff) // Belum lewat 60 menit
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('fakturpenjualanheader')
                  ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
                  ->where('fakturpenjualanheader.Status', '!=', 'D');
            })
            ->orderBy('bengkel_work_orders.updated_at', 'DESC')
            ->get();

        return response()->json([
            'menunggu'   => $menunggu,
            'dikerjakan' => $dikerjakan,
            'selesai'    => $selesai,
        ]);
    }

    public function queueUpdateStatus(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        DB::table('bengkel_work_orders')
            ->where('NoPKB', $request->NoTransaksi)
            ->where('RecordOwnerID', $recordOwnerID)
            ->update([
                'StatusServis' => $request->StatusServis,
                'updated_at'   => Carbon::now('Asia/Jakarta'),
            ]);

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui!']);
    }

    public function getItems($noPKB)
    {
        $user = Auth::user();
        
        $items = DB::table('bengkel_work_order_details')
            ->where('NoPKB', $noPKB)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();
            
        return response()->json(['success' => true, 'data' => $items]);
    }
}
