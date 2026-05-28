<?php
use Illuminate\Support\Facades\DB;

try {
    // Check if Data Booking Bengkel already exists
    $exists = DB::table('permission')->where('PermissionName', 'Data Booking Bengkel')->first();

    if (!$exists) {
        // Insert into permission
        // Let's attach it under 'Bengkel' (Level 1) or 'Operasional'
        // Let's find a suitable parent. 'Master Data' or 'Operasional' or 'Penjualan'
        $parent = DB::table('permission')->where('PermissionName', 'Service Advisor')->first();
        if ($parent) {
            $menuInduk = $parent->MenuInduk;
            $level = $parent->Level;
        } else {
            // fallback
            $menuInduk = 0;
            $level = 2;
        }

        $id = DB::table('permission')->insertGetId([
            'PermissionName' => 'Data Booking Bengkel',
            'Link' => 'admin-booking-bengkel',
            'Icon' => 'fas fa-calendar-check',
            'Level' => $level,
            'MenuInduk' => $menuInduk,
            'SubMenu' => 0,
            'Order' => 9.5, // Just to place it somewhere
            'Status' => 1,
            'isSuperAdmin' => 0
        ]);

        // Insert to permissionrole for all roles
        $roles = DB::table('roles')->get();
        foreach ($roles as $role) {
            DB::table('permissionrole')->insert([
                'roleid' => $role->id,
                'permissionid' => $id,
                'RecordOwnerID' => $role->RecordOwnerID ?? ''
            ]);
        }

        $userRoles = DB::table('userrole')->select('roleid', 'RecordOwnerID')->distinct()->get();
        foreach ($userRoles as $ur) {
            DB::table('permissionrole')->insertOrIgnore([
                'roleid' => $ur->roleid,
                'RecordOwnerID' => $ur->RecordOwnerID,
                'permissionid' => $id
            ]);
        }

        // Insert to subscriptiondetail for packages that have the parent menu
        if ($menuInduk != 0) {
            $packages = DB::table('subscriptiondetail')->where('PermissionID', $menuInduk)->pluck('NoTransaksi');
        } else {
            // fallback: all packages
            $packages = DB::table('subscriptiondetail')->pluck('NoTransaksi')->unique();
        }

        foreach ($packages as $pkg) {
            $maxUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg)->max('NoUrut') ?? 0;
            DB::table('subscriptiondetail')->insert([
                'NoTransaksi' => $pkg,
                'PermissionID' => $id,
                'NoUrut' => $maxUrut + 1
            ]);
        }
        
        echo "Inserted Data Booking Bengkel Menu successfully.";
    } else {
        echo "Menu already exists.";
    }
} catch (\Exception $e) {
    ob_clean();
    echo "ERROR MESSAGE: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
