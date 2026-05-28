<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatServisController extends Controller
{
    public function index()
    {
        $company = DB::table('company')->where('KodePartner', Auth::user()->RecordOwnerID)->first();
        return view('Transaksi.RiwayatServis.index', compact('company'));
    }

    public function getData(Request $request)
    {
        $request->validate([
            'PlatNomor' => 'required|string'
        ]);

        $recordOwnerID = Auth::user()->RecordOwnerID;
        $platNomor = $request->PlatNomor;

        // Clean up string to compare without spaces
        $platClean = str_replace(' ', '', strtolower($platNomor));

        // Cari header transaksi yang memiliki plat nomor yang sesuai (mengabaikan spasi dan case)
        $headers = DB::table('fakturpenjualanheader')
            ->select('fakturpenjualanheader.NoTransaksi', 'fakturpenjualanheader.TglTransaksi', 'fakturpenjualanheader.PlatNomor', 'mekanik.NamaMekanik', 'fakturpenjualanheader.TotalPenjualan')
            ->leftJoin('mekanik', 'fakturpenjualanheader.KodeMekanik', '=', 'mekanik.KodeMekanik')
            ->where('fakturpenjualanheader.RecordOwnerID', $recordOwnerID)
            ->whereRaw("REPLACE(LOWER(fakturpenjualanheader.PlatNomor), ' ', '') = ?", [$platClean])
            ->orderBy('fakturpenjualanheader.TglTransaksi', 'DESC')
            ->get();

        $history = [];

        foreach ($headers as $header) {
            $details = DB::table('fakturpenjualandetail')
                ->select('item.NamaItem', 'fakturpenjualandetail.Qty', 'fakturpenjualandetail.Harga', 'fakturpenjualandetail.TotalTransaksi')
                ->leftJoin('item', 'fakturpenjualandetail.KodeItem', '=', 'item.KodeItem')
                ->where('fakturpenjualandetail.NoTransaksi', $header->NoTransaksi)
                ->where('fakturpenjualandetail.RecordOwnerID', $recordOwnerID)
                ->get();

            $header->details = $details;
            $history[] = $header;
        }

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
