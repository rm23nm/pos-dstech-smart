<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JasaMedisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jasas = DB::table('klinik_medical_services')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('klinik.jasa.index', compact('jasas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'KodeJasa' => 'required|string|max:50',
            'NamaJasa' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
            'Deskripsi' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        DB::table('klinik_medical_services')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'KodeJasa' => $request->KodeJasa,
            'NamaJasa' => $request->NamaJasa,
            'Harga' => $request->Harga,
            'Deskripsi' => $request->Deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-jasa')->with('success', 'Jasa Medis berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'KodeJasa' => 'required|string|max:50',
            'NamaJasa' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        DB::table('klinik_medical_services')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->update([
            'KodeJasa' => $request->KodeJasa,
            'NamaJasa' => $request->NamaJasa,
            'Harga' => $request->Harga,
            'Deskripsi' => $request->Deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('klinik-jasa')->with('success', 'Jasa Medis berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        DB::table('klinik_medical_services')->where('id', $id)->where('RecordOwnerID', $user->RecordOwnerID)->delete();
        return redirect()->route('klinik-jasa')->with('success', 'Jasa Medis berhasil dihapus.');
    }
}
