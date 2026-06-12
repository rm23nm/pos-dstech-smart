<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

use App\Models\HariLibur;

class HariLiburController extends Controller
{
    public function index(Request $request)
    {
        $roid = Auth::user()->RecordOwnerID;
        $tahun = $request->input('tahun', date('Y'));

        $libur = HariLibur::where('RecordOwnerID', $roid)
            ->whereYear('Tanggal', $tahun)
            ->orderBy('Tanggal', 'asc')
            ->get();

        return view('hrd.HariLibur', compact('libur', 'tahun'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'Tanggal' => 'required|date',
                'Keterangan' => 'required'
            ]);

            $libur = new HariLibur();
            $libur->RecordOwnerID = Auth::user()->RecordOwnerID;
            $libur->Tanggal = $request->input('Tanggal');
            $libur->Keterangan = $request->input('Keterangan');
            $libur->save();

            return response()->json(['success' => true, 'message' => 'Hari Libur berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menambah hari libur: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            $libur = HariLibur::where('id', $id)
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->first();

            if ($libur) {
                $libur->delete();
                return response()->json(['success' => true, 'message' => 'Hari Libur berhasil dihapus.']);
            }
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus hari libur: ' . $e->getMessage()]);
        }
    }
}
