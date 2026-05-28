<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (!Schema::hasTable('bengkel_work_orders')) {
    Schema::create('bengkel_work_orders', function (Blueprint $table) {
        $table->id();
        $table->string('NoPKB', 50);
        $table->date('TglPKB');
        $table->string('PlatNomor', 20);
        $table->string('KodePelanggan', 50)->nullable();
        $table->string('NamaPelanggan', 100)->nullable();
        $table->string('KodeMekanik', 50)->nullable();
        $table->text('Keluhan')->nullable();
        $table->text('LaporanMekanik')->nullable();
        $table->integer('StatusServis')->default(0)->comment('0: Menunggu, 1: Dikerjakan, 2: Selesai, 3: Batal');
        $table->string('RecordOwnerID', 50);
        $table->timestamps();
    });
    echo "Tabel bengkel_work_orders berhasil dibuat.\n";
} else {
    echo "Tabel bengkel_work_orders sudah ada.\n";
}
