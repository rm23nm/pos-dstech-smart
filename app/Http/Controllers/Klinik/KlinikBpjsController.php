<?php

namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KlinikBpjsController extends Controller
{
    public function setting()
    {
        $user = Auth::user();
        $setting = DB::table('klinik_bpjs_settings')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->first();

        return view('klinik.bpjs.setting', compact('setting'));
    }

    public function storeSetting(Request $request)
    {
        $user = Auth::user();
        
        $setting = DB::table('klinik_bpjs_settings')
            ->where('RecordOwnerID', $user->RecordOwnerID)
            ->first();

        $data = [
            'ConsID'    => $request->ConsID,
            'SecretKey' => $request->SecretKey,
            'UserKey'   => $request->UserKey,
            'BaseUrl'   => $request->BaseUrl,
            'isSandbox' => $request->has('isSandbox') ? 1 : 0,
            'isActive'  => $request->has('isActive') ? 1 : 0,
            'updated_at'=> now()
        ];

        if ($setting) {
            DB::table('klinik_bpjs_settings')
                ->where('RecordOwnerID', $user->RecordOwnerID)
                ->update($data);
        } else {
            $data['RecordOwnerID'] = $user->RecordOwnerID;
            $data['created_at'] = now();
            DB::table('klinik_bpjs_settings')->insert($data);
        }

        return back()->with('success', 'Pengaturan BPJS berhasil disimpan.');
    }
}
