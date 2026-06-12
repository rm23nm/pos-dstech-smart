<?php
use Illuminate\Support\Facades\DB;

$induk = DB::table('permission')->where('PermissionName', 'Management Attendance')->first();

if ($induk) {
    $indukId = $induk->id;

    $cek1 = DB::table('permission')->where('PermissionName', 'Dashboard Absensi')->first();
    if (!$cek1) {
        $pId = DB::table('permission')->insertGetId([
            'PermissionName' => 'Dashboard Absensi',
            'Link' => 'dashboard-absensi',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 0,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Menu Dashboard Absensi ditambahkan.\n";
        
        $role1 = DB::table('permissionrole')->where('RoleID',1)->pluck('PermissionID')->toArray();
        if(!in_array($pId, $role1)) {
            DB::table('permissionrole')->insert(['RoleID'=>1, 'PermissionID'=>$pId, 'RecordOwnerID'=>'']);
            echo "Akses Dashboard disematkan ke SuperAdmin.\n";
        }
    } else {
        echo "Menu sudah ada.\n";
    }
}
