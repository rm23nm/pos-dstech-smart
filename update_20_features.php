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

$common = [
    'Transaksi Kasir Cepat (POS)',
    'Multi-Metode Pembayaran (Cash, QRIS, EDC)',
    'Cetak Struk Kasir (Thermal Printer)',
    'Manajemen Shift Karyawan',
    'Hak Akses Karyawan Berjenjang',
    'Manajemen Pelanggan (Database Member)',
    'Sistem Diskon & Voucher Promo',
    'Riwayat Transaksi & Void Kasir',
    'Manajemen Kas Masuk & Kas Keluar',
    'Laporan Penjualan Harian & Bulanan',
    'Laporan Laba Rugi Analitik',
    'Pencatatan Pajak & Biaya Layanan',
    'Manajemen Stok & Inventaris',
    'Peringatan Stok Menipis (Low Stock)',
    'Multi-Gudang (Warehouse Management)',
    'Manajemen Retur Penjualan',
    'Manajemen Pembelian (Supplier)',
    'Pembukuan Akuntansi & Piutang',
    'Dashboard Analitik Visual',
    'Keamanan & Backup Data Cloud 24/7'
];

// 20+ features for each type
$featuresRetail = $common;
$featuresRetail[12] = 'Manajemen Stok Terpusat (Akumulasi)';
$featuresRetail[18] = 'Dukungan Barcode Scanner';
$featuresRetail[19] = 'Cetak Label Harga Barang';
$featuresRetail[] = 'Harga Bertingkat (Grosir & Ecer)';

$featuresFnB = $common;
$featuresFnB[1] = 'Manajemen Meja (Dine-in & Takeout)';
$featuresFnB[2] = 'Cetak Tiket Dapur (Kitchen Printer)';
$featuresFnB[12] = 'Manajemen Resep & Bahan Baku';
$featuresFnB[13] = 'Split Bill & Gabung Pembayaran';
$featuresFnB[18] = 'Variasi Menu & Topping (Add-ons)';

$featuresApotek = $common;
$featuresApotek[12] = 'Manajemen Stok Obat & Farmasi';
$featuresApotek[13] = 'Pencatatan Expired Date Obat';
$featuresApotek[14] = 'Peringatan Obat Mendekati Kadaluarsa';
$featuresApotek[18] = 'Pencatatan Nama Dokter & Pasien';
$featuresApotek[19] = 'Dukungan Barcode Scanner';

$featuresTiketGate = $common;
$featuresTiketGate[1] = 'Cetak Tiket & Gelang Barcode Instan';
$featuresTiketGate[2] = 'Integrasi Smart Gate / Barrier';
$featuresTiketGate[12] = 'Pengaturan Harga Tiket Fleksibel (Hari)';
$featuresTiketGate[13] = 'Laporan Jumlah Pengunjung Harian';
$featuresTiketGate[18] = 'Validasi Tiket Cepat & Akurat';

function generateHtml($featuresArr, $crossCount = 0) {
    global $check, $cross;
    $html = "<ul>";
    $total = count($featuresArr);
    for ($i=0; $i<$total; $i++) {
        if ($i >= $total - $crossCount) {
            $html .= wrapLi($cross, $featuresArr[$i]);
        } else {
            $html .= wrapLi($check, $featuresArr[$i]);
        }
    }
    $html .= "</ul>";
    return $html;
}

$features = [
    'Retail' => generateHtml($featuresRetail),
    'FnB' => generateHtml($featuresFnB),
    'Apotek' => generateHtml($featuresApotek),
    'TiketGate' => generateHtml($featuresTiketGate),
];

// Hiburan features (Base 21 features)
$hiburanArr = [
    'Transaksi Kasir Cepat (POS)',
    'Billing Waktu (Hitungan per Jam/Menit)',
    'Manajemen Meja / Lapangan (Billiard/Futsal)',
    'Multi-Metode Pembayaran (QRIS, EDC, Tunai)',
    'Cetak Struk Kasir & Nota',
    'Manajemen Shift Karyawan',
    'Hak Akses Karyawan Berjenjang',
    'Riwayat Transaksi & Void Kasir',
    'Manajemen Kas Masuk & Kas Keluar',
    'Laporan Penjualan Harian',
    'Manajemen Stok F&B (Minuman/Snack)',
    'Sistem Diskon & Voucher',
    'Pencatatan Pajak & Biaya Layanan',
    'Manajemen Retur & Pembelian',
    'Booking & Reservasi Lapangan Online/Offline',
    'Manajemen Member & Database Pelanggan',
    'Integrasi Smart Relay IoT (Lampu Otomatis)',
    'Laporan Laba Rugi & Analitik Lanjut',
    'Multi-Gudang (Warehouse Management)',
    'Kontrol Alat / Perangkat Otomatis',
    'Priority Support 24/7'
];

// Basic: Cross out the last 7 features
// Pro: Cross out the last 4 features
// Premium: All checked
$hiburanFeatures = [
    '2002' => generateHtml($hiburanArr, 7), // BASIC
    '2003' => generateHtml($hiburanArr, 4), // PRO
    '2022' => generateHtml($hiburanArr, 0), // PREMIUM
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
    
    foreach ($hiburanFeatures as $id => $desc) {
        DB::table('subscriptionheader')
            ->where('NoTransaksi', (string)$id)
            ->update(['DeskripsiSubscription' => $desc]);
        echo "Update paket hiburan $id berhasil.\n";
    }

    DB::commit();
    echo "Selesai menambahkan 20+ fitur ke semua paket.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Gagal: " . $e->getMessage() . "\n";
}
