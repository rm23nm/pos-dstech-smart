<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Memulai Tahap 1: Migrasi Skema Database Multi-Cabang...\n";

// 1. Create cabang table if not exists
if (!Schema::hasTable('cabang')) {
    Schema::create('cabang', function (Blueprint $table) {
        $table->string('KodeCabang', 50)->primary();
        $table->string('NamaCabang', 100);
        $table->text('Alamat')->nullable();
        $table->string('NoTlp', 50)->nullable();
        $table->string('RecordOwnerID', 50);
        $table->timestamps();
    });
    echo "- Tabel 'cabang' berhasil dibuat.\n";
} else {
    echo "- Tabel 'cabang' sudah ada.\n";
}

// 2. Add KodeCabang to transaction tables
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
        if (!Schema::hasColumn($tbl, 'KodeCabang')) {
            Schema::table($tbl, function (Blueprint $table) {
                // Set default to 'PUSAT' so existing queries/data don't break immediately
                $table->string('KodeCabang', 50)->default('PUSAT')->after('RecordOwnerID');
            });
            echo "- Kolom KodeCabang ditambahkan ke tabel '$tbl'.\n";
        } else {
            echo "- Kolom KodeCabang sudah ada di tabel '$tbl'.\n";
        }
    }
}

// 3. Add Permission AllowMultiBranch
$permName = 'AllowMultiBranch';
$permDesc = 'Mengizinkan pengguna untuk membuka dan mengelola lebih dari 1 cabang (Multi-Branch).';

$exists = DB::table('permission')->where('PermissionName', $permName)->exists();
if (!$exists) {
    DB::table('permission')->insert([
        'PermissionName' => $permName,
        'Deskripsi' => $permDesc,
        'Grup' => 'Sistem & Pengaturan',
        'isDeleted' => 0
    ]);
    echo "- Permission '$permName' berhasil ditambahkan.\n";
} else {
    echo "- Permission '$permName' sudah ada.\n";
}

echo "\nTAHAP 1 SELESAI!\n";
