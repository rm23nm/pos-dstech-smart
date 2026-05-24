<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$perm = DB::table('permission')->where('PermissionName', 'Manajemen Gate')->first();
if ($perm) {
    $bound = DB::table('permissionrole')->where('RoleID', 1)->where('PermissionID', $perm->id)->first();
    if (!$bound) {
        DB::table('permissionrole')->insert([
            'RoleID' => 1,
            'PermissionID' => $perm->id,
            'RecordOwnerID' => ''
        ]);
        echo "Binding role untuk menu berhasil ditambahkan.\n";
    } else {
        echo "Binding sudah ada.\n";
    }
}
echo "Selesai.\n";
