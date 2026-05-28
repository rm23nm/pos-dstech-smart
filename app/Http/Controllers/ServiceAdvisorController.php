<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DocumentNumbering;
use App\Models\Pelanggan;

class ServiceAdvisorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $mekaniks = DB::table('mekanik')->where('RecordOwnerID', $recordOwnerID)->get();
        $pelanggans = DB::table('pelanggan')->where('RecordOwnerID', $recordOwnerID)->get();
        $kendaraans = DB::table('kendaraan')->get();
        $kategoris = DB::table('jenisitem')->where('RecordOwnerID', $recordOwnerID)->get();

        return view('MasterData.ServiceAdvisor.index', compact('mekaniks', 'pelanggans', 'kendaraans', 'kategoris'));
    }

    public function customerDisplay()
    {
        $company = \App\Models\Company::where('KodePartner', Auth::user()->RecordOwnerID)->first();
        return view('MasterData.ServiceAdvisor.CustomerDisplay', compact('company'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $pkbs = DB::table('bengkel_work_orders')
            ->leftJoin('mekanik', function ($join) use ($recordOwnerID) {
                $join->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                     ->where('mekanik.RecordOwnerID', '=', $recordOwnerID);
            })
            ->select('bengkel_work_orders.*', 'mekanik.NamaMekanik')
            ->where('bengkel_work_orders.RecordOwnerID', $recordOwnerID)
            ->whereIn('bengkel_work_orders.StatusServis', [0, 1]) // Only show active
            ->orderBy('bengkel_work_orders.created_at', 'DESC')
            ->get();

        return response()->json(['data' => $pkbs]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $request->validate([
            'PlatNomor' => 'required'
        ]);

        $noPkb = 'PKB-' . date('YmdHis');

        $keluhanInput = $request->input('Keluhan', []);
        $keluhanJson = [];
        if (is_array($keluhanInput)) {
            foreach ($keluhanInput as $k) {
                if (!empty(trim($k))) {
                    $keluhanJson[] = ['text' => $k, 'done' => false];
                }
            }
        }
        $keluhanString = json_encode($keluhanJson);

        DB::beginTransaction();
        try {
            DB::table('bengkel_work_orders')->insert([
                'NoPKB' => $noPkb,
                'TglPKB' => Carbon::today('Asia/Jakarta')->toDateString(),
                'PlatNomor' => $request->PlatNomor,
                'KodePelanggan' => $request->KodePelanggan ?? '',
                'NamaPelanggan' => $request->NamaPelanggan ?? '',
                'KodeMekanik' => $request->KodeMekanik,
                'EstimasiWaktu' => $request->EstimasiWaktu ?? 0,
                'Keluhan' => $keluhanString,
                'StatusServis' => 0,
                'RecordOwnerID' => $recordOwnerID,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

            if ($request->has('spareparts') && is_array($request->spareparts)) {
                $details = [];
                foreach ($request->spareparts as $item) {
                    $details[] = [
                        'NoPKB' => $noPkb,
                        'KodeItem' => $item['KodeItem'],
                        'NamaItem' => $item['NamaItem'],
                        'Qty' => $item['Qty'],
                        'Harga' => $item['Harga'],
                        'Subtotal' => $item['Qty'] * $item['Harga'],
                        'StatusGudang' => 0,
                        'RecordOwnerID' => $recordOwnerID,
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta')
                    ];
                }
                if (!empty($details)) {
                    DB::table('bengkel_work_order_details')->insert($details);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pendaftaran Servis Berhasil!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'], 500);
        }
    }

    public function batal(Request $request, $id)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        DB::table('bengkel_work_orders')
            ->where('id', $id)
            ->where('RecordOwnerID', $recordOwnerID)
            ->update([
                'StatusServis' => 3, // Batal
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);
            
        return response()->json(['success' => true, 'message' => 'Pendaftaran dibatalkan.']);
    }

    public function getItems(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;
        $items = DB::table('itemmaster')->where('RecordOwnerID', $recordOwnerID)->where('Active', 'Y')->get();
        return response()->json($items);
    }

    public function getDetails(Request $request, $noPkb)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;
        $details = DB::table('bengkel_work_order_details')
            ->leftJoin('itemmaster', function ($join) use ($recordOwnerID) {
                $join->on('bengkel_work_order_details.KodeItem', '=', 'itemmaster.KodeItem')
                     ->where('itemmaster.RecordOwnerID', '=', $recordOwnerID);
            })
            ->select('bengkel_work_order_details.*', 'itemmaster.NamaItem')
            ->where('bengkel_work_order_details.NoPKB', $noPkb)
            ->where('bengkel_work_order_details.RecordOwnerID', $recordOwnerID)
            ->get();
        return response()->json(['data' => $details]);
    }

    public function storeDetail(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $item = DB::table('itemmaster')
            ->where('KodeItem', $request->KodeItem)
            ->where('RecordOwnerID', $recordOwnerID)
            ->first();

        $namaItem = $item ? $item->NamaItem : '-';

        DB::table('bengkel_work_order_details')->insert([
            'NoPKB' => $request->NoPKB,
            'KodeItem' => $request->KodeItem,
            'NamaItem' => $namaItem,
            'Qty' => $request->Qty,
            'Harga' => $request->Harga,
            'Subtotal' => $request->Qty * $request->Harga,
            'StatusGudang' => 0,
            'RecordOwnerID' => $recordOwnerID,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);
        
        return response()->json(['success' => true, 'message' => 'Item berhasil ditambahkan']);
    }

    public function deleteDetail(Request $request, $id)
    {
        DB::table('bengkel_work_order_details')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Item dihapus']);
    }

    public function storeKendaraan(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $request->validate([
            'PlatNomor' => 'required',
            'Merek' => 'required'
        ]);

        $kodeKendaraan = 'KND' . date('ymdHis') . rand(10,99);

        DB::table('kendaraan')->insert([
            'KodeKendaraan' => $kodeKendaraan,
            'PlatNomor' => $request->PlatNomor,
            'KodePelanggan' => $request->KodePelanggan ?? '',
            'Merek' => $request->Merek,
            'JenisKendaraan' => $request->JenisKendaraan ?? 'Mobil',
            'Tipe' => $request->Tipe ?? '',
            'Tahun' => $request->Tahun ?? '',
            'Warna' => $request->Warna ?? '',
            'NoMesin' => $request->NoMesin ?? '',
            'NoRangka' => $request->NoRangka ?? '',
            'NamaSTNK' => $request->NamaSTNK ?? '',
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => Carbon::now('Asia/Jakarta')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => [
                'KodeKendaraan' => $kodeKendaraan,
                'PlatNomor' => $request->PlatNomor
            ]
        ]);
    }

    public function storePelanggan(Request $request)
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        $request->validate([
            'NamaPelanggan' => 'required'
        ]);

        $numberingData = new DocumentNumbering();
        $KodePelanggan = $numberingData->GetNewDoc("PLG","pelanggan","KodePelanggan");

        $model = new Pelanggan;
        $model->KodePelanggan = $KodePelanggan;
        $model->NamaPelanggan = $request->NamaPelanggan;
        $model->NoHP = $request->NoHP ?? '';
        $model->Alamat = $request->Alamat ?? '';
        $model->RecordOwnerID = $recordOwnerID;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil ditambahkan',
            'data' => [
                'KodePelanggan' => $KodePelanggan,
                'NamaPelanggan' => $request->NamaPelanggan
            ]
        ]);
    }
}
