<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InfoPeracikanController extends Controller
{
    public function InfoPeracikan()
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        
        $company = DB::table('company')
            ->where('KodePartner', $RecordOwnerID)
            ->first();

        $jenisItems = DB::table('jenisitem')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->get();
        
        $tables = DB::table('fakturpenjualanheader')
            ->where('RecordOwnerID', $RecordOwnerID)
            ->whereNotNull('QueueNumber')
            ->where('QueueNumber', '!=', '')
            ->select('QueueNumber as id', 'QueueNumber')
            ->distinct()
            ->get();

        return view('Transaksi.Penjualan.InfoPeracikan', compact('company', 'jenisItems', 'tables'));
    }

    public function InfoPeracikanData(Request $request)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $searchTerm = $request->input('searchTerm');
        $tgl = $request->input('tgl', date('Y-m-d'));

        $query = DB::table('fakturpenjualandetail')
            ->join('itemmaster', function($join) {
                $join->on('fakturpenjualandetail.KodeItem', '=', 'itemmaster.KodeItem')
                     ->on('fakturpenjualandetail.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
            })
            ->join('fakturpenjualanheader', function($join) {
                $join->on('fakturpenjualandetail.NoTransaksi', '=', 'fakturpenjualanheader.NoTransaksi')
                     ->on('fakturpenjualandetail.RecordOwnerID', '=', 'fakturpenjualanheader.RecordOwnerID');
            })
            ->select(
                'fakturpenjualandetail.*',
                'itemmaster.NamaItem',
                'fakturpenjualanheader.TglTransaksi',
                'fakturpenjualanheader.Status',
                'fakturpenjualanheader.NamaPasien',
                'fakturpenjualanheader.NoResep',
                'fakturpenjualanheader.QueueNumber',
                'fakturpenjualanheader.peracikan_status'
            )
            ->where('fakturpenjualandetail.RecordOwnerID', $RecordOwnerID)
            ->where('fakturpenjualanheader.peracikan_status', '<', 3);

        if (!empty($tgl)) {
            $query->whereDate('fakturpenjualanheader.TglTransaksi', $tgl);
        }

        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('fakturpenjualanheader.NoTransaksi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('itemmaster.NamaItem', 'like', '%' . $searchTerm . '%')
                  ->orWhere('fakturpenjualanheader.NamaPasien', 'like', '%' . $searchTerm . '%')
                  ->orWhere('fakturpenjualanheader.NoResep', 'like', '%' . $searchTerm . '%');
            });
        }

        $items = $query->orderBy('fakturpenjualanheader.created_at', 'asc')->get();

        return response()->json($items);
    }

    public function InfoPeracikanMarkDone(Request $request)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $NoTransaksi = $request->input('NoTransaksi');
        $LineNumber = $request->input('LineNumber'); // NoUrut

        try {
            DB::table('fakturpenjualandetail')
                ->where('NoTransaksi', $NoTransaksi)
                ->where('NoUrut', $LineNumber)
                ->where('RecordOwnerID', $RecordOwnerID)
                ->update(['isCompleted' => 1]);

            // Auto update order status to "Proses Peracikan" (1) if it's currently "Masuk" (0)
            DB::table('fakturpenjualanheader')
                ->where('NoTransaksi', $NoTransaksi)
                ->where('RecordOwnerID', $RecordOwnerID)
                ->where('peracikan_status', 0)
                ->update(['peracikan_status' => 1]);

            // Check if all items are done to auto-set status to "Siap Diambil" (2)
            $allItems = DB::table('fakturpenjualandetail')
                ->where('NoTransaksi', $NoTransaksi)
                ->where('RecordOwnerID', $RecordOwnerID)
                ->get();

            $allDone = true;
            foreach ($allItems as $item) {
                if ($item->isCompleted == 0) {
                    $allDone = false;
                    break;
                }
            }

            if ($allDone) {
                DB::table('fakturpenjualanheader')
                    ->where('NoTransaksi', $NoTransaksi)
                    ->where('RecordOwnerID', $RecordOwnerID)
                    ->update(['peracikan_status' => 2]);
            }

            return response()->json(['success' => true, 'allDone' => $allDone]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function InfoPeracikanUpdateStatus(Request $request)
    {
        $RecordOwnerID = Auth::user()->RecordOwnerID;
        $NoTransaksi = $request->input('NoTransaksi');
        $Status = $request->input('Status'); // 0, 1, 2, 3

        try {
            DB::table('fakturpenjualanheader')
                ->where('NoTransaksi', $NoTransaksi)
                ->where('RecordOwnerID', $RecordOwnerID)
                ->update(['peracikan_status' => $Status]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
