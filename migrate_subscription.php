<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "Memulai migrasi paket subscription...\n";

DB::beginTransaction();

try {
    // 1. Dapatkan semua Permission ID
    $permissions = DB::table('permission')->pluck('id')->toArray();
    echo "Ditemukan " . count($permissions) . " permissions.\n";

    // 2. Daftar paket baru
    $now = Carbon::now();
    $packages = [
        'Retail' => [
            ['id' => 'PKT-RETAIL-1', 'nama' => 'POS Retail Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-RETAIL-12', 'nama' => 'POS Retail Tahunan', 'lama' => 12, 'harga' => 1500000],
        ],
        'FnB' => [
            ['id' => 'PKT-FNB-1', 'nama' => 'POS FnB Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-FNB-12', 'nama' => 'POS FnB Tahunan', 'lama' => 12, 'harga' => 1500000],
        ],
        'Hiburan' => [
            ['id' => 'PKT-HIBURAN-1', 'nama' => 'POS Hiburan Bulanan', 'lama' => 1, 'harga' => 150000],
            ['id' => 'PKT-HIBURAN-12', 'nama' => 'POS Hiburan Tahunan', 'lama' => 12, 'harga' => 1500000],
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

    // 3. Bersihkan tabel lama
    echo "Menghapus data subscription lama...\n";
    DB::table('subscriptiondetail')->delete();
    DB::table('subscriptionheader')->delete();

    // 4. Masukkan paket baru
    foreach ($packages as $jenisUsaha => $pkgs) {
        foreach ($pkgs as $pkg) {
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

            // Assign semua permission
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
            echo "Paket " . $pkg['nama'] . " (".$pkg['id'].") berhasil dibuat beserta ".count($details)." permissions.\n";
            
            // 5. Update company yang bersangkutan (Set ke Paket Tahunan)
            if ($pkg['lama'] == 12) {
                $affected = DB::table('company')->where('JenisUsaha', $jenisUsaha)->update([
                    'KodePaketLangganan' => $pkg['id']
                ]);
                echo "--> Migrasi $affected perusahaan $jenisUsaha ke paket ".$pkg['id']."\n";
            }
        }
    }

    // Jika ada company yang JenisUsahanya kosong atau tidak dikenali, set ke Retail Tahunan
    $affectedOther = DB::table('company')
        ->whereNull('JenisUsaha')
        ->orWhereNotIn('JenisUsaha', array_keys($packages))
        ->update(['KodePaketLangganan' => 'PKT-RETAIL-12']);
    if ($affectedOther > 0) {
        echo "--> Migrasi $affectedOther perusahaan tipe Lain-lain ke paket PKT-RETAIL-12\n";
    }

    DB::commit();
    echo "\nBERHASIL! Proses perombakan paket selesai.\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "GAGAL: " . $e->getMessage() . "\n";
}
