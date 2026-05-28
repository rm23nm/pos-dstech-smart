<?php
use Illuminate\Support\Facades\DB;

$permissionId = DB::table('permission')->where('PermissionName', 'Laporan Komisi Mekanik')->value('id');

if ($permissionId) {
    // Get unique roleid and RecordOwnerID pairs from userrole
    $userRoles = DB::table('userrole')
        ->select('roleid', 'RecordOwnerID')
        ->distinct()
        ->get();

    $inserted = 0;
    foreach ($userRoles as $ur) {
        $exists = DB::table('permissionrole')
            ->where('roleid', $ur->roleid)
            ->where('RecordOwnerID', $ur->RecordOwnerID)
            ->where('permissionid', $permissionId)
            ->exists();
        
        if (!$exists) {
            DB::table('permissionrole')->insert([
                'roleid' => $ur->roleid,
                'RecordOwnerID' => $ur->RecordOwnerID,
                'permissionid' => $permissionId
            ]);
            $inserted++;
        }
    }
    echo "Inserted $inserted permission roles.";
} else {
    echo "Permission not found.";
}
