<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGlobalSyncMenuPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Insert permission for centralized multi-app dashboard
        DB::table('permission')->updateOrInsert(
            ['id' => 120],
            [
                'PermissionName' => 'Integrasi Multi-App',
                'Link' => 'dstechgloballedger',
                'Icon' => 'flaticon-refresh',
                'SubMenu' => '0',
                'Level' => '2',
                'MenuInduk' => '100', // SuperAdmin parent menu
                'Status' => '1',
                'isSuperAdmin' => '0',
                'Order' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 2. Bind permission 120 to SuperAdmin roles of 999999 and CL0007
        // Role 53 = SuperAdmin of 999999
        DB::table('permissionrole')->updateOrInsert(
            [
                'roleid' => 53,
                'permissionid' => 120,
                'RecordOwnerID' => '999999'
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Role 54 = SuperAdmin of CL0007 (PT. DSTECH SMART PERKASA)
        DB::table('permissionrole')->updateOrInsert(
            [
                'roleid' => 54,
                'permissionid' => 120,
                'RecordOwnerID' => 'CL0007'
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissionrole')->where('permissionid', 120)->delete();
        DB::table('permission')->where('id', 120)->delete();
    }
}
