<?php
use Illuminate\Support\Facades\DB;

// 1. Mekanik
$mekanik = DB::table('permission')->where('PermissionName', 'Mekanik')->first();
if (!$mekanik) {
    $idMekanik = DB::table('permission')->insertGetId([
        'PermissionName' => 'Mekanik',
        'Link' => 'mekanik',
        'Icon' => 'bi bi-wrench-adjustable',
        'Level' => 3,
        'MenuInduk' => 2,
        'Status' => 1,
        'SubMenu' => 0,
        'Order' => 0
    ]);
} else {
    DB::table('permission')->where('id', $mekanik->id)->update([
        'MenuInduk' => 2,
        'Level' => 3,
        'Icon' => 'bi bi-wrench-adjustable'
    ]);
    $idMekanik = $mekanik->id;
}

// 2. Antrian Bengkel
$antrian = DB::table('permission')->where('PermissionName', 'Antrian Bengkel')->first();
if (!$antrian) {
    $idAntrian = DB::table('permission')->insertGetId([
        'PermissionName' => 'Antrian Bengkel',
        'Link' => 'queuebengkel-redirect',
        'Icon' => 'bi bi-display',
        'Level' => 2,
        'MenuInduk' => 114,
        'Status' => 1,
        'SubMenu' => 0,
        'Order' => 0
    ]);
} else {
    $idAntrian = $antrian->id;
}

// 3. Management Bengkel
$mb = DB::table('permission')->where('PermissionName', 'Management Bengkel')->first();
if (!$mb) {
    $idMB = DB::table('permission')->insertGetId([
        'PermissionName' => 'Management Bengkel',
        'Link' => '#',
        'Icon' => 'bi bi-car-front',
        'Level' => 1,
        'MenuInduk' => 0,
        'Status' => 1,
        'SubMenu' => 1,
        'Order' => 0
    ]);
} else {
    $idMB = $mb->id;
}

// 4. Riwayat Servis
$rs = DB::table('permission')->where('PermissionName', 'Riwayat Servis')->first();
if (!$rs) {
    $idRS = DB::table('permission')->insertGetId([
        'PermissionName' => 'Riwayat Servis',
        'Link' => 'riwayat-servis',
        'Icon' => 'bi bi-card-list',
        'Level' => 2,
        'MenuInduk' => $idMB,
        'Status' => 1,
        'SubMenu' => 0,
        'Order' => 0
    ]);
} else {
    $idRS = $rs->id;
}

// Grant permissions to role 1
$perms = [$idMekanik, $idAntrian, $idMB, $idRS];
foreach($perms as $pid) {
    DB::table('permissionrole')->updateOrInsert([
        'RoleID' => 1, 
        'PermissionID' => $pid
    ], [
        'RecordOwnerID' => ''
    ]);
}

// Grant permissions to DEMO-BENGKEL-001 (company)
// The NoTransaksi used is SUB-DEMO-BENGKEL-001
$subNo = 'SUB-DEMO-BENGKEL-001';
$idx = 100; // start from high index to avoid duplicate if possible
foreach($perms as $pid) {
    DB::table('subscriptiondetail')->updateOrInsert([
        'NoTransaksi' => $subNo,
        'PermissionID' => $pid
    ], [
        'NoUrut' => $idx++
    ]);
}
echo "OK\n";
