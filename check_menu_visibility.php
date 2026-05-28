<?php
use Illuminate\Support\Facades\DB;
use App\Models\UserRole;

try {
    $res = [];
    $res['laporan_komisi_mekanik'] = DB::table('permission')->where('PermissionName', 'Laporan Komisi Mekanik')->first();
    $res['data_booking_bengkel'] = DB::table('permission')->where('PermissionName', 'Data Booking Bengkel')->first();

    if ($res['laporan_komisi_mekanik']) {
        $res['lkm_subs'] = DB::table('subscriptiondetail')->where('PermissionID', $res['laporan_komisi_mekanik']->id)->count();
        $res['lkm_roles'] = DB::table('permissionrole')->where('permissionid', $res['laporan_komisi_mekanik']->id)->count();
    }
    
    if ($res['data_booking_bengkel']) {
        $res['dbb_subs'] = DB::table('subscriptiondetail')->where('PermissionID', $res['data_booking_bengkel']->id)->count();
        $res['dbb_roles'] = DB::table('permissionrole')->where('permissionid', $res['data_booking_bengkel']->id)->count();
    }
    
    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    ob_clean();
    echo $e->getMessage();
}

