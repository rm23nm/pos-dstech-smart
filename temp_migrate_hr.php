<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// 1. Tambah kolom di tabel absensi
if (Schema::hasTable('absensi')) {
    Schema::table('absensi', function (Blueprint $table) {
        if (!Schema::hasColumn('absensi', 'LatitudeMasuk')) {
            $table->string('LatitudeMasuk')->nullable();
            $table->string('LongitudeMasuk')->nullable();
            $table->string('LatitudePulang')->nullable();
            $table->string('LongitudePulang')->nullable();
            $table->decimal('TotalJamKerja', 8, 2)->nullable();
            $table->decimal('JamLembur', 8, 2)->nullable();
            $table->decimal('DendaTelat', 15, 2)->nullable();
            $table->decimal('BonusLembur', 15, 2)->nullable();
        }
    });
    echo "Tabel absensi di-update.\n";
}

// 2. Buat tabel setting_absensi
if (!Schema::hasTable('setting_absensi')) {
    Schema::create('setting_absensi', function (Blueprint $table) {
        $table->id();
        $table->string('RecordOwnerID', 50)->nullable();
        $table->integer('ToleransiTelat')->default(0); // dalam menit
        $table->decimal('DendaTelatPerMenit', 15, 2)->default(0);
        $table->decimal('UpahLemburPerJam', 15, 2)->default(0);
        $table->string('TitikKordinatKantor')->nullable(); // format "lat,lng"
        $table->integer('RadiusBebasAbsen')->default(0); // dalam meter
        $table->timestamps();
    });
    echo "Tabel setting_absensi dibuat.\n";
}

// 3. Buat tabel pengajuan_absen
if (!Schema::hasTable('pengajuan_absen')) {
    Schema::create('pengajuan_absen', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('RecordOwnerID', 50)->nullable();
        $table->string('JenisPengajuan', 50); // Cuti Tahunan, Izin, Sakit
        $table->date('TanggalMulai');
        $table->date('TanggalSelesai');
        $table->text('Keterangan')->nullable();
        $table->longText('BuktiDokumen')->nullable();
        $table->string('StatusApproval', 50)->default('Pending');
        $table->unsignedBigInteger('ApprovedBy')->nullable();
        $table->timestamps();
    });
    echo "Tabel pengajuan_absen dibuat.\n";
}
