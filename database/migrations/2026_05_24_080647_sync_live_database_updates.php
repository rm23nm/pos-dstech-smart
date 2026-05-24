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
        // 1. Insert Manajemen Gate permission if not exists
        $perm = DB::table('permission')->where('PermissionName', 'Manajemen Gate')->first();
        if (!$perm) {
            $id = DB::table('permission')->insertGetId([
                'PermissionName' => 'Manajemen Gate',
                'Link' => 'gatedevices',
                'Icon' => 'fas fa-door-open',
                'Level' => 2,
                'MenuInduk' => 1,
                'SubMenu' => 0,
                'Order' => 99,
                'Status' => 1,
                'isSuperAdmin' => 0
            ]);
            $perm = DB::table('permission')->where('id', $id)->first();
        } else {
            DB::table('permission')->where('id', $perm->id)->update(['SubMenu' => 0]);
        }

        // 2. Bind to Role 1
        if ($perm) {
            $bound = DB::table('permissionrole')->where('RoleID', 1)->where('PermissionID', $perm->id)->first();
            if (!$bound) {
                DB::table('permissionrole')->insert([
                    'RoleID' => 1,
                    'PermissionID' => $perm->id,
                    'RecordOwnerID' => ''
                ]);
            }
        }

        // 3. Inject to Subscriptions that have Sewa Billing & IoT (88)
        $packages = DB::table('subscriptiondetail')->select('NoTransaksi')->where('PermissionID', 88)->distinct()->get();
        foreach ($packages as $pkg) {
            $exists = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg->NoTransaksi)->where('PermissionID', $perm->id)->exists();
            if (!$exists) {
                // Get max NoUrut for this transaction to avoid strict mode error
                $maxUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $pkg->NoTransaksi)->max('NoUrut');
                $nextUrut = $maxUrut ? $maxUrut + 1 : 1;

                DB::table('subscriptiondetail')->insert([
                    'NoTransaksi' => $pkg->NoTransaksi,
                    'PermissionID' => $perm->id,
                    'NoUrut' => $nextUrut
                ]);
            }
        }

        // 4. Create gate_device_tickets if not exists
        if (!Schema::hasTable('gate_device_tickets')) {
            Schema::create('gate_device_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('DeviceID');
                $table->string('KodeItem');
                $table->string('RecordOwnerID');
                $table->timestamps();
            });
        }

        // 5. Add columns to tiket_masuk and pelanggan
        if (Schema::hasTable('tiket_masuk') && !Schema::hasColumn('tiket_masuk', 'KodeItem')) {
            Schema::table('tiket_masuk', function (Blueprint $table) {
                $table->string('KodeItem')->nullable()->after('NoTransaksi');
            });
        }
        if (Schema::hasTable('pelanggan') && !Schema::hasColumn('pelanggan', 'KodePaketMember')) {
            Schema::table('pelanggan', function (Blueprint $table) {
                $table->string('KodePaketMember')->nullable()->after('ValidUntil');
            });
        }
        
        // 6. Fix Stored Procedure Collation
        $dbName = DB::getDatabaseName();
        DB::statement("ALTER DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        $procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = '$dbName'");
        foreach ($procedures as $proc) {
            $name = $proc->Name;
            $res = DB::select("SHOW CREATE PROCEDURE `$name`");
            $create_stmt = $res[0]->{'Create Procedure'};
            DB::statement("DROP PROCEDURE IF EXISTS `$name`");
            $create_stmt = preg_replace('/CREATE DEFINER=`[^`]+`@`[^`]+` PROCEDURE/', 'CREATE PROCEDURE', $create_stmt);
            DB::statement($create_stmt);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No down logic required for sync
    }
};
