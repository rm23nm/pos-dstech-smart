<?php
use Illuminate\Support\Facades\DB;

$res = [];
$userroles = DB::table('userrole')->where('email', 'demobengkel@pos.dstechsmart.com')->get();
$res['userroles'] = $userroles;

// Insert for all roles assigned to this user
foreach ($userroles as $ur) {
    // DBB
    DB::table('permissionrole')->insertOrIgnore([
        'roleid' => $ur->roleid,
        'RecordOwnerID' => $ur->RecordOwnerID,
        'permissionid' => 134
    ]);
    
    // LKM
    DB::table('permissionrole')->insertOrIgnore([
        'roleid' => $ur->roleid,
        'RecordOwnerID' => $ur->RecordOwnerID,
        'permissionid' => 133
    ]);
}

echo json_encode($res, JSON_PRETTY_PRINT);
