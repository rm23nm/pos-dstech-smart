<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctors = DB::table('klinik_doctors')
            ->leftJoin('klinik_polis', 'klinik_doctors.PoliID', '=', 'klinik_polis.id')
            ->select('klinik_doctors.*', 'klinik_polis.NamaPoli')
            ->where('klinik_doctors.RecordOwnerID', $user->RecordOwnerID)
            ->get();
        $polis = DB::table('klinik_polis')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('klinik.doctors.index', compact('doctors', 'polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaDokter'    => 'required|string|max:255',
            'Spesialisasi'  => 'nullable|string|max:255',
            'NoHP'          => 'nullable|string|max:20',
            'JadwalPraktik' => 'nullable|string',
            'PoliID'        => 'nullable|integer',
        ]);

        $user = Auth::user();
        
        DB::table('klinik_doctors')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NamaDokter'    => $request->NamaDokter,
            'Spesialisasi'  => $request->Spesialisasi,
            'PoliID'        => $request->PoliID,
            'NoHP'          => $request->NoHP,
            'JadwalPraktik' => $request->JadwalPraktik,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('klinik-doctors')->with('success', 'Data Dokter berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaDokter'    => 'required|string|max:255',
            'Spesialisasi'  => 'nullable|string|max:255',
            'NoHP'          => 'nullable|string|max:20',
            'JadwalPraktik' => 'nullable|string',
        ]);

        $user = Auth::user();
        DB::table('klinik_doctors')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
            'NamaDokter'    => $request->NamaDokter,
            'Spesialisasi'  => $request->Spesialisasi,
            'PoliID'        => $request->PoliID,
            'NoHP'          => $request->NoHP,
            'JadwalPraktik' => $request->JadwalPraktik,
            'updated_at'    => now(),
        ]);

        return redirect()->route('klinik-doctors')->with('success', 'Data Dokter berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::table('klinik_doctors')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->delete();
        return redirect()->route('klinik-doctors')->with('success', 'Data Dokter berhasil dihapus.');
    }
}
