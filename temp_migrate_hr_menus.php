<?php

use Illuminate\Support\Facades\DB;

$induk = DB::table('permission')->where('PermissionName', 'Management Attendance')->first();

if ($induk) {
    $indukId = $induk->id;

    $cek1 = DB::table('permission')->where('PermissionName', 'Pengajuan Izin')->first();
    if (!$cek1) {
        DB::table('permission')->insert([
            'PermissionName' => 'Pengajuan Izin',
            'Link' => 'pengajuan-izin',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 3,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Menu Pengajuan Izin ditambahkan.\n";
    }

    $cek2 = DB::table('permission')->where('PermissionName', 'Approval Izin')->first();
    if (!$cek2) {
        DB::table('permission')->insert([
            'PermissionName' => 'Approval Izin',
            'Link' => 'approval-izin',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 4,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Menu Approval Izin ditambahkan.\n";
    }
} else {
    echo "Menu induk Management Attendance tidak ditemukan.\n";
}
