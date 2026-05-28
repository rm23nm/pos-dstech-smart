<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KendaraanController extends Controller
{
    public function index()
    {
        $advisors = \Illuminate\Support\Facades\DB::table('mekanik')->where('RecordOwnerID', Auth::user()->RecordOwnerID)->get();
        return view('MasterData.Kendaraan.index', compact('advisors'));
    }

    public function getData()
    {
        $data = Kendaraan::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'PlatNomor' => 'required',
            'Merek' => 'required'
        ]);

        $kodeKendaraan = 'KND' . date('ymdHis') . rand(10,99);

        $kendaraan = new Kendaraan();
        $kendaraan->KodeKendaraan = $kodeKendaraan;
        $kendaraan->KodePelanggan = $request->KodePelanggan ?? '';
        $kendaraan->PlatNomor = $request->PlatNomor;
        $kendaraan->Merek = $request->Merek;
        $kendaraan->JenisKendaraan = $request->JenisKendaraan ?? 'Mobil';
        $kendaraan->Tipe = $request->Tipe ?? '';
        $kendaraan->Tahun = $request->Tahun ?? '';
        $kendaraan->Warna = $request->Warna ?? '';
        $kendaraan->NoMesin = $request->NoMesin ?? '';
        $kendaraan->NoRangka = $request->NoRangka ?? '';
        $kendaraan->NamaSTNK = $request->NamaSTNK ?? '';
        $kendaraan->Email = $request->Email ?? '';
        $kendaraan->KodeAdvisor = $request->KodeAdvisor ?? null;
        $kendaraan->created_at = Carbon::now('Asia/Jakarta');
        $kendaraan->updated_at = Carbon::now('Asia/Jakarta');
        $kendaraan->save();

        return response()->json(['success' => true, 'message' => 'Data kendaraan berhasil disimpan']);
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::where('KodeKendaraan', $id)->first();
        if ($kendaraan) {
            $kendaraan->KodePelanggan = $request->KodePelanggan ?? '';
            $kendaraan->PlatNomor = $request->PlatNomor;
            $kendaraan->Merek = $request->Merek;
            $kendaraan->JenisKendaraan = $request->JenisKendaraan ?? 'Mobil';
            $kendaraan->Tipe = $request->Tipe ?? '';
            $kendaraan->Tahun = $request->Tahun ?? '';
            $kendaraan->Warna = $request->Warna ?? '';
            $kendaraan->NoMesin = $request->NoMesin ?? '';
            $kendaraan->NoRangka = $request->NoRangka ?? '';
            $kendaraan->NamaSTNK = $request->NamaSTNK ?? '';
            $kendaraan->Email = $request->Email ?? '';
            $kendaraan->KodeAdvisor = $request->KodeAdvisor ?? null;
            $kendaraan->updated_at = Carbon::now('Asia/Jakarta');
            $kendaraan->save();
            return response()->json(['success' => true, 'message' => 'Data kendaraan berhasil diupdate']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
    }

    public function destroy(Request $request)
    {
        $kendaraan = Kendaraan::where('KodeKendaraan', $request->id)->first();
        if ($kendaraan) {
            $kendaraan->delete();
            return response()->json(['success' => true, 'message' => 'Data kendaraan berhasil dihapus']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
}
