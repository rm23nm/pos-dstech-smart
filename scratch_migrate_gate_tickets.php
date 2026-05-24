<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Mulai migrasi database...\n";

if (!Schema::hasColumn('tiket_masuk', 'KodeItem')) {
    Schema::table('tiket_masuk', function (Blueprint $table) {
        $table->string('KodeItem')->nullable()->after('NoTransaksi');
    });
    echo "Kolom KodeItem ditambahkan ke tiket_masuk.\n";
} else {
    echo "Kolom KodeItem sudah ada di tiket_masuk.\n";
}

if (!Schema::hasColumn('pelanggan', 'KodePaketMember')) {
    Schema::table('pelanggan', function (Blueprint $table) {
        $table->string('KodePaketMember')->nullable()->after('ValidUntil');
    });
    echo "Kolom KodePaketMember ditambahkan ke pelanggan.\n";
} else {
    echo "Kolom KodePaketMember sudah ada di pelanggan.\n";
}

if (!Schema::hasTable('gate_device_tickets')) {
    Schema::create('gate_device_tickets', function (Blueprint $table) {
        $table->id();
        $table->string('DeviceID');
        $table->string('KodeItem');
        $table->string('RecordOwnerID');
        $table->timestamps();
    });
    echo "Tabel gate_device_tickets berhasil dibuat.\n";
} else {
    echo "Tabel gate_device_tickets sudah ada.\n";
}

echo "Migrasi Selesai.\n";
