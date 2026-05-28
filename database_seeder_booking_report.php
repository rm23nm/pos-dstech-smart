<?php
use Illuminate\Support\Facades\DB;

try {
    $permName = 'Laporan Booking Bengkel';
    $exists = DB::table('permission')->where('PermissionName', $permName)->first();
    
    // Lap Penjualan is ID 50
    $menuInduk = 50;
    
    $id = $exists->id;
    
    // Add to subscriptiondetail for all companies that have 'Lap Penjualan'
    $subs = DB::table('subscriptiondetail')->where('PermissionID', $menuInduk)->get();
    foreach ($subs as $sub) {
        DB::table('subscriptiondetail')->insertOrIgnore([
            'NoTransaksi' => $sub->NoTransaksi,
            'PermissionID' => $id
        ]);
    }
    
    // Add to permissionrole for all users who have Lap Penjualan
    $roles = DB::table('permissionrole')->where('permissionid', $menuInduk)->get();
    foreach ($roles as $role) {
        DB::table('permissionrole')->insertOrIgnore([
            'roleid' => $role->roleid,
            'RecordOwnerID' => $role->RecordOwnerID,
            'permissionid' => $id
        ]);
    }
    
    echo "Permissions mapped.";
} catch (\Exception $e) {
    echo $e->getMessage();
}
