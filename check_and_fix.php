<?php
use Illuminate\Support\Facades\DB;

try {
    $res = [];
    $company = DB::table('company')->where('KodePartner', 'DEMO-BENGKEL-001')->first();
    
    $paket = $company->KodePaketLangganan;
    
    $subs = DB::table('subscriptiondetail')->where('NoTransaksi', $paket)->get();
    
    $res['paket'] = $paket;
    $res['subs_count'] = count($subs);
    
    $res['dbb_exists'] = DB::table('subscriptiondetail')
        ->where('NoTransaksi', $paket)
        ->where('PermissionID', 134)
        ->exists();
        
    $res['lkm_exists'] = DB::table('subscriptiondetail')
        ->where('NoTransaksi', $paket)
        ->where('PermissionID', 133)
        ->exists();
        
    $res['userrole_dbb'] = DB::table('permissionrole')
        ->where('RecordOwnerID', 'DEMO-BENGKEL-001')
        ->where('permissionid', 134)
        ->exists();

    $res['userrole_lkm'] = DB::table('permissionrole')
        ->where('RecordOwnerID', 'DEMO-BENGKEL-001')
        ->where('permissionid', 133)
        ->exists();
    
    // Also inject it if missing
    if (!$res['dbb_exists']) {
        DB::table('subscriptiondetail')->insert([
            'NoTransaksi' => $paket,
            'PermissionID' => 134,
            'status' => 1
        ]);
        $res['dbb_exists'] = 'inserted';
    }
    
    if (!$res['lkm_exists']) {
        DB::table('subscriptiondetail')->insert([
            'NoTransaksi' => $paket,
            'PermissionID' => 133,
            'status' => 1
        ]);
        $res['lkm_exists'] = 'inserted';
    }
    
    if (!$res['userrole_dbb']) {
        $roles = DB::table('roles')->where('RecordOwnerID', 'DEMO-BENGKEL-001')->get();
        foreach ($roles as $role) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'RecordOwnerID' => 'DEMO-BENGKEL-001',
                'permissionid' => 134
            ]);
        }
        $res['userrole_dbb'] = 'inserted';
    }
    
    if (!$res['userrole_lkm']) {
        $roles = DB::table('roles')->where('RecordOwnerID', 'DEMO-BENGKEL-001')->get();
        foreach ($roles as $role) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'RecordOwnerID' => 'DEMO-BENGKEL-001',
                'permissionid' => 133
            ]);
        }
        $res['userrole_lkm'] = 'inserted';
    }

    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}
