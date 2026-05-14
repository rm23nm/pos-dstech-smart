<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller {
    public function debugTables() {
        $roid = Auth::user()->RecordOwnerID;
        $titikLampu = DB::table('titiklampu')->where('RecordOwnerID', $roid)->get();
        $activeOrders = DB::table('tableorderheader')
            ->where('RecordOwnerID', $roid)
            ->whereIn('DocumentStatus', ['O', 'D'])
            ->get();
            
        return response()->json([
            'titik_lampu_count' => $titikLampu->count(),
            'titik_lampu_sample' => $titikLampu->where('DigitalInput', 4),
            'active_orders_count' => $activeOrders->count(),
            'active_orders_sample' => $activeOrders->where('tableid', 4),
            'master_controller' => DB::table('mastercontroller')->where('RecordOwnerID', $roid)->get()
        ]);
    }
}
