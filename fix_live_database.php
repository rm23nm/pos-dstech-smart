<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "--- Memulai Perbaikan Database Live ---\n";

// 1. Fix Missing Column 'KelompokLampu' in member_packages
echo "1. Memeriksa kolom KelompokLampu di tabel member_packages...\n";
if (Schema::hasTable('member_packages')) {
    if (!Schema::hasColumn('member_packages', 'KelompokLampu')) {
        Schema::table('member_packages', function (Blueprint $table) {
            $table->string('KelompokLampu', 50)->nullable()->after('TargetKategori');
        });
        echo "   [SUCCESS] Kolom KelompokLampu berhasil ditambahkan.\n";
    } else {
        echo "   [INFO] Kolom KelompokLampu sudah ada.\n";
    }
}

// 2. Fix Missing Menus (Layar Antrean & KDS)
echo "2. Memeriksa Menu Layar Antrean (Info Kitchen, Customer Display, dsb)...\n";
$menus = [
    [
        'id' => 113, 'PermissionName' => 'Info Kitchen', 'Link' => 'infokitchen', 'Icon' => 'fas fa-utensils',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.1, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 115, 'PermissionName' => 'Queue Lapangan', 'Link' => 'infokitchen', 'Icon' => 'fas fa-futbol',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.2, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 116, 'PermissionName' => 'Monitor Counter (Recall)', 'Link' => 'infokitchen', 'Icon' => 'fas fa-desktop',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.3, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 117, 'PermissionName' => 'Queue Antrian FNB', 'Link' => 'infokitchen', 'Icon' => 'fas fa-hamburger',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.4, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 118, 'PermissionName' => 'Antrian FNB Dapur', 'Link' => 'infokitchen', 'Icon' => 'fas fa-fire-alt',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.5, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 119, 'PermissionName' => 'Customer Display POS', 'Link' => 'fpenjualan/custdisplay_new', 'Icon' => 'fas fa-tablet-alt',
        'Level' => 2, 'MenuInduk' => 114, 'SubMenu' => 2, 'Order' => 26.6, 'Status' => 1, 'isSuperAdmin' => 0
    ],
    [
        'id' => 121, 'PermissionName' => 'Manajemen Gate', 'Link' => 'gatedevices', 'Icon' => 'fas fa-door-open',
        'Level' => 2, 'MenuInduk' => 1, 'SubMenu' => 0, 'Order' => 99, 'Status' => 1, 'isSuperAdmin' => 0
    ]
];

foreach ($menus as $m) {
    $exists = DB::table('permission')->where('id', $m['id'])->first();
    if (!$exists) {
        $m['created_at'] = now();
        $m['updated_at'] = now();
        DB::table('permission')->insert($m);
        echo "   [SUCCESS] Menu {$m['PermissionName']} berhasil ditambahkan.\n";
    } else {
        // Update if details are different
        DB::table('permission')->where('id', $m['id'])->update([
            'PermissionName' => $m['PermissionName'],
            'Link' => $m['Link'],
            'Icon' => $m['Icon']
        ]);
        echo "   [INFO] Menu {$m['PermissionName']} sudah ada, diupdate.\n";
    }
}

// 3. Assign display menus to all active roles
echo "3. Menetapkan hak akses Menu Layar Antrean ke Roles & Subscriptions...\n";
$roles = DB::table('roles')->get();
$menuIds = [113, 115, 116, 117, 118, 119, 121];

foreach ($roles as $role) {
    foreach ($menuIds as $mid) {
        $existsRole = DB::table('permissionrole')
            ->where('RoleID', $role->id)
            ->where('PermissionID', $mid)
            ->first();
            
        if (!$existsRole) {
            DB::table('permissionrole')->insert([
                'RoleID' => $role->id,
                'PermissionID' => $mid,
                'RecordOwnerID' => $role->RecordOwnerID,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
echo "   [SUCCESS] Hak akses selesai ditetapkan ke Roles.\n";

// Assign to subscriptions
$subs = DB::table('subscription')->where('Status', 1)->get();
foreach ($subs as $sub) {
    foreach ($menuIds as $mid) {
        $existsSub = DB::table('subscriptiondetail')
            ->where('NoTransaksi', $sub->NoTransaksi)
            ->where('PermissionID', $mid)
            ->first();
            
        if (!$existsSub) {
            DB::table('subscriptiondetail')->insert([
                'NoTransaksi' => $sub->NoTransaksi,
                'NoUrut' => 1,
                'PermissionID' => $mid,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
echo "   [SUCCESS] Hak akses selesai ditetapkan ke Subscription Aktif.\n";

echo "--- Proses Selesai ---\n";
