<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $exists = DB::table('permission')->where('PermissionName', 'Slide Login')->exists();
    if (!$exists) {
        DB::table('permission')->insert([
            'PermissionName' => 'Slide Login',
            'Link' => 'loginslide',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => 100,
            'SubMenu' => 2,
            'Order' => '76,5',
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Permission 'Slide Login' added successfully.\n";
    } else {
        echo "Permission 'Slide Login' already exists.\n";
    }

    // Assign this permission to Super Admin (RoleID 1)
    $permission = DB::table('permission')->where('PermissionName', 'Slide Login')->first();
    $roleExists = DB::table('rolepermission')->where('RoleID', 1)->where('PermissionID', $permission->id)->exists();
    if (!$roleExists) {
        DB::table('rolepermission')->insert([
            'RoleID' => 1,
            'PermissionID' => $permission->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Permission 'Slide Login' assigned to Super Admin.\n";
    }

} catch (\Exception $e) {
    echo $e->getMessage();
}
