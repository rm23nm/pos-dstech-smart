<?php
use Illuminate\Support\Facades\DB;
use App\Models\UserRole;

$recordOwnerID = 'DEMO_COMPANY';
$email = 'demobengkel@pos.dstechsmart.com';

try {
    $res = [];
    
    $company = DB::table('company')->where('KodePartner', $recordOwnerID)->first();
    $res['company_paket'] = $company->KodePaketLangganan ?? 'null';
    
    $dbb = DB::table('permission')->where('PermissionName', 'Data Booking Bengkel')->first();
    $res['dbb_id'] = $dbb->id;
    
    $subs = DB::table('subscriptiondetail')
        ->where('PermissionID', $dbb->id)
        ->where('NoTransaksi', $company->KodePaketLangganan)
        ->first();
    $res['dbb_subs_this_company'] = $subs ? 'Yes' : 'No';
    
    $role = DB::table('userrole')
        ->join('permissionrole', 'permissionrole.roleid', '=', 'userrole.roleid')
        ->where('userrole.RecordOwnerID', $recordOwnerID)
        ->where('userrole.email', $email)
        ->where('permissionrole.permissionid', $dbb->id)
        ->first();
    $res['dbb_role_this_user'] = $role ? 'Yes' : 'No';
    
    $lkm = DB::table('permission')->where('PermissionName', 'Laporan Komisi Mekanik')->first();
    $res['lkm_id'] = $lkm->id;
    
    $subs2 = DB::table('subscriptiondetail')
        ->where('PermissionID', $lkm->id)
        ->where('NoTransaksi', $company->KodePaketLangganan)
        ->first();
    $res['lkm_subs_this_company'] = $subs2 ? 'Yes' : 'No';
    
    $role2 = DB::table('userrole')
        ->join('permissionrole', 'permissionrole.roleid', '=', 'userrole.roleid')
        ->where('userrole.RecordOwnerID', $recordOwnerID)
        ->where('userrole.email', $email)
        ->where('permissionrole.permissionid', $lkm->id)
        ->first();
    $res['lkm_role_this_user'] = $role2 ? 'Yes' : 'No';

    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}
