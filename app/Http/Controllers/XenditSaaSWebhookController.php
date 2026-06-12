<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\PembayaranLangganan;
use App\Models\Company;

class XenditSaaSWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Xendit SaaS Webhook Received: ', $payload);

        // Check if webhook is from invoices
        if (isset($payload['status']) && isset($payload['external_id'])) {
            $status = $payload['status'];
            $invoice_id = $payload['external_id'];
            $amount_paid = isset($payload['paid_amount']) ? floatval($payload['paid_amount']) : floatval($payload['amount']);
            $payment_method = $payload['payment_method'] ?? 'XENDIT';

            if ($status === 'PAID') {
                try {
                    DB::beginTransaction();

                    // Cek apakah pembayaran sudah pernah dicatat
                    $isPaid = PembayaranLangganan::where('BaseReff', $invoice_id)->exists();
                    if ($isPaid) {
                        DB::rollBack();
                        return response()->json(['message' => 'Already paid'], 200);
                    }

                    $tagihan = DB::table('tagihanpenggunaheader')->where('NoTransaksi', $invoice_id)->first();
                    if (!$tagihan) {
                        DB::rollBack();
                        return response()->json(['message' => 'Invoice not found'], 404);
                    }

                    // 1. Simpan Pembayaran Langganan
                    $prefix = "PMB";
                    $lastNoTrx = PembayaranLangganan::where(DB::raw('LEFT(NoTransaksi,3)'), '=', $prefix)->count() + 1;
                    $NoTransaksi = $prefix . str_pad($lastNoTrx, 4, '0', STR_PAD_LEFT);

                    $model = new PembayaranLangganan;
                    $model->NoTransaksi      = $NoTransaksi;
                    $model->TglTransaksi     = Carbon::now()->format('Y-m-d');
                    $model->BaseReff         = $invoice_id;
                    $model->MetodePembayaran = $payment_method;
                    $model->NoReff           = $payload['id'] ?? $invoice_id;
                    $model->Keterangan       = 'Pembayaran SaaS otomatis via Xendit';
                    $model->TotalBayar       = $amount_paid;
                    $model->save();

                    // 2. Update Total Bayar Tagihan
                    DB::table('tagihanpenggunaheader')
                        ->where('NoTransaksi', $invoice_id)
                        ->update([
                            'TotalBayar' => DB::raw("TotalBayar + {$amount_paid}")
                        ]);

                    // 3. Perpanjang Masa Aktif Company
                    $RecordOwnerID = $tagihan->KodePelanggan;
                    
                    DB::table('company')
                        ->where('KodePartner', $RecordOwnerID)
                        ->update([
                            'StartSubs'        => Carbon::now()->format('Y-m-d'),
                            'EndSubs'          => Carbon::now()->addMonth()->format('Y-m-d'),
                            'isActive'         => 1,
                            'GudangPoS'        => 'UMM',
                            'isInitialSetting' => 0,
                        ]);

                    DB::commit();

                    // Kirim Notifikasi WA (Opsional)
                    try {
                        $company = Company::where('KodePartner', $RecordOwnerID)->first();
                        if ($company && !empty($company->NoHP)) {
                            $waMessage = "Halo *" . $company->NamaPartner . "*,\n\n"
                                       . "Terima kasih, pembayaran tagihan Anda sebesar *Rp " . number_format($amount_paid, 0, ',', '.') . "* telah berhasil kami terima via Xendit.\n\n"
                                       . "Rincian Pembayaran:\n"
                                       . "No Ref: *" . $invoice_id . "*\n"
                                       . "Masa Aktif Berakhir: *" . Carbon::now()->addMonth()->format('d/m/Y') . "*\n\n"
                                       . "Akun Anda saat ini sudah aktif dan dapat digunakan kembali.\n\n"
                                       . "Salam hangat,\n*Tim DSMS POS*";
                            
                            $smartpro = new \App\Services\SmartProService();
                            $smartpro->sendWhatsAppMessage($company->NoHP, $waMessage);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error sending WA payment via Xendit Webhook: ' . $e->getMessage());
                    }

                    return response()->json(['message' => 'Payment processed successfully'], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error processing Xendit SaaS Webhook: ' . $e->getMessage());
                    return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
                }
            }
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
}
