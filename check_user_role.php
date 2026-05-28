<?php
use Illuminate\Support\Facades\DB;

$res = [];
$userrole = DB::table('userrole')->where('email', 'demobengkel@pos.dstechsmart.com')->get();
$res['userrole'] = $userrole;

foreach ($userrole as $ur) {
    $role = DB::table('roles')->where('id', $ur->roleid)->first();
    $res['roles'][] = $role;
    
    $perm134 = DB::table('permissionrole')
        ->where('roleid', $ur->roleid)
        ->where('permissionid', 134)
        ->first();
    $res['perm_134'][] = $perm134;

    $perm133 = DB::table('permissionrole')
        ->where('roleid', $ur->roleid)
        ->where('permissionid', 133)
        ->first();
    $res['perm_133'][] = $perm133;
}

echo json_encode($res, JSON_PRETTY_PRINT);
