<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$features = [
    'Retail' => "<ul>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Transaksi Kasir Super Cepat</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Stok Terpusat</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Mendukung Barcode Scanner</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Penjualan Real-time</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Pelanggan (Loyalty)</li>
    </ul>",
    'FnB' => "<ul>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Meja & Pesanan</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Split Bill & Cetak Dapur</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Kontrol Resep & Bahan Baku</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Keuangan Harian</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Multi Metode Pembayaran</li>
    </ul>",
    'Apotek' => "<ul>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Pencatatan Expired Date</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Manajemen Stok Obat</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Laporan Penjualan Medis</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Dukungan Barcode Scanner</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Multi Shift Karyawan</li>
    </ul>",
    'TiketGate' => "<ul>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Pencetakan Tiket Instan</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Integrasi Smart Gate/Barrier</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Analitik Jumlah Pengunjung</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Pengaturan Harga Fleksibel</li>
        <li><i class='fas fa-check-circle' style='color: #25D366;'></i> Cetak Gelang Barcode</li>
    </ul>",
];

DB::beginTransaction();
try {
    foreach ($features as $jenis => $desc) {
        $affected = DB::table('subscriptionheader')
            ->where('JenisUsaha', $jenis)
            ->where('NoTransaksi', 'LIKE', 'PKT-%')
            ->update(['DeskripsiSubscription' => $desc]);
        echo "Update $affected packages untuk $jenis\n";
    }
    DB::commit();
    echo "Selesai memperbarui deskripsi fitur dengan tanda centang.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Gagal: " . $e->getMessage() . "\n";
}
