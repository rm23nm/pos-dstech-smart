<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$packages = [
    ['id' => '2002', // BASIC
     'Harga' => 1500000,
     'Deskripsi' => "<ul>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Billing Waktu Standar</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Meja / Lapangan</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Transaksi Harian</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Cetak Struk Kasir</li>
            <li><i class='fas fa-times-circle' style='color: #dc3545;'></i> Integrasi Smart Relay IoT</li>
        </ul>"
    ],
    ['id' => '2003', // PRO
     'Harga' => 2500000,
     'Deskripsi' => "<ul>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Semua Fitur Basic</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Booking & Reservasi</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Member</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Integrasi Smart Relay IoT</li>
            <li><i class='fas fa-times-circle' style='color: #dc3545;'></i> Laporan Analitik Lanjut</li>
        </ul>"
    ],
    ['id' => '2022', // PREMIUM
     'Harga' => 3500000,
     'Deskripsi' => "<ul>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Semua Fitur Pro</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Multi-cabang Terpusat</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Analitik Lanjut</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Priority Support 24/7</li>
            <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Kontrol Alat Otomatis</li>
        </ul>"
    ]
];

DB::beginTransaction();
try {
    foreach ($packages as $data) {
        $id = (string) $data['id'];
        DB::table('subscriptionheader')
            ->where('NoTransaksi', $id)
            ->update([
                'Harga' => $data['Harga'],
                'DeskripsiSubscription' => $data['Deskripsi']
            ]);
        echo "Update paket $id berhasil.\n";
    }
    DB::commit();
    echo "Selesai memperbarui paket Hiburan!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Gagal: " . $e->getMessage() . "\n";
}
