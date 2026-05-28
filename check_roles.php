<?php
use Illuminate\Support\Facades\DB;
$kode = 'DEMO-BENGKEL-001';
$user = DB::table('users')->where('RecordOwnerID', $kode)->first();
echo "User RoleID: " . ($user->RoleID ?? 'null') . "\n";

$roles = DB::table('roles')->where('RecordOwnerID', $kode)->get();
echo "Total Roles: " . count($roles) . "\n";

if ($user && $user->RoleID) {
    $permissions = DB::table('permissionrole')->where('RoleID', $user->RoleID)->get();
    echo "Total Permissions for user role: " . count($permissions) . "\n";
} else {
    echo "No RoleID found for user\n";
}
