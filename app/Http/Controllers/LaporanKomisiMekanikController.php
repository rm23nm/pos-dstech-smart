<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Mekanik;
use App\Models\Company;

class LaporanKomisiMekanikController extends Controller
{
    public function index(Request $request)
    {
        $mekanik = Mekanik::where('RecordOwnerID', Auth::user()->RecordOwnerID)
                          ->where('Status', 1)
                          ->get();

        $company = Company::where('KodePartner', Auth::user()->RecordOwnerID)->first();

        return view('report.KomisiMekanik', [
            'mekanik' => $mekanik,
            'company' => $company
        ]);
    }

    public function getData(Request $request)
    {
        $TglAwal = $request->input('TglAwal');
        $TglAkhir = $request->input('TglAkhir');
        $KodeMekanik = $request->input('KodeMekanik');

        $query = DB::table('fakturpenjualandetail')
            ->select(
                'fakturpenjualandetail.NoTransaksi',
                'fakturpenjualanheader.TglTransaksi',
                'fakturpenjualandetail.KodeItem',
                'itemmaster.NamaItem',
                'fakturpenjualandetail.Qty',
                'fakturpenjualandetail.KomisiMekanik',
                'mekanik.NamaMekanik'
            )
            ->leftJoin('fakturpenjualanheader', function($join) {
                $join->on('fakturpenjualanheader.NoTransaksi', '=', 'fakturpenjualandetail.NoTransaksi')
                     ->on('fakturpenjualanheader.RecordOwnerID', '=', 'fakturpenjualandetail.RecordOwnerID');
            })
            ->leftJoin('itemmaster', function($join) {
                $join->on('itemmaster.KodeItem', '=', 'fakturpenjualandetail.KodeItem')
                     ->on('itemmaster.RecordOwnerID', '=', 'fakturpenjualandetail.RecordOwnerID');
            })
            // Use KodeMekanik from the header (if it's saved there), or from PKB
            ->leftJoin('mekanik', function($join) {
                $join->on('mekanik.KodeMekanik', '=', 'fakturpenjualanheader.KodeMekanik')
                     ->on('mekanik.RecordOwnerID', '=', 'fakturpenjualanheader.RecordOwnerID');
            })
            ->where('fakturpenjualandetail.RecordOwnerID', Auth::user()->RecordOwnerID)
            ->where('fakturpenjualanheader.Status', '!=', 'D')
            ->where('fakturpenjualandetail.KomisiMekanik', '>', 0);

        if ($TglAwal && $TglAkhir) {
            $query->whereBetween('fakturpenjualanheader.TglTransaksi', [$TglAwal . ' 00:00:00', $TglAkhir . ' 23:59:59']);
        }

        if ($KodeMekanik) {
            $query->where('fakturpenjualanheader.KodeMekanik', $KodeMekanik);
        }

        $data = $query->orderBy('fakturpenjualanheader.TglTransaksi', 'desc')->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
