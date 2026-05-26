<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Memulai Rollback Tahap 1: Membatalkan Multi-Cabang...\n";

// 1. Drop cabang table if exists
if (Schema::hasTable('cabang')) {
    Schema::drop('cabang');
    echo "- Tabel 'cabang' berhasil dihapus.\n";
} else {
    echo "- Tabel 'cabang' sudah tidak ada.\n";
}

// 2. Remove KodeCabang from transaction tables
$tablesToUpdate = [
    'fakturpenjualanheader',
    'fakturpembelianheader',
    'kasmasukheader',
    'kaskeluarheader',
    'pembayaranheader',
    'orderpenjualanheader',
    'orderpembelianheader',
    'returpenjualanheader',
    'returpembelianheader'
];

foreach ($tablesToUpdate as $tbl) {
    if (Schema::hasTable($tbl)) {
        if (Schema::hasColumn($tbl, 'KodeCabang')) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropColumn('KodeCabang');
            });
            echo "- Kolom KodeCabang dihapus dari tabel '$tbl'.\n";
        } else {
            echo "- Kolom KodeCabang tidak ditemukan di tabel '$tbl'.\n";
        }
    }
}

// 3. Remove Permission AllowMultiBranch
$permName = 'AllowMultiBranch';
$deleted = DB::table('permission')->where('PermissionName', $permName)->delete();
if ($deleted) {
    echo "- Permission '$permName' berhasil dihapus.\n";
}

echo "\nROLLBACK SELESAI! Sistem kembali seperti semula.\n";
