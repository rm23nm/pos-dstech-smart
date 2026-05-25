<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->where('email', 'gor.servicepos@gmail.com')->get();

foreach ($users as $u) {
    echo "User: " . $u->email . "\n";
    $roles = DB::table('userrole')->where('userid', $u->id)->get();
    foreach($roles as $r) {
        echo "Role: " . json_encode($r) . "\n";
        $roleId = $r->roleid;
        $rolePerms = DB::table('permissionrole')->where('RoleID', $roleId)->whereIn('PermissionID', [113, 115, 116, 117, 118, 119])->pluck('PermissionID')->toArray();
        echo "  Display Permissions: " . implode(', ', $rolePerms) . "\n";
    }
}
