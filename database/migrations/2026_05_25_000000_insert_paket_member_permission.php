<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Insert "Paket Member" permission if not exists
        $permName = 'Paket Member';
        $perm = DB::table('permission')->where('PermissionName', $permName)->first();
        
        $permId = 122; // Using 122 to match local DB consistency if possible
        
        if (!$perm) {
            // Check if ID 122 is already taken
            $idTaken = DB::table('permission')->where('id', 122)->exists();
            if (!$idTaken) {
                DB::table('permission')->insert([
                    'id' => 122,
                    'PermissionName' => $permName,
                    'Link' => 'master/memberpackage',
                    'Icon' => '',
                    'Level' => 3,
                    'MenuInduk' => 91, // Under Paket
                    'SubMenu' => 2,
                    'Order' => 0,
                    'Status' => 1,
                    'isSuperAdmin' => 0
                ]);
            } else {
                $permId = DB::table('permission')->insertGetId([
                    'PermissionName' => $permName,
                    'Link' => 'master/memberpackage',
                    'Icon' => '',
                    'Level' => 3,
                    'MenuInduk' => 91,
                    'SubMenu' => 2,
                    'Order' => 0,
                    'Status' => 1,
                    'isSuperAdmin' => 0
                ]);
            }
            $perm = DB::table('permission')->where('id', $permId)->first();
        } else {
            $permId = $perm->id;
            // Update to make sure it's under Paket (91)
            DB::table('permission')->where('id', $permId)->update([
                'MenuInduk' => 91,
                'Level' => 3,
                'SubMenu' => 2
            ]);
        }

        // 2. Bind to Role 1 (Superadmin)
        if ($permId) {
            $bound = DB::table('permissionrole')->where('RoleID', 1)->where('PermissionID', $permId)->first();
            if (!$bound) {
                DB::table('permissionrole')->insert([
                    'RoleID' => 1,
                    'PermissionID' => $permId,
                    'RecordOwnerID' => '999999'
                ]);
            }
        }

        // 3. Inject to Subscriptions that already have "Paket" (ID 91)
        $packages = DB::table('subscriptiondetail')->select('NoTransaksi')->where('PermissionID', 91)->distinct()->get();
        foreach ($packages as $pkg) {
            $exists = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg->NoTransaksi)->where('PermissionID', $permId)->exists();
            if (!$exists) {
                $maxUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg->NoTransaksi)->max('NoUrut');
                $nextUrut = $maxUrut ? $maxUrut + 1 : 1;

                DB::table('subscriptiondetail')->insert([
                    'NoTransaksi' => $pkg->NoTransaksi,
                    'PermissionID' => $permId,
                    'NoUrut' => $nextUrut
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // To cleanly rollback
        $perm = DB::table('permission')->where('PermissionName', 'Paket Member')->first();
        if ($perm) {
            DB::table('permissionrole')->where('PermissionID', $perm->id)->delete();
            DB::table('subscriptiondetail')->where('PermissionID', $perm->id)->delete();
            DB::table('permission')->where('id', $perm->id)->delete();
        }
    }
};
