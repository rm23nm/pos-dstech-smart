<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Services\SmartProService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBengkelServiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bengkel:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pesan WhatsApp pengingat servis bengkel berkala via Smartpro';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(SmartProService $smartPro)
    {
        $this->info('Mulai mengecek jadwal servis bengkel...');

        // Ambang batas servis berkala: 3 bulan (90 hari) setelah servis terakhir
        $thresholdDate = Carbon::now('Asia/Jakarta')->subDays(90)->format('Y-m-d');

        // Cari pelanggan yang terakhir servisnya tepat 90 hari yang lalu
        // Menggunakan tabel fakturpenjualanheader
        
        $companies = Company::where('isActive', 1)->get();

        $sentCount = 0;

        foreach ($companies as $company) {
            $kodePartner = $company->KodePartner;

            // Cari faktur terakhir tiap kendaraan
            $latestServices = DB::table('fakturpenjualanheader')
                ->where('RecordOwnerID', $kodePartner)
                ->whereNotNull('PlatNomor')
                ->select('PlatNomor', 'KodePelanggan', DB::raw('MAX(TglTrans) as last_service'))
                ->groupBy('PlatNomor', 'KodePelanggan')
                ->get();

            foreach ($latestServices as $service) {
                // Jika servis terakhir tepat pada thresholdDate
                // Kita gunakan substr untuk memotong Waktu jika TglTrans ada jamnya
                $lastServiceDate = substr($service->last_service, 0, 10);
                
                if ($lastServiceDate === $thresholdDate && !empty($service->KodePelanggan)) {
                    // Cek pelanggan untuk mendapatkan No HP
                    $pelanggan = DB::table('pelanggan')
                        ->where('RecordOwnerID', $kodePartner)
                        ->where('KodePelanggan', $service->KodePelanggan)
                        ->first();

                    if ($pelanggan && !empty($pelanggan->NoHP)) {
                        // Generate URL Booking
                        // pastikan tidak ada double slash
                        $bookingUrl = url("/booking-bengkel/{$kodePartner}");

                        $message = "Halo Bapak/Ibu {$pelanggan->NamaPelanggan},\n\n";
                        $message .= "Kami dari {$company->NamaPartner} ingin mengingatkan bahwa sudah 3 bulan sejak servis terakhir kendaraan Anda dengan plat nomor *{$service->PlatNomor}*.\n\n";
                        $message .= "Untuk menjaga performa kendaraan agar tetap prima, yuk jadwalkan servis berkala sekarang!\n\n";
                        $message .= "Silakan klik link berikut untuk melakukan booking antrean:\n";
                        $message .= "👉 {$bookingUrl}\n\n";
                        $message .= "Terima kasih atas kepercayaannya. Kami tunggu kedatangannya!";

                        try {
                            $smartPro->sendWhatsAppMessage($pelanggan->NoHP, $message);
                            $sentCount++;
                            Log::info("Service reminder sent to {$pelanggan->NoHP} for plat {$service->PlatNomor}");
                        } catch (\Exception $e) {
                            Log::error("Gagal mengirim reminder ke {$pelanggan->NoHP}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->info("Selesai. Total $sentCount pesan pengingat dikirim.");
        return 0;
    }
}
