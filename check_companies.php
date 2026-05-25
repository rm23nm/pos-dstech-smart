<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->where('name', 'like', '%Ramadhan%')->orWhere('name', 'like', '%gor%')->get();

foreach ($users as $u) {
    echo "User: " . json_encode($u) . "\n";
    $roleId = $u->RoleID ?? $u->role_id ?? $u->role ?? null;
    
    if ($roleId) {
        $rolePerms = DB::table('permissionrole')->where('RoleID', $roleId)->whereIn('PermissionID', [113, 115, 116, 117, 118, 119])->pluck('PermissionID')->toArray();
        echo "  Display Permissions: " . implode(', ', $rolePerms) . "\n";
    }
}
