<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DealerPOSController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recordOwnerID = $user->RecordOwnerID;

        // Fetch data needed for POS (inventory, leasing, customers, etc.)
        $inventory = DB::table('dealer_inventory')
            ->where('RecordOwnerID', $recordOwnerID)
            ->where('Status', 'Tersedia')
            ->get();
            
        $leasing = DB::table('dealer_leasing')
            ->where('RecordOwnerID', $recordOwnerID)
            ->get();
            
        $customers = DB::table('dealer_customers')
            ->where('RecordOwnerID', $recordOwnerID)
            ->get();

        return view('dealer.pos.index', compact('inventory', 'leasing', 'customers'));
    }

    public function store(Request $request)
    {
        // To be implemented
    }

    public function invoice($id)
    {
        // To be implemented
    }

    public function bstb($id)
    {
        // To be implemented
    }
}
