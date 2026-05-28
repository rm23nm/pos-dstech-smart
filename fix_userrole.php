<?php
use Illuminate\Support\Facades\DB;
$kode = 'DEMO-BENGKEL-001';

$user = DB::table('users')->where('RecordOwnerID', $kode)->first();
$role = DB::table('roles')->where('RecordOwnerID', $kode)->first();

if ($user && $role) {
    $userrole = DB::table('userrole')->where('userid', $user->id)->where('RecordOwnerID', $kode)->first();
    if (!$userrole) {
        DB::table('userrole')->insert([
            'userid' => $user->id,
            'roleid' => $role->id,
            'RecordOwnerID' => $kode
        ]);
        echo "Inserted userrole for user {$user->id} with role {$role->id}\n";
    } else {
        if ($userrole->roleid != $role->id) {
            DB::table('userrole')->where('userid', $user->id)->update(['roleid' => $role->id]);
            echo "Updated userrole for user {$user->id} with role {$role->id}\n";
        } else {
            echo "userrole already correct\n";
        }
    }

    // Verify permissions count
    $perms = DB::table('permissionrole')->where('roleid', $role->id)->count();
    echo "Total permissions for this role: $perms\n";
} else {
    echo "User or Role not found\n";
}
