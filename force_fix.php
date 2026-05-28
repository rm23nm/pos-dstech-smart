<?php
use Illuminate\Support\Facades\DB;

try {
    $res = [];
    $user = DB::table('users')->where('email', 'demobengkel@pos.dstechsmart.com')->first();
    $company = DB::table('company')->where('KodePartner', $user->RecordOwnerID)->first();
    
    // Make sure Data Booking Bengkel is fully permitted for this user's role
    $roles = DB::table('userrole')->where('userid', $user->id)->get();
    
    foreach ($roles as $r) {
        // Data Booking Bengkel
        DB::table('permissionrole')->updateOrInsert(
            ['roleid' => $r->roleid, 'permissionid' => 134],
            ['RecordOwnerID' => $company->KodePartner]
        );
        // Laporan Komisi Mekanik
        DB::table('permissionrole')->updateOrInsert(
            ['roleid' => $r->roleid, 'permissionid' => 133],
            ['RecordOwnerID' => $company->KodePartner]
        );
    }
    
    // Ensure subscription has it
    DB::table('subscriptiondetail')->updateOrInsert(
        ['NoTransaksi' => $company->KodePaketLangganan, 'PermissionID' => 134],
        []
    );
    DB::table('subscriptiondetail')->updateOrInsert(
        ['NoTransaksi' => $company->KodePaketLangganan, 'PermissionID' => 133],
        []
    );
    
    echo "OK - FIXED ALL";
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}
