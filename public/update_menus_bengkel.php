<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Menyusun ulang menu Display Bengkel...\n";

try {
    DB::beginTransaction();

    // 1. Cek apakah "Display Bengkel" sudah ada untuk menghindari duplikasi
    $existingBengkel = DB::table('permission')
        ->where('PermissionName', 'Display Bengkel')
        ->where('MenuInduk', 114)
        ->first();

    if ($existingBengkel) {
        $idBengkel = $existingBengkel->id;
        echo "Menu 'Display Bengkel' sudah ada dengan ID: " . $idBengkel . "\n";
    } else {
        // Buat Sub Menu "Display Bengkel"
        $idBengkel = DB::table('permission')->insertGetId([
            'PermissionName' => 'Display Bengkel',
            'Link' => '#',
            'Icon' => 'fas fa-tools', // Menggunakan ikon tools/bengkel
            'Level' => 2,
            'MenuInduk' => 114,
            'SubMenu' => 1,
            'Order' => 3, // Order di bawah Apotek (1 FnB, 2 Apotek)
            'Status' => 1,
            'isSuperAdmin' => 0
        ]);
        echo "Berhasil membuat menu 'Display Bengkel' dengan ID: " . $idBengkel . "\n";
    }

    // 2. Pindahkan menu "Antrian Bengkel" (id 127) ke bawah "Display Bengkel"
    $affected = DB::table('permission')
        ->where('id', 127)
        ->update([
            'Level' => 3,
            'MenuInduk' => $idBengkel,
            'SubMenu' => 0
        ]);

    echo "Berhasil memindahkan " . $affected . " menu ke bawah 'Display Bengkel'.\n";

    DB::commit();
    echo "Selesai!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
