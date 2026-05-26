<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$check = "<i class='fas fa-check-circle' style='color: #25D366; margin-right:5px;'></i>";
$cross = "<i class='fas fa-times-circle' style='color: #dc3545; margin-right:5px;'></i>";

function wrapLi($icon, $text) {
    $color = strpos($icon, 'fa-times-circle') !== false ? '#888' : '#333';
    return "<li>{$icon} <span style='color: {$color};'>{$text}</span></li>";
}

$features = [
    'Retail' => "<ul>
        " . wrapLi($check, 'Transaksi Kasir POS') . "
        " . wrapLi($check, 'Manajemen Stok Terpusat') . "
        " . wrapLi($check, 'Dukungan Barcode Scanner') . "
        " . wrapLi($check, 'Laporan Keuangan & Penjualan') . "
        " . wrapLi($check, 'Manajemen Pelanggan (Member)') . "
        " . wrapLi($check, 'Manajemen Karyawan & Hak Akses') . "
        " . wrapLi($check, 'Multi-Gudang (Warehouse)') . "
        " . wrapLi($check, 'Multi Metode Pembayaran') . "
        " . wrapLi($check, 'Cetak Struk & Label Harga') . "
    </ul>",
    'FnB' => "<ul>
        " . wrapLi($check, 'Transaksi Kasir POS') . "
        " . wrapLi($check, 'Manajemen Meja (Dine-in, Takeaway)') . "
        " . wrapLi($check, 'Split Bill & Gabung Meja') . "
        " . wrapLi($check, 'Cetak Tiket Dapur (Kitchen Printer)') . "
        " . wrapLi($check, 'Kontrol Resep & Bahan Baku') . "
        " . wrapLi($check, 'Laporan Keuangan & Penjualan') . "
        " . wrapLi($check, 'Manajemen Karyawan & Hak Akses') . "
        " . wrapLi($check, 'Multi-Gudang (Warehouse)') . "
        " . wrapLi($check, 'Manajemen Pelanggan (Member)') . "
    </ul>",
    'Apotek' => "<ul>
        " . wrapLi($check, 'Transaksi Kasir POS') . "
        " . wrapLi($check, 'Pencatatan Expired Date Obat') . "
        " . wrapLi($check, 'Manajemen Stok Farmasi') . "
        " . wrapLi($check, 'Dukungan Barcode Scanner') . "
        " . wrapLi($check, 'Laporan Penjualan Medis') . "
        " . wrapLi($check, 'Multi-Gudang Farmasi') . "
        " . wrapLi($check, 'Manajemen Pasien / Member') . "
        " . wrapLi($check, 'Manajemen Karyawan & Hak Akses') . "
    </ul>",
    'TiketGate' => "<ul>
        " . wrapLi($check, 'Transaksi Kasir Tiket') . "
        " . wrapLi($check, 'Cetak Tiket & Gelang Barcode Instan') . "
        " . wrapLi($check, 'Integrasi Smart Gate / Barrier') . "
        " . wrapLi($check, 'Analitik Jumlah Pengunjung') . "
        " . wrapLi($check, 'Pengaturan Harga Tiket Fleksibel') . "
        " . wrapLi($check, 'Laporan Keuangan & Penjualan') . "
        " . wrapLi($check, 'Manajemen Karyawan & Hak Akses') . "
        " . wrapLi($check, 'Multi Metode Pembayaran') . "
    </ul>",
];

$hiburanFeatures = [
    '2002' => "<ul>" . // BASIC
        wrapLi($check, 'Transaksi Kasir POS') .
        wrapLi($check, 'Billing Waktu Standar') .
        wrapLi($check, 'Manajemen Meja / Lapangan') .
        wrapLi($check, 'Laporan Keuangan Harian') .
        wrapLi($check, 'Cetak Struk Kasir') .
        wrapLi($cross, 'Booking & Reservasi') .
        wrapLi($cross, 'Manajemen Member / Pelanggan') .
        wrapLi($cross, 'Integrasi Smart Relay IoT') .
        wrapLi($cross, 'Laporan Analitik Laba Rugi') .
        wrapLi($cross, 'Multi-Gudang (Warehouse)') .
        wrapLi($cross, 'Kontrol Alat Otomatis') .
    "</ul>",
    
    '2003' => "<ul>" . // PRO
        wrapLi($check, 'Transaksi Kasir POS') .
        wrapLi($check, 'Billing Waktu Standar') .
        wrapLi($check, 'Manajemen Meja / Lapangan') .
        wrapLi($check, 'Laporan Keuangan Harian') .
        wrapLi($check, 'Cetak Struk Kasir') .
        wrapLi($check, 'Booking & Reservasi') .
        wrapLi($check, 'Manajemen Member / Pelanggan') .
        wrapLi($check, 'Integrasi Smart Relay IoT') .
        wrapLi($cross, 'Laporan Analitik Laba Rugi') .
        wrapLi($cross, 'Multi-Gudang (Warehouse)') .
        wrapLi($cross, 'Kontrol Alat Otomatis') .
    "</ul>",
    
    '2022' => "<ul>" . // PREMIUM
        wrapLi($check, 'Transaksi Kasir POS') .
        wrapLi($check, 'Billing Waktu Standar') .
        wrapLi($check, 'Manajemen Meja / Lapangan') .
        wrapLi($check, 'Laporan Keuangan Harian') .
        wrapLi($check, 'Cetak Struk Kasir') .
        wrapLi($check, 'Booking & Reservasi') .
        wrapLi($check, 'Manajemen Member / Pelanggan') .
        wrapLi($check, 'Integrasi Smart Relay IoT') .
        wrapLi($check, 'Laporan Analitik Laba Rugi') .
        wrapLi($check, 'Multi-Gudang (Warehouse)') .
        wrapLi($check, 'Kontrol Alat Otomatis') .
    "</ul>",
];

DB::beginTransaction();
try {
    // Update non-hiburan packages
    foreach ($features as $jenis => $desc) {
        $affected = DB::table('subscriptionheader')
            ->where('JenisUsaha', $jenis)
            ->where('NoTransaksi', 'LIKE', 'PKT-%')
            ->update(['DeskripsiSubscription' => $desc]);
        echo "Update $affected packages untuk $jenis\n";
    }
    
    // Update hiburan packages
    foreach ($hiburanFeatures as $id => $desc) {
        $idStr = (string)$id;
        DB::table('subscriptionheader')
            ->where('NoTransaksi', $idStr)
            ->update(['DeskripsiSubscription' => $desc]);
        echo "Update paket hiburan $idStr berhasil.\n";
    }

    DB::commit();
    echo "Selesai memperbarui SEMUA fitur secara lengkap.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Gagal: " . $e->getMessage() . "\n";
}
