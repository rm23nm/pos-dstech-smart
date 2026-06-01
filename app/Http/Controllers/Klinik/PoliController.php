<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PoliController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $polis = DB::table('klinik_polis')
            ->leftJoin('klinik_doctors', 'klinik_polis.DoctorID', '=', 'klinik_doctors.id')
            ->select('klinik_polis.*', 'klinik_doctors.NamaDokter')
            ->where('klinik_polis.RecordOwnerID', $user->RecordOwnerID)
            ->get();
        $doctors = DB::table('klinik_doctors')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('klinik.poli.index', compact('polis', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'KodePoli' => 'required|string|max:50',
            'NamaPoli' => 'required|string|max:255',
            'Shift' => 'nullable|string|max:255',
            'DoctorID' => 'nullable|integer',
            'VideoURL' => 'nullable|string|max:500',
            'Deskripsi' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        DB::table('klinik_polis')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'KodePoli' => $request->KodePoli,
            'NamaPoli' => $request->NamaPoli,
            'Shift' => $request->Shift,
            'JamMulai' => $request->JamMulai,
            'JamSelesai' => $request->JamSelesai,
            'DoctorID' => $request->DoctorID,
            'VideoURL' => $request->VideoURL,
            'Deskripsi' => $request->Deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-poli')->with('success', 'Master Poli berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'KodePoli' => 'required|string|max:50',
            'NamaPoli' => 'required|string|max:255',
            'Shift' => 'nullable|string|max:255',
            'DoctorID' => 'nullable|integer',
            'VideoURL' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        DB::table('klinik_polis')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
            'KodePoli' => $request->KodePoli,
            'NamaPoli' => $request->NamaPoli,
            'Shift' => $request->Shift,
            'JamMulai' => $request->JamMulai,
            'JamSelesai' => $request->JamSelesai,
            'DoctorID' => $request->DoctorID,
            'VideoURL' => $request->VideoURL,
            'Deskripsi' => $request->Deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-poli')->with('success', 'Master Poli berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::table('klinik_polis')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->delete();
        return redirect()->route('klinik-poli')->with('success', 'Master Poli berhasil dihapus.');
    }
}
