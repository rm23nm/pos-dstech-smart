<?php

namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KlinikAsuransiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $asuransis = DB::table('klinik_asuransis')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->get();

        return view('klinik.asuransi.index', compact('asuransis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'NamaAsuransi' => 'required|string|max:255',
        ]);

        DB::table('klinik_asuransis')->insert([
            'RecordOwnerID' => $user->RecordOwnerID,
            'NamaAsuransi'  => $request->NamaAsuransi,
            'isActive'      => $request->isActive ?? 1,
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        return back()->with('success', 'Asuransi berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id' => 'required',
            'NamaAsuransi' => 'required|string|max:255',
        ]);

        DB::table('klinik_asuransis')
            ->where('id', $request->id)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->update([
                'NamaAsuransi' => $request->NamaAsuransi,
                'isActive'     => $request->isActive ?? 1,
                'updated_at'   => now()
            ]);

        return back()->with('success', 'Asuransi berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        DB::table('klinik_asuransis')
            ->where('id', $request->id)
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->delete();

        return back()->with('success', 'Asuransi berhasil dihapus.');
    }
}
