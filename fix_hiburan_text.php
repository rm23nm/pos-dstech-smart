<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$desc = "<ul>
    <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Semua Fitur Pro</li>
    <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Multi-Gudang (Warehouse)</li>
    <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Analitik Lanjut</li>
    <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Priority Support 24/7</li>
    <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Kontrol Alat Otomatis</li>
</ul>";

DB::beginTransaction();
try {
    DB::table('subscriptionheader')
        ->where('NoTransaksi', '2022')
        ->update(['DeskripsiSubscription' => $desc]);
    DB::commit();
    echo "Selesai memperbaiki teks fitur PREMIUM.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Gagal: " . $e->getMessage() . "\n";
}
