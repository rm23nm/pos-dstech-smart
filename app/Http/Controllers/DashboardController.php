<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Log;

use App\Models\FakturPenjualanHeader;
use App\Models\FakturPenjualanDetail;
use App\Models\ItemMaster;
use App\Models\InvoicePenggunaHeader;
use App\Models\Company;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $roid = Auth::user()->RecordOwnerID;
        $now = Carbon::now();
        $awalTahun = $now->copy()->startOfYear()->toDateTimeString();
        $TglAwal = $now->copy()->startOfMonth()->toDateTimeString();
        $TglAkhir = $now->copy()->toDateTimeString();
        $HariIni = $now->copy()->startOfDay()->toDateTimeString();
        $Besok = $now->copy()->endOfDay()->toDateTimeString();

        // Optimized DayByDay
        $daybyday = FakturPenjualanHeader::selectRaw("SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID', $roid)
                        ->whereBetween('TglTransaksi', [$HariIni, $Besok])
                        ->where('Status', '!=', 'D')
                        ->first();

        // Optimized MTD
        $mtd = FakturPenjualanHeader::selectRaw("SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID', $roid)
                        ->whereBetween('TglTransaksi', [$TglAwal, $TglAkhir])
                        ->where('Status', '!=', 'D')
                        ->first();

        // Optimized YTD
        $ytd = FakturPenjualanHeader::selectRaw("SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID', $roid)
                        ->whereBetween('TglTransaksi', [$awalTahun, $TglAkhir])
                        ->where('Status', '!=', 'D')
                        ->first();
        
        $stockMinimum = ItemMaster::selectRaw("KodeItem, NamaItem, Stock")
                        ->where('RecordOwnerID', $roid)
                        ->whereColumn('Stock', '<=', 'StockMinimum')
                        ->where('Active', 'Y')
                        ->take(10)
                        ->get();

        $grafikpenjualan = FakturPenjualanHeader::selectRaw("DATE(TglTransaksi) Tanggal ,SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID', $roid)
                        ->whereBetween('TglTransaksi', [$TglAwal, $TglAkhir])
                        ->where('Status', '!=', 'D')
                        ->groupBy(DB::raw('DATE(TglTransaksi)'))
                        ->orderBy('TglTransaksi')
                        ->get();

        // Heavy SP - DISABLED FOR SPEED TEST
        $perbandinganharga = [];
        /*
        try {
            $perbandinganharga = DB::select('CALL rsp_perbandinganhargasupplier(?, ?, ?)', [$awalTahun, $TglAkhir, $roid]);
        } catch (\Exception $e) { Log::error("SP Error: " . $e->getMessage()); }
        */

        $topspender = FakturPenjualanHeader::selectRaw('pelanggan.NamaPelanggan, SUM(TotalPembelian) Total')
                    ->leftJoin('pelanggan', function ($value) use ($roid) {
                        $value->on('pelanggan.KodePelanggan', '=', 'fakturpenjualanheader.KodePelanggan')
                              ->on('pelanggan.RecordOwnerID', '=', 'fakturpenjualanheader.RecordOwnerID');
                    })
                    ->where('fakturpenjualanheader.RecordOwnerID', $roid)
                    ->whereBetween('fakturpenjualanheader.TglTransaksi', [$TglAwal, $TglAkhir])
                    ->where('fakturpenjualanheader.Status', '!=', 'D')
                    ->groupBy('pelanggan.NamaPelanggan')
                    ->orderByDesc('Total')
                    ->take(5)
                    ->get();
        
        $topItemPerformance = FakturPenjualanDetail::selectRaw('itemmaster.NamaItem, itemmaster.Satuan , SUM(fakturpenjualanheader.TotalPembelian) Total, SUM(fakturpenjualandetail.Qty) AS Qty')
                            ->join('fakturpenjualanheader', function ($value) use ($roid) {
                                $value->on('fakturpenjualanheader.NoTransaksi', '=', 'fakturpenjualandetail.NoTransaksi')
                                      ->on('fakturpenjualanheader.RecordOwnerID', '=', 'fakturpenjualandetail.RecordOwnerID');
                            })
                            ->join('itemmaster', function ($value) use ($roid) {
                                $value->on('itemmaster.KodeItem', '=', 'fakturpenjualandetail.KodeItem')
                                      ->on('itemmaster.RecordOwnerID', '=', 'fakturpenjualandetail.RecordOwnerID');
                            })
                            ->where('fakturpenjualanheader.RecordOwnerID', $roid)
                            ->whereBetween('fakturpenjualanheader.TglTransaksi', [$TglAwal, $TglAkhir])
                            ->where('fakturpenjualanheader.Status', '!=', 'D')
                            ->groupBy('itemmaster.NamaItem', 'itemmaster.Satuan')
                            ->orderByDesc('Total')
                            ->take(3) // Further limited for test
                            ->get();

    	return view("dashboard",[
            'daybyday' => $daybyday->Total ?? 0,
            'mtd' => $mtd->Total ?? 0,
            'ytd' => $ytd->Total ?? 0,
            'stockMinimum' => $stockMinimum,
            'grafikpenjualan' => $grafikpenjualan,
            'perbandinganharga' => $perbandinganharga,
            'topspender' => $topspender,
            'topItemPerformance' => $topItemPerformance
        ]);
    }

    public function filterOmzet(Request $request){
        $range = $request->input('range', 'hari');
        
        switch ($range) {
            case 'hari':
                // Display data from the 1st to the end of the current month
                $grafikpenjualan = FakturPenjualanHeader::selectRaw("DATE(fakturpenjualanheader.TglTransaksi) Tanggal ,SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                        ->whereBetween(DB::raw('DATE(fakturpenjualanheader.TglTransaksi)'), [now()->startOfMonth(), now()->endOfMonth()])
                        ->where('Status','<>',DB::raw("'D'"))
                        ->groupBy(DB::raw('DATE(fakturpenjualanheader.TglTransaksi)'))
                        ->orderBy(DB::raw('DATE(fakturpenjualanheader.TglTransaksi)'))
                        ->get();
                break;
            case 'minggu':
                // Display data from Monday to Sunday of the current week
                $grafikpenjualan = FakturPenjualanHeader::selectRaw("DAYNAME(fakturpenjualanheader.TglTransaksi) Tanggal ,SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                        ->whereBetween(DB::raw('DATE(fakturpenjualanheader.TglTransaksi)'), [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
                        ->where('Status','<>',DB::raw("'D'"))
                        ->groupBy(DB::raw('DAYNAME(fakturpenjualanheader.TglTransaksi)'))
                        ->orderBy(DB::raw('DAYOFWEEK(fakturpenjualanheader.TglTransaksi)'))
                        ->get();
                break;
            case 'bulan':
                // Display data from the beginning of the year to today, showing only the month names
                $grafikpenjualan = FakturPenjualanHeader::selectRaw("MONTHNAME(fakturpenjualanheader.TglTransaksi) Tanggal ,SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                        ->whereBetween(DB::raw('DATE(fakturpenjualanheader.TglTransaksi)'), [now()->startOfYear(), now()])
                        ->where('Status','<>',DB::raw("'D'"))
                        ->groupBy(DB::raw('MONTHNAME(fakturpenjualanheader.TglTransaksi)'))
                        ->orderBy(DB::raw('MONTH(fakturpenjualanheader.TglTransaksi)'))
                        ->get();
                break;
            case 'tahun':
                // Display data per year from the first transaction date
                $grafikpenjualan = FakturPenjualanHeader::selectRaw("YEAR(fakturpenjualanheader.TglTransaksi) Tanggal ,SUM(TotalPembelian) Total")
                        ->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)
                        ->where('Status','<>',DB::raw("'D'"))
                        ->groupBy(DB::raw('YEAR(fakturpenjualanheader.TglTransaksi)'))
                        ->orderBy(DB::raw('YEAR(fakturpenjualanheader.TglTransaksi)'))
                        ->get();
                break;
        }

        return response()->json([
            'grafik' => $grafikpenjualan
        ]);
    }

    function dashboardAdmin() {
        // dd(Auth::user()->RecordOwnerID);
        if(Auth::user()->RecordOwnerID != '999999'){
            auth()->user()->tokens()->delete();
            Auth::logout();
            return redirect('/');
        }
        $awalTahun = Carbon::now()->startOfYear()->toDateString();;
        $TglAwal = Carbon::now()->startOfMonth()->toDateString();
        $TglAkhir = Carbon::now();

        $daybyday = InvoicePenggunaHeader::selectRaw("SUM(TotalBayar) Total")
                        ->whereBetween(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'),[DB::raw("DATE('".$TglAkhir."')"), DB::raw("DATE('".$TglAkhir."')")])
                        ->get();
        
        $mtd = InvoicePenggunaHeader::selectRaw("SUM(TotalBayar) Total")
                        ->whereBetween(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'),[$TglAwal, $TglAkhir])
                        ->get();
        $ytd = InvoicePenggunaHeader::selectRaw("SUM(TotalBayar) Total")
                        ->whereBetween(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'),[$awalTahun, $TglAkhir])
                        ->get();
        
        $grafikpenjualan = InvoicePenggunaHeader::selectRaw("DATE(tagihanpenggunaheader.TglTransaksi) Tanggal ,SUM(TotalBayar) Total")
                        ->whereBetween(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'),[$TglAwal, $TglAkhir])
                        ->groupBy(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'))
                        ->orderBy(DB::raw('DATE(tagihanpenggunaheader.TglTransaksi)'))
                        ->get();

        $subshampirhabis = Company::join('userrole', 'company.KodePartner', '=', 'userrole.RecordOwnerID')
                            ->join('roles', function($join) {
                                $join->on('userrole.roleid', '=', 'roles.id')
                                    ->on('userrole.RecordOwnerID', '=', 'roles.RecordOwnerID');
                            })
                            ->join('users', function($join) {
                                $join->on('userrole.userid', '=', 'users.id')
                                    ->on('userrole.RecordOwnerID', '=', 'users.RecordOwnerID');
                            })
                            ->where('roles.RoleName', 'SuperAdmin')
                            ->where(function($query) {
                                $query->where('company.EndSubs', '>', DB::raw('NOW()'))
                                    ->orWhereBetween('company.EndSubs', [
                                        DB::raw('NOW()'),
                                        DB::raw('DATE_ADD(NOW(), INTERVAL 7 DAY)')
                                    ]);
                            })
                            ->select(
                                'company.NamaPartner',
                                'company.NamaPIC',
                                'company.NoTlp',
                                'users.email',
                                'company.EndSubs'
                            )->get();

        $subshabis = Company::join('userrole', 'company.KodePartner', '=', 'userrole.RecordOwnerID')
                            ->join('roles', function($join) {
                                $join->on('userrole.roleid', '=', 'roles.id')
                                    ->on('userrole.RecordOwnerID', '=', 'roles.RecordOwnerID');
                            })
                            ->join('users', function($join) {
                                $join->on('userrole.userid', '=', 'users.id')
                                    ->on('userrole.RecordOwnerID', '=', 'users.RecordOwnerID');
                            })
                            ->where('roles.RoleName', 'SuperAdmin')
                            ->where(DB::raw('NOW()'), '>', DB::raw('EndSubs'))
                            ->select(
                                'company.NamaPartner',
                                'company.NamaPIC',
                                'company.NoTlp',
                                'users.email'
                            )->get();
        $daftarbelumbayar = Company::join('userrole', 'company.KodePartner', '=', 'userrole.RecordOwnerID')
                            ->join('roles', function($join) {
                                $join->on('userrole.roleid', '=', 'roles.id')
                                    ->on('userrole.RecordOwnerID', '=', 'roles.RecordOwnerID');
                            })
                            ->join('users', function($join) {
                                $join->on('userrole.userid', '=', 'users.id')
                                    ->on('userrole.RecordOwnerID', '=', 'users.RecordOwnerID');
                            })
                            ->whereNull('EndSubs')
                            ->select(
                                'company.NamaPartner',
                                'company.NamaPIC',
                                'company.NoTlp',
                                'users.email'
                            )->get();
        $companyPerJenis = Company::select('JenisUsaha', DB::raw('COUNT(*) as jumlah'))
                            ->groupBy('JenisUsaha')
                            ->get();

        return view("dashboardadmin",[
            'daybyday' => $daybyday[0]['Total'],
            'mtd' => $mtd[0]['Total'],
            'ytd' => $ytd[0]['Total'],
            'grafikpenjualan' => $grafikpenjualan,
            'subshampirhabis' => $subshampirhabis,
            'subshabis' => $subshabis,
            'daftarbelumbayar' => $daftarbelumbayar,
            'companyPerJenis' => $companyPerJenis
        ]);
    }
}
