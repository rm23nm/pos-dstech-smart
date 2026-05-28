<?php
use Illuminate\Support\Facades\DB;

try {
    $res = [];
    $res['lap_penjualan_subs'] = DB::table('subscriptiondetail')->where('PermissionID', 50)->count();
    $res['lap_penjualan_roles'] = DB::table('permissionrole')->where('permissionid', 50)->count();
    
    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}
