<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueueBengkelController extends Controller
{
    /**
     * Display the Bengkel queue management dashboard.
     */
    public function index($id)
    {
        $idE = base64_decode($id);
        $company = Company::where('KodePartner','=',$idE)->first();
        
        return view('Transaksi.Penjualan.QueueManagement.QueueBengkel',[
            'company' => $company,
            'idE' => $idE
        ]);
    }

    /**
     * Get real-time data for Bengkel queue via AJAX
     */
    public function getData(Request $request)
    {
        $today = Carbon::today('Asia/Jakarta');
        $recordOwnerID = $request->RecordOwnerID;

        // Fetch transactions for today that have a PlatNomor
        $transactionsRaw = DB::table('bengkel_work_orders')
            ->select(
                'bengkel_work_orders.NoPKB as NoTransaksi',
                'bengkel_work_orders.TglPKB as TglTransaksi',
                'bengkel_work_orders.PlatNomor',
                'bengkel_work_orders.KodeMekanik',
                'mekanik.NamaMekanik',
                'bengkel_work_orders.NamaPelanggan',
                'bengkel_work_orders.StatusServis'
            )
            ->leftJoin('mekanik', function ($join) {
                $join->on('bengkel_work_orders.KodeMekanik', '=', 'mekanik.KodeMekanik')
                     ->on('bengkel_work_orders.RecordOwnerID', '=', 'mekanik.RecordOwnerID');
            })
            ->where('bengkel_work_orders.RecordOwnerID', '=', $recordOwnerID)
            ->whereDate('bengkel_work_orders.TglPKB', '=', $today->toDateString())
            ->where('bengkel_work_orders.PlatNomor', '!=', '')
            ->whereNotNull('bengkel_work_orders.PlatNomor')
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('fakturpenjualanheader')
                      ->whereColumn('fakturpenjualanheader.NoPKB', 'bengkel_work_orders.NoPKB')
                      ->where('fakturpenjualanheader.Status', '!=', 'D');
            })
            ->orderBy('bengkel_work_orders.created_at', 'ASC')
            ->get();

        $menunggu = [];
        $dikerjakan = [];
        $selesai = [];

        foreach ($transactionsRaw as $trx) {
            if ($trx->StatusServis == 1) {
                $dikerjakan[] = $trx;
            } elseif ($trx->StatusServis == 2) {
                $selesai[] = $trx;
            } else {
                // Default is Menunggu (0)
                $menunggu[] = $trx;
            }
        }

        return response()->json([
            'menunggu' => $menunggu,
            'dikerjakan' => $dikerjakan,
            'selesai' => $selesai,
            'message' => 'Data fetched successfully'
        ]);
    }

    /**
     * Update the status of a service queue item
     */
    public function updateStatus(Request $request)
    {
        $noTransaksi = $request->input('NoTransaksi');
        $newStatus = $request->input('StatusServis');
        $recordOwnerID = Auth::user()->RecordOwnerID ?? $request->input('RecordOwnerID');

        try {
            DB::table('bengkel_work_orders')
                ->where('NoPKB', $noTransaksi)
                ->where('RecordOwnerID', $recordOwnerID)
                ->update(['StatusServis' => $newStatus, 'updated_at' => Carbon::now('Asia/Jakarta')]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
