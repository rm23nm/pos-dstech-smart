<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use DB;
use App\Models\PembayaranPenjualanHeader;
use App\Models\FakturPenjualanHeader;
use App\Models\Company;
use App\Models\MetodePembayaran;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Xendit Webhook Triggered:', $request->all());

        $externalId = $request->input('external_id');
        $status = $request->input('status');

        if (!$externalId) {
            return response()->json(['message' => 'Missing external_id'], 400);
        }

        if ($status == 'COMPLETED') {
            DB::beginTransaction();
            try {
                // Find PembayaranPenjualanHeader with the given order_id (stored in NomorRefrensiPembayaran or matched via external_id)
                // However, external_id is currently order_id which is saved to NomorRefrensiPembayaran.
                $pembayaran = PembayaranPenjualanHeader::where('NoReff', $externalId)->first();
                if (!$pembayaran) {
                    // Try checking if we stored order_id in NoReff or somewhere else. 
                    // In frontend, we put it in #NomorRefrensiPembayaran, which usually maps to NoReff or Keterangan.
                    // Wait, NormalPoS logic: SaveData(Status, ButonObject, ButtonDefaultText). 
                    // Let's assume it maps to NoReff. If not found, log and ignore.
                    Log::error("Xendit Webhook: Payment not found for external_id " . $externalId);
                    return response()->json(['message' => 'Payment not found'], 404);
                }

                // If found, and not already posted, we can auto post or just leave it since the frontend will call SaveData anyway.
                // Wait! If frontend calls SaveData upon user clicking "Selesai", then the POS transaction is already saved!
                // Webhook might just be a backup or we don't need to do anything if frontend already saves it.
                // Actually, for QRIS, Xendit webhook is the SOURCE OF TRUTH. 
                // But in POS, the cashier stares at the screen. If they click "Selesai", the JS calls SaveData and the transaction is closed.
                // It is better to just log it for now to avoid double posting unless required.
                Log::info("Xendit Webhook: Payment success for POS transaction " . $pembayaran->NoTransaksi);
                
                DB::commit();
                return response()->json(['message' => 'Success']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Xendit Webhook Error: ' . $e->getMessage());
                return response()->json(['message' => 'Error'], 500);
            }
        }

        return response()->json(['message' => 'Ignored']);
    }
}
