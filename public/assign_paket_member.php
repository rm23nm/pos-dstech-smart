<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $roles = DB::table('roles')->get();
    foreach ($roles as $role) {
        // assign Paket Member
        $exists = DB::table('permissionrole')
            ->where('roleid', $role->id)
            ->where('permissionid', 122)
            ->exists();
        if (!$exists) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'permissionid' => 122,
                'RecordOwnerID' => isset($role->RecordOwnerID) ? $role->RecordOwnerID : ''
            ]);
        }
    }
    echo "Done assigning permission 122 to all roles.";
} catch (\Exception $e) {
    echo $e->getMessage();
}
