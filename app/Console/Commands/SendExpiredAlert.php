<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ItemMaster;
use App\Models\Company;
use App\Services\SmartProService;
use Carbon\Carbon;

class SendExpiredAlert extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'expired:send-alert {--recordOwnerID= : Jalankan hanya untuk satu client tertentu}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi WA harian ke client tentang barang yang akan/sudah expired';

    public function handle()
    {
        $specificClient = $this->option('recordOwnerID');

        $query = Company::whereNotNull('ExpiredAlertWA')
                        ->where('ExpiredAlertWA', '!=', '')
                        ->where('isActive', 1);

        if ($specificClient) {
            $query->where('KodePartner', $specificClient);
        }

        $companies = $query->get();

        if ($companies->isEmpty()) {
            $this->info('Tidak ada client dengan nomor WA notifikasi yang dikonfigurasi.');
            return 0;
        }

        $this->info('Memproses ' . $companies->count() . ' client...');

        foreach ($companies as $company) {
            try {
                $alertDays = $company->ExpiredAlertDays ?? 90;
                $namaToko  = $company->NamaPartner ?? 'Toko';
                $today     = Carbon::now()->format('d-m-Y');
                $threshold = Carbon::now()->addDays($alertDays)->format('Y-m-d');

                // Barang sudah expired
                $sudahExpired = ItemMaster::selectRaw(
                        "itemmaster.NamaItem, COALESCE(itemwarehouses.Qty,0) as Stock,
                         satuan.NamaSatuan, itemmaster.ExpiredDate")
                    ->leftJoin('itemwarehouses', function($j) {
                        $j->on('itemwarehouses.KodeItem','=','itemmaster.KodeItem')
                          ->on('itemwarehouses.RecordOwnerID','=','itemmaster.RecordOwnerID');
                    })
                    ->leftJoin('satuan', function($j) {
                        $j->on('satuan.KodeSatuan','=','itemmaster.Satuan')
                          ->on('satuan.RecordOwnerID','=','itemmaster.RecordOwnerID');
                    })
                    ->where('itemmaster.RecordOwnerID', $company->KodePartner)
                    ->whereNotNull('itemmaster.ExpiredDate')
                    ->whereDate('itemmaster.ExpiredDate', '<', Carbon::now()->format('Y-m-d'))
                    ->orderBy('itemmaster.ExpiredDate', 'ASC')
                    ->get();

                // Barang akan expired dalam threshold hari
                $akanExpired = ItemMaster::selectRaw(
                        "itemmaster.NamaItem, COALESCE(itemwarehouses.Qty,0) as Stock,
                         satuan.NamaSatuan, itemmaster.ExpiredDate")
                    ->leftJoin('itemwarehouses', function($j) {
                        $j->on('itemwarehouses.KodeItem','=','itemmaster.KodeItem')
                          ->on('itemwarehouses.RecordOwnerID','=','itemmaster.RecordOwnerID');
                    })
                    ->leftJoin('satuan', function($j) {
                        $j->on('satuan.KodeSatuan','=','itemmaster.Satuan')
                          ->on('satuan.RecordOwnerID','=','itemmaster.RecordOwnerID');
                    })
                    ->where('itemmaster.RecordOwnerID', $company->KodePartner)
                    ->whereNotNull('itemmaster.ExpiredDate')
                    ->whereDate('itemmaster.ExpiredDate', '>=', Carbon::now()->format('Y-m-d'))
                    ->whereDate('itemmaster.ExpiredDate', '<=', $threshold)
                    ->orderBy('itemmaster.ExpiredDate', 'ASC')
                    ->get();

                if ($sudahExpired->isEmpty() && $akanExpired->isEmpty()) {
                    $this->line("[{$company->KodePartner}] Tidak ada barang expired. Skip.");
                    continue;
                }

                // Susun pesan
                $pesan = "\u{1F6A8} *PERINGATAN EXPIRED BARANG* \u{1F6A8}\n";
                $pesan .= "*{$namaToko}*\n";
                $pesan .= "Tanggal: {$today}\n\n";
                $pesan .= "Berikut barang yang perlu diperhatikan:\n";

                if ($sudahExpired->isNotEmpty()) {
                    $pesan .= "\n\u{26D4} *SUDAH EXPIRED:*\n";
                    foreach ($sudahExpired as $item) {
                        $ed = Carbon::parse($item->ExpiredDate)->format('d-m-Y');
                        $pesan .= "\u{2022} {$item->NamaItem} | Stok: {$item->Stock} {$item->NamaSatuan} | ED: {$ed}\n";
                    }
                }

                if ($akanExpired->isNotEmpty()) {
                    $pesan .= "\n\u{26A0}\uFE0F *AKAN EXPIRED (dalam {$alertDays} hari):*\n";
                    foreach ($akanExpired as $item) {
                        $ed = Carbon::parse($item->ExpiredDate)->format('d-m-Y');
                        $pesan .= "\u{2022} {$item->NamaItem} | Stok: {$item->Stock} {$item->NamaSatuan} | ED: {$ed}\n";
                    }
                }

                $pesan .= "\nMohon segera ditindaklanjuti. \u{1F64F}";

                // Kirim ke semua nomor
                $nomors  = array_filter(array_map('trim', explode(',', $company->ExpiredAlertWA)));
                $apiKey  = $company->SmartproApiKey ?? null;
                $sender  = $company->SmartproSender ?? null;
                $sent = 0; $failed = 0;

                foreach ($nomors as $nomor) {
                    try {
                        $smartpro = new SmartProService();
                        $ok = $smartpro->sendExpiredNotification($nomor, $pesan, $apiKey, $sender);
                        if ($ok) $sent++; else $failed++;
                    } catch (\Exception $e) {
                        Log::error("[ExpiredAlert] [{$company->KodePartner}] Gagal kirim ke {$nomor}: " . $e->getMessage());
                        $failed++;
                    }
                }

                $this->info("[{$company->KodePartner}] {$namaToko}: Terkirim {$sent} nomor, Gagal {$failed} nomor.");
                Log::info("[ExpiredAlert] [{$company->KodePartner}] Terkirim:{$sent} Gagal:{$failed}");

            } catch (\Exception $e) {
                Log::error("[ExpiredAlert] Error client {$company->KodePartner}: " . $e->getMessage());
                $this->error("[{$company->KodePartner}] Error: " . $e->getMessage());
            }
        }

        $this->info('Selesai.');
        return 0;
    }
}
