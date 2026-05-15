<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDisplayMenuPermissions extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan menu "Display" sebagai parent Level 1 dengan 6 submenu:
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
            $displayId = DB::table('permission')->insertGetId([
                'PermissionName' => 'Display',
                'Link'           => '#',
                'Icon'           => 'fas fa-desktop',
                'Level'          => 1,
                'MenuInduk'      => 0,
                'SubMenu'        => 1,
                'Order'          => 26,
                'Status'         => 1,
                'isSuperAdmin'   => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            echo "Menu Display dibuat dengan ID: {$displayId}\n";
        }

        // 2. Update/Insert 6 Submenu
        $submenus = [
            [
                'id' => 113,
                'name' => 'Info Kitchen',
                'link' => 'infokitchen',
                'icon' => 'fas fa-utensils',
                'order' => 26.1
            ],
            [
                'id' => 117, // ID baru
                'name' => 'Queue Antrian FNB',
                'link' => 'customerdisplay',
                'icon' => 'fas fa-tv',
                'order' => 26.2
            ],
            [
                'id' => 116,
                'name' => 'Monitor Counter (Recall)',
                'link' => 'countermonitor',
                'icon' => 'fas fa-headset',
                'order' => 26.3
            ],
            [
                'id' => 118, // ID baru
                'name' => 'Antrian FNB Dapur',
                'link' => 'infokitchen',
                'icon' => 'fas fa-fire-alt',
                'order' => 26.4
            ],
            [
                'id' => 115,
                'name' => 'Queue Lapangan',
                'link' => 'monitorantrean', // Redirects to /queue/{id} via web.php
                'icon' => 'fas fa-list-ol',
                'order' => 26.5
            ],
            [
                'id' => 119, // ID baru
                'name' => 'Customer Display POS',
                'link' => 'fpenjualan-custdisplay',
                'icon' => 'fas fa-tablet-alt',
                'order' => 26.6
            ]
        ];

        $allPermIds = [$displayId];

        foreach ($submenus as $sub) {
            $existing = DB::table('permission')->where('id', $sub['id'])->first();
            
            $data = [
                'PermissionName' => $sub['name'],
                'Link'           => $sub['link'],
                'Icon'           => $sub['icon'],
                'Level'          => 2,
                'MenuInduk'      => $displayId,
                'SubMenu'        => 2,
                'Order'          => $sub['order'],
                'Status'         => 1,
                'isSuperAdmin'   => 0,
                'updated_at'     => now(),
            ];

            if ($existing) {
                DB::table('permission')->where('id', $sub['id'])->update($data);
                echo "Submenu '{$sub['name']}' diperbarui.\n";
            } else {
                $data['id'] = $sub['id'];
                $data['created_at'] = now();
                DB::table('permission')->insert($data);
                echo "Submenu '{$sub['name']}' dibuat.\n";
            }
            $allPermIds[] = $sub['id'];
        }

        // 3. Pastikan semua permission ini ada di subscriptiondetail paket 2003
        $hasRecordOwnerID = Schema::hasColumn('subscriptiondetail', 'RecordOwnerID');
        
        foreach ($allPermIds as $pid) {
            $exists = DB::table('subscriptiondetail')
                ->where('NoTransaksi', '2003')
                ->where('PermissionID', $pid)
                ->exists();
            if (!$exists) {
                $maxNoUrut = DB::table('subscriptiondetail')
                    ->where('NoTransaksi', '2003')
                    ->max('NoUrut') ?? -1;
                
                $data = [
                    'NoTransaksi' => '2003',
                    'NoUrut'      => $maxNoUrut + 1,
                    'PermissionID' => $pid,
                ];
                if ($hasRecordOwnerID) {
                    $data['RecordOwnerID'] = '999999';
                }
                DB::table('subscriptiondetail')->insert($data);
                echo "PermissionID {$pid} ditambahkan ke subscriptiondetail paket 2003\n";
            }
        }

        // 4. Tambahkan ke permissionrole untuk roles yang sudah punya Info Kitchen (113)
        $rolesWithKitchen = DB::table('permissionrole')
            ->where('permissionid', 113)
            ->get();

        $hasPROID = Schema::hasColumn('permissionrole', 'RecordOwnerID');
        $insertedCount = 0;

        foreach ($rolesWithKitchen as $rp) {
            foreach ($allPermIds as $pid) {
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
                    if ($hasPROID) {
                        $data['RecordOwnerID'] = $rp->RecordOwnerID;
                    }
                    DB::table('permissionrole')->insert($data);
                    $insertedCount++;
                }
            }
        }
        echo "{$insertedCount} entri permissionrole ditambahkan.\n";

        echo "\n=== SELESAI: 6 Monitor Berhasil Dikonfigurasi ===\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Cleanup logic if needed
    }
}
