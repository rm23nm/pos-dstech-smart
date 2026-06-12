<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertAbsensiPermissions extends Migration
{
    public function up()
    {
        // 1. Induk HRD
        $indukId = DB::table('permission')->insertGetId([
            'PermissionName' => 'HRD / Kepegawaian',
            'Link' => '#',
            'Icon' => 'fas fa-users',
            'Level' => 1,
            'MenuInduk' => 0,
            'SubMenu' => 1,
            'Order' => 75,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Absensi Saya
        $absensiSayaId = DB::table('permission')->insertGetId([
            'PermissionName' => 'Absensi Saya',
            'Link' => 'absensi-saya',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 1,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Laporan Absensi
        $laporanId = DB::table('permission')->insertGetId([
            'PermissionName' => 'Laporan Absensi',
            'Link' => 'laporan-absensi',
            'Icon' => '',
            'Level' => 2,
            'MenuInduk' => $indukId,
            'SubMenu' => 1,
            'Order' => 2,
            'Status' => 1,
            'isSuperAdmin' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Auto assign to superadmin/admin or we can let them assign it via role settings.
        // Usually, there is a Roles/Permission assignment logic in the system.
        // Let's just insert into `permissionrole` for role_id 1 (usually Admin) or 2.
        // But to be safe, I'll just add the permissions. The admin can check the boxes in the Roles page.
    }

    public function down()
    {
        DB::table('permission')->where('PermissionName', 'HRD / Kepegawaian')->delete();
        DB::table('permission')->where('PermissionName', 'Absensi Saya')->delete();
        DB::table('permission')->where('PermissionName', 'Laporan Absensi')->delete();
    }
}
