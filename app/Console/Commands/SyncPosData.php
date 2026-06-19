<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncPosData extends Command
{
    protected $signature = 'pos:sync';
    protected $description = 'Sinkronisasi data POS offline (lokal) ke database Live MySQL';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Memulai sinkronisasi POS offline ke Live Database...');
        
        // Cek koneksi db live
        try {
            DB::connection('mysql_live')->getPdo();
        } catch (\Exception $e) {
            $this->error('Koneksi ke database live gagal. Sinkronisasi dibatalkan.');
            Log::error('POS Sync Error: ' . $e->getMessage());
            return 1;
        }

        // Ambil data fpenjualan yang belum disinkronkan
        $unsyncedSales = DB::table('fpenjualan')->where('is_synced', 0)->get();

        if ($unsyncedSales->isEmpty()) {
            $this->info('Tidak ada data yang perlu disinkronkan.');
            return 0;
        }

        $this->info('Ditemukan ' . $unsyncedSales->count() . ' transaksi untuk disinkronkan.');

        foreach ($unsyncedSales as $sale) {
            DB::connection('mysql_live')->beginTransaction();
            try {
                $saleArray = (array) $sale;
                // Pastikan kolom is_synced juga ada di Live, atau hapus key ini sebelum insert ke Live
                // unset($saleArray['is_synced']); 

                // 1. Insert header fpenjualan
                $exists = DB::connection('mysql_live')->table('fpenjualan')->where('NoTransaksi', $sale->NoTransaksi)->exists();
                if (!$exists) {
                    DB::connection('mysql_live')->table('fpenjualan')->insert($saleArray);
                }

                // 2. Sync Detail (fpenjualandetail)
                $details = DB::table('fpenjualandetail')->where('NoTransaksi', $sale->NoTransaksi)->get();
                foreach ($details as $detail) {
                    $detailExists = DB::connection('mysql_live')->table('fpenjualandetail')
                        ->where('NoTransaksi', $detail->NoTransaksi)
                        ->where('NoUrut', $detail->NoUrut)->exists();
                    if (!$detailExists) {
                        DB::connection('mysql_live')->table('fpenjualandetail')->insert((array) $detail);
                    }
                }

                // 3. Sync fkas (pembayaran) jika ada
                $kas = DB::table('fkas')->where('NoReff', $sale->NoTransaksi)->get();
                foreach ($kas as $k) {
                    $kasExists = DB::connection('mysql_live')->table('fkas')->where('NoTransaksi', $k->NoTransaksi)->exists();
                    if (!$kasExists) {
                        DB::connection('mysql_live')->table('fkas')->insert((array) $k);
                    }
                }

                // 4. Sync fjurnal jika ada
                $jurnals = DB::table('fjurnal')->where('NoReff', $sale->NoTransaksi)->get();
                foreach ($jurnals as $j) {
                    $jurnalExists = DB::connection('mysql_live')->table('fjurnal')
                        ->where('NoTransaksi', $j->NoTransaksi)
                        ->where('NoUrut', $j->NoUrut)->exists();
                    if (!$jurnalExists) {
                        DB::connection('mysql_live')->table('fjurnal')->insert((array) $j);
                    }
                }

                DB::connection('mysql_live')->commit();

                // Update lokal is_synced
                DB::table('fpenjualan')->where('NoTransaksi', $sale->NoTransaksi)->update(['is_synced' => 1]);
                $this->info("Berhasil sync: {$sale->NoTransaksi}");

            } catch (\Exception $e) {
                DB::connection('mysql_live')->rollBack();
                $this->error("Gagal sync {$sale->NoTransaksi}: " . $e->getMessage());
                Log::error("POS Sync Error pada {$sale->NoTransaksi}: " . $e->getMessage());
            }
        }

        $this->info('Sinkronisasi selesai.');
        return 0;
    }
}
