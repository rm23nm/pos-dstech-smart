<?php
use Illuminate\Support\Facades\DB;

$induk = DB::table('permission')->where('PermissionName', 'Management Attendance')->first();

if ($induk) {
    $indukId = $induk->id;
    $role1 = DB::table('permissionrole')->where('RoleID',1)->pluck('PermissionID')->toArray();

    // Menu 1: Master Gaji
    $cek1 = DB::table('permission')->where('PermissionName', 'Master Gaji')->first();
    if (!$cek1) {
        $pId1 = DB::table('permission')->insertGetId([
            'PermissionName' => 'Master Gaji',
            'Link' => 'master-gaji',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 5,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        if(!in_array($pId1, $role1)) {
            DB::table('permissionrole')->insert(['RoleID'=>1, 'PermissionID'=>$pId1, 'RecordOwnerID'=>'']);
        }
    }

    // Menu 2: Proses Penggajian
    $cek2 = DB::table('permission')->where('PermissionName', 'Proses Penggajian')->first();
    if (!$cek2) {
        $pId2 = DB::table('permission')->insertGetId([
            'PermissionName' => 'Proses Penggajian',
            'Link' => 'proses-penggajian',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 6,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        if(!in_array($pId2, $role1)) {
            DB::table('permissionrole')->insert(['RoleID'=>1, 'PermissionID'=>$pId2, 'RecordOwnerID'=>'']);
        }
    }
    
    echo "Menu Master Gaji dan Proses Penggajian berhasil ditambahkan.\n";
} else {
    echo "Induk menu tidak ditemukan.\n";
}
