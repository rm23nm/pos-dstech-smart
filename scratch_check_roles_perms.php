<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Cek kolom roles
$roleCols = Schema::getColumnListing('roles');
echo "Roles columns: " . json_encode($roleCols) . "\n";

$roles = DB::table('roles')->get();
echo "Roles: " . json_encode($roles, JSON_PRETTY_PRINT) . "\n";

// Cek permissionrole untuk permission baru
echo "\n=== PermissionRole untuk 113, 114, 115, 116 ===\n";
$pr = DB::table('permissionrole')->whereIn('permissionid', [113, 114, 115, 116])->get();
echo json_encode($pr, JSON_PRETTY_PRINT) . "\n";

// Cek permissionrole untuk satu role yang ada
$firstRole = DB::table('roles')->first();
if ($firstRole) {
    $roleid = $firstRole->id;
    echo "\nPermissions for role {$roleid}: ";
    $rolePerms = DB::table('permissionrole')->where('roleid', $roleid)->pluck('permissionid')->toArray();
    echo json_encode($rolePerms) . "\n";
}
