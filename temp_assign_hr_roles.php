<?php
use Illuminate\Support\Facades\DB;

$role1 = DB::table('permissionrole')->where('RoleID',1)->pluck('PermissionID')->toArray();
$newPerms = DB::table('permission')->whereIn('PermissionName',['Pengajuan Izin','Approval Izin'])->pluck('id')->toArray();

foreach($newPerms as $p) {
    if(!in_array($p, $role1)) {
        DB::table('permissionrole')->insert(['RoleID'=>1, 'PermissionID'=>$p, 'RecordOwnerID'=>'']);
    }
}
echo "Assigned to Role 1\n";
