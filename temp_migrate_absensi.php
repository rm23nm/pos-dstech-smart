<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// 1. Create absensi table
if (!Schema::hasTable('absensi')) {
    Schema::create('absensi', function (Blueprint $table) {
        $table->id();
        $table->string('RecordOwnerID', 50)->nullable();
        $table->date('Tanggal');
        $table->unsignedBigInteger('user_id'); // Karyawan yang absen
        $table->string('KodeShift', 50)->nullable();
        $table->datetime('JamMasuk')->nullable();
        $table->datetime('JamPulang')->nullable();
        $table->longText('FotoMasuk')->nullable(); // Base64
        $table->longText('FotoPulang')->nullable(); // Base64
        $table->string('StatusKehadiran', 50)->nullable(); // Tepat Waktu, Terlambat
        $table->text('Catatan')->nullable();
        $table->timestamps();
    });
    echo "Table absensi created.\n";
} else {
    echo "Table absensi already exists.\n";
}

// 2. Insert Permissions
$cek = DB::table('permission')->where('PermissionName', 'HRD / Kepegawaian')->first();
if (!$cek) {
    $indukId = DB::table('permission')->insertGetId([
        'PermissionName' => 'HRD / Kepegawaian',
        'Link' => '#',
        'Icon' => 'fas fa-users',
        'Level' => 1,
        'MenuInduk' => 0,
        'SubMenu' => 1,
        'Order' => 75,
        'Status' => 1,
        'isSuperAdmin' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    DB::table('permission')->insert([
        'PermissionName' => 'Absensi Saya',
        'Link' => 'absensi-saya',
        'Icon' => '',
        'Level' => 2,
        'MenuInduk' => $indukId,
        'SubMenu' => 1,
        'Order' => 1,
        'Status' => 1,
        'isSuperAdmin' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    DB::table('permission')->insert([
        'PermissionName' => 'Laporan Absensi',
        'Link' => 'laporan-absensi',
        'Icon' => '',
        'Level' => 2,
        'MenuInduk' => $indukId,
        'SubMenu' => 1,
        'Order' => 2,
        'Status' => 1,
        'isSuperAdmin' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "Permissions inserted.\n";
} else {
    echo "Permissions already exist.\n";
}
