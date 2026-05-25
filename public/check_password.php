<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

try {
    $exists = DB::table('permission')->where('PermissionName', 'Slide Login')->exists();
    if (!$exists) {
        $id = DB::table('permission')->insertGetId([
            'PermissionName' => 'Slide Login',
            'Link' => 'loginslide',
            'Icon' => 'bi-images',
            'Level' => 2,
            'MenuInduk' => 100,
            'SubMenu' => 2,
            'Order' => '76',
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Permission 'Slide Login' added successfully with ID $id.\n";
    } else {
        echo "Permission 'Slide Login' already exists.\n";
    }

    $permission = DB::table('permission')->where('PermissionName', 'Slide Login')->first();
    if ($permission) {
        $roles = DB::table('roles')->get();
        foreach ($roles as $r) {
            $exists = DB::table('rolepermission')->where('RoleID', $r->id)->where('PermissionID', $permission->id)->exists();
            if (!$exists) {
                DB::table('rolepermission')->insert([
                    'RoleID' => $r->id,
                    'PermissionID' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        echo "Permission assigned to all roles.\n";
    }

    // clear cache
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    echo "Cache cleared.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage();
}
