<?php
use Illuminate\Support\Facades\DB;
$kode = 'DEMO-BENGKEL-001';
$role = DB::table('roles')->where('RecordOwnerID', $kode)->first();

if ($role) {
    echo "Role ID found: " . $role->id . " - " . $role->RoleName . "\n";
    DB::table('users')->where('RecordOwnerID', $kode)->update(['RoleID' => $role->id]);
    echo "Updated user with RoleID\n";

    $permissions = DB::table('permissionrole')->where('RoleID', $role->id)->get();
    echo "Total permissions: " . count($permissions) . "\n";
} else {
    echo "No roles found for RecordOwnerID: $kode\n";
}
