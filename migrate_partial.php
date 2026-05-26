<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "Memulai migrasi parsial paket subscription...\n";

DB::beginTransaction();

try {
    $now = Carbon::now();
    $permissions = DB::table('permission')->pluck('id')->toArray();
    echo "Ditemukan " . count($permissions) . " permissions.\n";

    $packages = [
        'Retail' => [
            ['id' => 'PKT-RETAIL-1', 'nama' => 'POS Retail Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-RETAIL-12', 'nama' => 'POS Retail Tahunan', 'lama' => 12, 'harga' => 1500000],
        ],
        'FnB' => [
            ['id' => 'PKT-FNB-1', 'nama' => 'POS FnB Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-FNB-12', 'nama' => 'POS FnB Tahunan', 'lama' => 12, 'harga' => 1500000],
        ],
        'Apotek' => [
            ['id' => 'PKT-APOTEK-1', 'nama' => 'POS Apotek Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-APOTEK-12', 'nama' => 'POS Apotek Tahunan', 'lama' => 12, 'harga' => 1500000],
        ],
        'TiketGate' => [
            ['id' => 'PKT-TIKET-1', 'nama' => 'POS TiketGate Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-TIKET-12', 'nama' => 'POS TiketGate Tahunan', 'lama' => 12, 'harga' => 1500000],
        ]
    ];

    $newPackageIds = [];

    // 1. Masukkan paket baru
    foreach ($packages as $jenisUsaha => $pkgs) {
        foreach ($pkgs as $pkg) {
            $newPackageIds[] = $pkg['id'];
            DB::table('subscriptionheader')->insert([
                'NoTransaksi' => $pkg['id'],
                'Tanggal' => $now->format('Y-m-d'),
                'NamaSubscription' => $pkg['nama'],
                'DeskripsiSubscription' => 'Akses ' . $pkg['nama'] . ' full fitur selama ' . $pkg['lama'] . ' bulan.',
                'Harga' => $pkg['harga'],
                'Potongan' => 0,
                'LamaSubsription' => $pkg['lama'],
                'AllowAccounting' => 1,
                'AllowPesananMeja' => 1,
                'AllowPaymentGateway' => 1,
                'AllowKatalogOnline' => 1,
                'AllowMonitorAntrean' => 1,
                'JenisUsaha' => $jenisUsaha,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $details = [];
            $noUrut = 1;
            foreach ($permissions as $pid) {
                $details[] = [
                    'NoTransaksi' => $pkg['id'],
                    'PermissionID' => $pid,
                    'NoUrut' => $noUrut++,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            DB::table('subscriptiondetail')->insert($details);
            echo "Paket " . $pkg['nama'] . " (".$pkg['id'].") berhasil dibuat.\n";
            
            // 2. Update company yang bersangkutan (Set ke Paket Tahunan)
            if ($pkg['lama'] == 12) {
                $affected = DB::table('company')->where('JenisUsaha', $jenisUsaha)->update([
                    'KodePaketLangganan' => $pkg['id']
                ]);
                echo "--> Migrasi $affected perusahaan $jenisUsaha ke paket ".$pkg['id']."\n";
            }
        }
    }

    // 3. Hapus paket lama HANYA untuk Retail, FnB, Apotek, TiketGate
    // Kita hapus yang JenisUsahanya ada dalam daftar target, TAPI BUKAN ID Paket Baru
    $targetJenisUsaha = array_keys($packages);
    $oldPackages = DB::table('subscriptionheader')
        ->whereIn('JenisUsaha', $targetJenisUsaha)
        ->whereNotIn('NoTransaksi', $newPackageIds)
        ->pluck('NoTransaksi')->toArray();
        
    if (count($oldPackages) > 0) {
        echo "Menghapus " . count($oldPackages) . " paket lama (" . implode(", ", $oldPackages) . ")...\n";
        DB::table('subscriptiondetail')->whereIn('NoTransaksi', $oldPackages)->delete();
        DB::table('subscriptionheader')->whereIn('NoTransaksi', $oldPackages)->delete();
    } else {
        echo "Tidak ada paket lama yang perlu dihapus.\n";
    }

    DB::commit();
    echo "\nBERHASIL! Proses perombakan parsial selesai.\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "GAGAL: " . $e->getMessage() . "\n";
}
