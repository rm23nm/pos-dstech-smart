<?php
namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KlinikController extends Controller
{
    public function dashboard()
    {
        $polis = DB::table('klinik_polis')
            ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
            ->get();
            
        return view('klinik.dashboard.index', compact('polis'));
    }
}
