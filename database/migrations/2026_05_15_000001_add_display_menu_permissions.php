<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDisplayMenuPermissions extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan menu "Display" sebagai parent Level 1 dengan submenu:
     * - Info Kitchen
     * - Monitor Antrean (Queue Monitor)
     * - Monitor Counter
     */
    public function up()
    {
        // 1. Cek apakah sudah ada menu Display Level 1
        $existingDisplay = DB::table('permission')
            ->where('PermissionName', 'Display')
            ->where('Level', 1)
            ->first();

        if ($existingDisplay) {
            $displayId = $existingDisplay->id;
            echo "Menu Display sudah ada dengan ID: {$displayId}\n";
        } else {
            // Insert menu Display sebagai Level 1
            $displayId = DB::table('permission')->insertGetId([
                'PermissionName' => 'Display',
                'Link'           => '#',
                'Icon'           => 'fas fa-desktop',
                'Level'          => 1,
                'MenuInduk'      => 0,
                'SubMenu'        => 1,
                'Order'          => 26, // Antara Transaksi (18) dan Akutansi (38)
                'Status'         => 1,
                'isSuperAdmin'   => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            echo "Menu Display dibuat dengan ID: {$displayId}\n";
        }

        // 2. Pindahkan Info Kitchen (113) dari parent Penjualan (25) ke Display
        DB::table('permission')
            ->where('id', 113)
            ->update([
                'MenuInduk'  => $displayId,
                'Level'      => 2,
                'Order'      => 26.1,
                'updated_at' => now(),
            ]);
        echo "Info Kitchen (113) dipindahkan ke parent Display ({$displayId})\n";

        // 3. Tambah Monitor Antrean jika belum ada
        $existingAntrean = DB::table('permission')
            ->where('Link', 'monitorantrean')
            ->first();
        if (!$existingAntrean) {
            $antreanId = DB::table('permission')->insertGetId([
                'PermissionName' => 'Monitor Antrean',
                'Link'           => 'monitorantrean',
                'Icon'           => 'fas fa-tv',
                'Level'          => 2,
                'MenuInduk'      => $displayId,
                'SubMenu'        => 2,
                'Order'          => 26.2,
                'Status'         => 1,
                'isSuperAdmin'   => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            echo "Monitor Antrean dibuat dengan ID: {$antreanId}\n";
        } else {
            $antreanId = $existingAntrean->id;
            DB::table('permission')
                ->where('id', $antreanId)
                ->update([
                    'MenuInduk'  => $displayId,
                    'Level'      => 2,
                    'updated_at' => now(),
                ]);
            echo "Monitor Antrean sudah ada dengan ID: {$antreanId}, dipindahkan ke parent Display\n";
        }

        // 4. Tambah Monitor Counter jika belum ada
        $existingCounter = DB::table('permission')
            ->where('Link', 'monitorcounter')
            ->first();
        if (!$existingCounter) {
            $counterId = DB::table('permission')->insertGetId([
                'PermissionName' => 'Monitor Counter',
                'Link'           => 'monitorcounter',
                'Icon'           => 'fas fa-headset',
                'Level'          => 2,
                'MenuInduk'      => $displayId,
                'SubMenu'        => 2,
                'Order'          => 26.3,
                'Status'         => 1,
                'isSuperAdmin'   => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            echo "Monitor Counter dibuat dengan ID: {$counterId}\n";
        } else {
            $counterId = $existingCounter->id;
            DB::table('permission')
                ->where('id', $counterId)
                ->update([
                    'MenuInduk'  => $displayId,
                    'Level'      => 2,
                    'updated_at' => now(),
                ]);
            echo "Monitor Counter sudah ada dengan ID: {$counterId}, dipindahkan ke parent Display\n";
        }

        // 5. Pastikan semua permission ini ada di subscriptiondetail paket 2003
        $permIds = [$displayId, 113, $antreanId, $counterId];
        // Cek apakah kolom RecordOwnerID ada di subscriptiondetail
        $hasRecordOwnerID = Schema::hasColumn('subscriptiondetail', 'RecordOwnerID');
        
        foreach ($permIds as $pid) {
            $exists = DB::table('subscriptiondetail')
                ->where('NoTransaksi', '2003')
                ->where('PermissionID', $pid)
                ->exists();
            if (!$exists) {
                // Get next NoUrut
                $maxNoUrut = DB::table('subscriptiondetail')
                    ->where('NoTransaksi', '2003')
                    ->max('NoUrut') ?? -1;
                
                $data = [
                    'NoTransaksi' => '2003',
                    'NoUrut'      => $maxNoUrut + 1,
                    'PermissionID' => $pid,
                ];
                // Hanya tambah RecordOwnerID kalau kolom itu ada
                if ($hasRecordOwnerID) {
                    $data['RecordOwnerID'] = '999999';
                }
                DB::table('subscriptiondetail')->insert($data);
                echo "PermissionID {$pid} ditambahkan ke subscriptiondetail paket 2003\n";
            } else {
                echo "PermissionID {$pid} sudah ada di subscriptiondetail paket 2003\n";
            }
        }

        // 6. Tambahkan permission Display, Monitor Antrean, Monitor Counter ke semua roles
        //    yang sudah memiliki permission Info Kitchen (113)
        $rolesWithKitchen = DB::table('permissionrole')
            ->where('permissionid', 113)
            ->get();

        $newPermIds = [$displayId, $antreanId, $counterId];
        $inserted = 0;

        foreach ($rolesWithKitchen as $rp) {
            foreach ($newPermIds as $pid) {
                $exists = DB::table('permissionrole')
                    ->where('roleid', $rp->roleid)
                    ->where('permissionid', $pid)
                    ->exists();

                if (!$exists) {
                    $data = [
                        'roleid'        => $rp->roleid,
                        'permissionid'  => $pid,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                    // Hanya tambah RecordOwnerID kalau kolom itu ada
                    if (Schema::hasColumn('permissionrole', 'RecordOwnerID')) {
                        $data['RecordOwnerID'] = $rp->RecordOwnerID;
                    }
                    DB::table('permissionrole')->insert($data);
                    $inserted++;
                }
            }
        }
        echo "{$inserted} permissionrole entries ditambahkan untuk menu Display\n";

        echo "\n=== SELESAI: Menu Display dan submenu berhasil dikonfigurasi ===\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Kembalikan Info Kitchen ke parent Penjualan (25)
        DB::table('permission')
            ->where('id', 113)
            ->update([
                'MenuInduk'  => 25,
                'Level'      => 3,
                'Order'      => 28.2,
                'updated_at' => now(),
            ]);

        // Hapus Monitor Antrean dan Monitor Counter
        DB::table('permission')
            ->whereIn('Link', ['monitorantrean', 'monitorcounter'])
            ->delete();

        // Hapus Display parent
        DB::table('permission')
            ->where('PermissionName', 'Display')
            ->where('Level', 1)
            ->delete();

        echo "Rollback: Menu Display dan submenu dihapus\n";
    }
}
