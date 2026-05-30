<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Menyusun ulang menu Display...\n";

try {
    DB::beginTransaction();

    // 1. Buat Sub Menu "Display FnB"
    $idFnb = DB::table('permission')->insertGetId([
        'PermissionName' => 'Display FnB',
        'Link' => '#',
        'Icon' => 'fas fa-utensils',
        'Level' => 2,
        'MenuInduk' => 114,
        'SubMenu' => 1,
        'Order' => 1,
        'Status' => 1,
        'isSuperAdmin' => 0
    ]);

    // 2. Buat Sub Menu "Display Apotek"
    $idApotek = DB::table('permission')->insertGetId([
        'PermissionName' => 'Display Apotek',
        'Link' => '#',
        'Icon' => 'fas fa-pills',
        'Level' => 2,
        'MenuInduk' => 114,
        'SubMenu' => 1,
        'Order' => 2,
        'Status' => 1,
        'isSuperAdmin' => 0
    ]);

    // 3. Pindahkan menu FnB ke bawah "Display FnB"
    $fnbMenuIds = [113, 115, 116, 117, 118];
    DB::table('permission')
        ->whereIn('id', $fnbMenuIds)
        ->update([
            'Level' => 3,
            'MenuInduk' => $idFnb,
            'SubMenu' => 0
        ]);

    // 4. Tambahkan menu baru untuk Apotek ke bawah "Display Apotek"
    DB::table('permission')->insert([
        [
            'PermissionName' => 'Monitor Peracikan Obat',
            'Link' => 'infoperacikan',
            'Icon' => 'fas fa-desktop',
            'Level' => 3,
            'MenuInduk' => $idApotek,
            'SubMenu' => 0,
            'Order' => 1,
            'Status' => 1,
            'isSuperAdmin' => 0
        ],
        [
            'PermissionName' => 'Antrean Pengambilan Obat',
            'Link' => 'queue-apotek',
            'Icon' => 'fas fa-users',
            'Level' => 3,
            'MenuInduk' => $idApotek,
            'SubMenu' => 0,
            'Order' => 2,
            'Status' => 1,
            'isSuperAdmin' => 0
        ],
        [
            'PermissionName' => 'Customer Display POS',
            'Link' => 'fpenjualan/custdisplay_new',
            'Icon' => 'fas fa-tablet-alt',
            'Level' => 3,
            'MenuInduk' => $idApotek,
            'SubMenu' => 0,
            'Order' => 3,
            'Status' => 1,
            'isSuperAdmin' => 0
        ]
    ]);

    // Pindahkan existing Customer Display POS (id: 119) ke FnB (sebagai opsi atau kita bisa delete karena udah buat di Apotek)
    // Atau biarkan 119 di FnB
    DB::table('permission')
        ->where('id', 119)
        ->update([
            'Level' => 3,
            'MenuInduk' => $idFnb,
            'SubMenu' => 0
        ]);

    DB::commit();
    echo "Selesai!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
