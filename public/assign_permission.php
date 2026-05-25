<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

try {
    $permission = DB::table('permission')->where('PermissionName', 'Slide Login')->first();
    if ($permission) {
        $roles = DB::table('roles')->get();
        foreach ($roles as $r) {
            $exists = DB::table('permissionrole')->where('roleid', $r->id)->where('permissionid', $permission->id)->exists();
            if (!$exists) {
                // RecordOwnerID might be needed for permissionrole? Wait, let's check permissionrole structure
                // Let's just insert without RecordOwnerID if it allows null, or we get the RecordOwnerID from the role.
                DB::table('permissionrole')->insert([
                    'roleid' => $r->id,
                    'permissionid' => $permission->id,
                    'RecordOwnerID' => $r->RecordOwnerID ?? 'CL0001',
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
    echo "Cache cleared.";
} catch (\Exception $e) {
    echo $e->getMessage();
}
