<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// 3. Add Permission AllowMultiBranch
$permName = 'AllowMultiBranch';

$exists = DB::table('permission')->where('PermissionName', $permName)->exists();
if (!$exists) {
    DB::table('permission')->insert([
        'PermissionName' => $permName,
        'Link' => '#',
        'Icon' => 'fas fa-building',
        'Level' => 1,
        'MenuInduk' => 'Pengaturan',
        'SubMenu' => '',
        'Order' => 99,
        'Status' => 1,
        'isSuperAdmin' => 0
    ]);
    echo "- Permission '$permName' berhasil ditambahkan.\n";
} else {
    echo "- Permission '$permName' sudah ada.\n";
}
