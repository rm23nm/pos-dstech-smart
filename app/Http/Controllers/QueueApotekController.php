<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class QueueApotekController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $idE = base64_decode($id);
        } else {
            $idE = \Illuminate\Support\Facades\Auth::user()->RecordOwnerID;
        }
        $company = Company::where('KodePartner', '=', $idE)->first();

        // Get Display Settings for Apotek if they want to show videos/images
        $oImageData = [];
        for ($i = 1; $i <= 5; $i++) {
            $imgField = 'ImageCustDisplay' . $i;
            if (!empty($company->$imgField)) {
                array_push($oImageData, ['type' => 'image', 'url' => $company->$imgField]);
            }
        }
        for ($i = 1; $i <= 2; $i++) {
            $vidField = 'VideoCustomerDisplay' . $i;
            if (!empty($company->$vidField)) {
                $url = "https://www.youtube.com/embed/" . $company->$vidField . "?&mute=1&enablejsapi=1";
                array_push($oImageData, ['type' => 'video', 'url' => $url]);
            }
        }

        return view('Transaksi.Penjualan.QueueApotek', compact('company', 'oImageData', 'idE'));
    }

    public function CounterMonitorApotek()
    {
        return view('Transaksi.Penjualan.CounterMonitorApotek');
    }

    public function recallApotek(Request $request)
    {
        $NoTransaksi = $request->input('NoTransaksi');
        $RecordOwnerID = \Illuminate\Support\Facades\Auth::user()->RecordOwnerID;

        try {
            // Check if call_trigger column exists, if not, this will throw an error
            // But we will try to update it anyway
            DB::table('fakturpenjualanheader')
                ->where('NoTransaksi', $NoTransaksi)
                ->where('RecordOwnerID', $RecordOwnerID)
                ->increment('call_trigger');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function handleQueue(Request $request)
    {
        $RecordOwnerID = $request->RecordOwnerID;
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        // Antrean Masuk (Proses = 0 or null)
        $antreanMasuk = DB::table('fakturpenjualanheader')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->whereDate('TglTransaksi', $today->toDateString())
            ->where(function($q) {
                $q->whereNull('peracikan_status')
                  ->orWhere('peracikan_status', 0);
            })
            ->orderBy('TglTransaksi', 'asc')
            ->get();

        // Sedang Diramu (Proses = 1)
        $sedangDiramu = DB::table('fakturpenjualanheader')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->whereDate('TglTransaksi', $today->toDateString())
            ->where('peracikan_status', 1)
            ->orderBy('TglTransaksi', 'asc')
            ->get();

        // Siap Diambil (Siap = 2)
        $siapDiambil = DB::table('fakturpenjualanheader')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->whereDate('TglTransaksi', $today->toDateString())
            ->where('peracikan_status', 2)
            ->orderBy('TglTransaksi', 'desc')
            ->get();

        return response()->json([
            'antreanMasuk' => $antreanMasuk,
            'sedangDiramu' => $sedangDiramu,
            'siapDiambil' => $siapDiambil
        ]);
    }
}
