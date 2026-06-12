<?php
use Illuminate\Support\Facades\DB;

$emails = ['demotiket@pos.dstechsmart.com','demoapotek@pos.dstechsmart.com','demoresto@pos.dstechsmart.com','demogate@pos.dstechsmart.com','demolaundry@pos.dstechsmart.com'];

$roleIds = [];
foreach($emails as $e) {
    $u = DB::table('users')->where('email', $e)->first();
    if($u) {
        $roles = DB::table('userrole')->where('userid', $u->id)->get();
        foreach($roles as $r) {
            $roleIds[] = $r->roleid;
        }
    }
}
$roleIds = array_unique($roleIds);

// Get the permission IDs for HR Menus
$perms = DB::table('permission')
    ->whereIn('PermissionName', [
        'Management Attendance', 
        'Absensi Saya', 
        'Laporan Absensi',
        'Pengajuan Izin',
        'Approval Izin',
        'Dashboard Absensi',
        'Master Gaji',
        'Proses Penggajian'
    ])->pluck('id')->toArray();

$inserted = 0;
foreach($roleIds as $rId) {
    $existing = DB::table('permissionrole')->where('RoleID', $rId)->pluck('PermissionID')->toArray();
    foreach($perms as $pId) {
        if(!in_array($pId, $existing)) {
            DB::table('permissionrole')->insert([
                'RoleID' => $rId,
                'PermissionID' => $pId,
                'RecordOwnerID' => ''
            ]);
            $inserted++;
        }
    }
}

echo "Permissions injected for demo roles: " . implode(',', $roleIds) . "\n";
echo "Total injected rows: " . $inserted . "\n";
