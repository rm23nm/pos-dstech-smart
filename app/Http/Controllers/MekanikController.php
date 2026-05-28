<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mekanik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MekanikController extends Controller
{
    public function index()
    {
        return view('MasterData.Mekanik.index');
    }

    public function getData()
    {
        $data = Mekanik::where('RecordOwnerID', Auth::user()->RecordOwnerID)->get();
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'KodeMekanik' => 'required',
            'NamaMekanik' => 'required'
        ]);

        try {
            $mekanik = new Mekanik();
            $mekanik->KodeMekanik = $request->KodeMekanik;
            $mekanik->NamaMekanik = $request->NamaMekanik;
            $mekanik->NoHP = $request->NoHP ?? '';
            $mekanik->PersentaseKomisi = $request->PersentaseKomisi ?? 0;
            $mekanik->Status = $request->Status ?? 1;
            $mekanik->RecordOwnerID = Auth::user()->RecordOwnerID;
            $mekanik->save();

            return response()->json(['success' => true, 'message' => 'Data Mekanik berhasil disimpan']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaMekanik' => 'required'
        ]);

        try {
            $mekanik = Mekanik::where('KodeMekanik', $id)->where('RecordOwnerID', Auth::user()->RecordOwnerID)->first();
            if ($mekanik) {
                $mekanik->NamaMekanik = $request->NamaMekanik;
                $mekanik->NoHP = $request->NoHP ?? '';
                $mekanik->PersentaseKomisi = $request->PersentaseKomisi ?? 0;
                $mekanik->Status = $request->Status ?? 1;
                $mekanik->save();

                return response()->json(['success' => true, 'message' => 'Data Mekanik berhasil diupdate']);
            }
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $mekanik = Mekanik::where('KodeMekanik', $request->KodeMekanik)->where('RecordOwnerID', Auth::user()->RecordOwnerID)->first();
            if ($mekanik) {
                $mekanik->delete();
                return response()->json(['success' => true, 'message' => 'Data Mekanik berhasil dihapus']);
            }
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan, mungkin data sedang digunakan: ' . $e->getMessage()]);
        }
    }
}
