<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DealerInventory;
use App\Models\ItemMaster;
use Auth;
use DB;

class DealerInventoryController extends Controller
{
    public function index(Request $request)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $inventory = DealerInventory::with('item')->where('RecordOwnerID', $RecordOwnerID)->get();
        return view('dealer.inventory.index', compact('inventory'));
    }

    public function create()
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        // Asumsi TypeItem = 7 untuk Kendaraan
        $items = ItemMaster::where('RecordOwnerID', $RecordOwnerID)
                ->where('TypeItem', 7) 
                ->get();
        return view('dealer.inventory.form', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'KodeItem' => 'required',
            'NoRangka' => 'required',
            'NoMesin' => 'required',
            'Tahun' => 'required|numeric',
        ]);

        $RecordOwnerID = Auth::user()->RecordOwnerID;
        
        $exists = DealerInventory::where('NoRangka', $request->NoRangka)
                    ->where('RecordOwnerID', $RecordOwnerID)
                    ->exists();
                    
        if($exists) {
            return redirect()->back()->with('error', 'No Rangka sudah ada di database!');
        }

        $inv = new DealerInventory();
        $inv->KodeItem = $request->KodeItem;
        $inv->NoRangka = $request->NoRangka;
        $inv->NoMesin = $request->NoMesin;
        $inv->Warna = $request->Warna;
        $inv->Tahun = $request->Tahun;
        $inv->HargaBeli = str_replace(',', '', $request->HargaBeli) ?? 0;
        $inv->Status = 0; // Ready
        $inv->RecordOwnerID = $RecordOwnerID;
        $inv->save();

        return redirect()->route('dealer.inventory.index')->with('success', 'Stok Unit berhasil ditambahkan');
    }

    public function edit($id)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $inventory = DealerInventory::where('id', $id)->where('RecordOwnerID', $RecordOwnerID)->firstOrFail();
        $items = ItemMaster::where('RecordOwnerID', $RecordOwnerID)->get();
        return view('dealer.inventory.form', compact('inventory', 'items'));
    }

    public function update(Request $request, $id)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $inv = DealerInventory::where('id', $id)->where('RecordOwnerID', $RecordOwnerID)->firstOrFail();
        
        $inv->KodeItem = $request->KodeItem;
        $inv->NoRangka = $request->NoRangka;
        $inv->NoMesin = $request->NoMesin;
        $inv->Warna = $request->Warna;
        $inv->Tahun = $request->Tahun;
        $inv->HargaBeli = str_replace(',', '', $request->HargaBeli) ?? 0;
        $inv->save();

        return redirect()->route('dealer.inventory.index')->with('success', 'Stok Unit berhasil diubah');
    }

    public function destroy(Request $request)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        DealerInventory::where('id', $request->id)->where('RecordOwnerID', $RecordOwnerID)->delete();
        return response()->json(['success' => true]);
    }
}
