<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

// Cek permission 113, 114, 115, 116 setelah migration
$perms = DB::table('permission')
    ->whereIn('id', [113, 114, 115, 116])
    ->get();
echo "=== Status Permission Setelah Migration ===\n";
foreach ($perms as $p) {
    echo "ID: {$p->id} | {$p->PermissionName} | Level: {$p->Level} | Parent: {$p->MenuInduk} | Link: {$p->Link}\n";
}

// Cek semua roles yang ada
$roles = DB::table('roles')->get();
echo "\n=== Roles ===\n";
foreach ($roles as $r) {
    echo "ID: {$r->id} | {$r->NamaRole}\n";
}

// Cek permissionrole untuk permission baru
echo "\n=== PermissionRole untuk 113, 114, 115, 116 ===\n";
$pr = DB::table('permissionrole')->whereIn('permissionid', [113, 114, 115, 116])->get();
foreach ($pr as $p) {
    echo "RoleID: {$p->roleid} | PermissionID: {$p->permissionid}\n";
}

// Cek user test
$users = DB::table('users')->limit(5)->get();
echo "\n=== Users ===\n";
foreach ($users as $u) {
    echo "ID: {$u->id} | {$u->email} | RecordOwnerID: {$u->RecordOwnerID}\n";
}

// Cek userrole untuk user pertama
$userRole = DB::table('userrole')->first();
echo "\n=== First UserRole: " . json_encode($userRole) . " ===\n";
