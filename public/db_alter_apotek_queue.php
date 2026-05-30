<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Memulai migrasi struktur database untuk Sistem Peracikan Obat Apotek...\n";

try {
    // 1. Tambah peracikan_status dan QueueNumber ke fakturpenjualanheader
    if (!Schema::hasColumn('fakturpenjualanheader', 'peracikan_status')) {
        Schema::table('fakturpenjualanheader', function (Blueprint $table) {
            $table->integer('peracikan_status')->default(0)->comment('0: Masuk, 1: Proses Peracikan, 2: Siap Diambil, 3: Selesai (Diserahkan)');
        });
        echo "Berhasil menambah kolom 'peracikan_status' pada tabel 'fakturpenjualanheader'.\n";
    } else {
        echo "Kolom 'peracikan_status' sudah ada pada tabel 'fakturpenjualanheader'.\n";
    }

    if (!Schema::hasColumn('fakturpenjualanheader', 'QueueNumber')) {
        Schema::table('fakturpenjualanheader', function (Blueprint $table) {
            $table->integer('QueueNumber')->nullable()->comment('Nomor antrean pengambilan obat');
        });
        echo "Berhasil menambah kolom 'QueueNumber' pada tabel 'fakturpenjualanheader'.\n";
    } else {
        echo "Kolom 'QueueNumber' sudah ada pada tabel 'fakturpenjualanheader'.\n";
    }

    // 2. Tambah isCompleted ke fakturpenjualandetail
    if (!Schema::hasColumn('fakturpenjualandetail', 'isCompleted')) {
        Schema::table('fakturpenjualandetail', function (Blueprint $table) {
            $table->integer('isCompleted')->default(0)->comment('Status apakah item ini sudah selesai diracik/disiapkan');
        });
        echo "Berhasil menambah kolom 'isCompleted' pada tabel 'fakturpenjualandetail'.\n";
    } else {
        echo "Kolom 'isCompleted' sudah ada pada tabel 'fakturpenjualandetail'.\n";
    }

    echo "\n=== MIGRATION SELESAI ===\n";

} catch (\Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage() . "\n";
}
