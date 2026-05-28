<?php
use Illuminate\Support\Facades\DB;

$posKasir = DB::table('permission')->where('PermissionName', 'Halaman POS Kasir')->first();
if (!$posKasir) {
    $idPOS = DB::table('permission')->insertGetId([
        'PermissionName' => 'Halaman POS Kasir',
        'Link' => 'fpenjualan/new',
        'Icon' => 'fas fa-cash-register',
        'Level' => 2,
        'MenuInduk' => 19,
        'Status' => 1,
        'SubMenu' => 0,
        'Order' => 0
    ]);
} else {
    $idPOS = $posKasir->id;
}

DB::table('permissionrole')->updateOrInsert([
    'RoleID' => 1, 
    'PermissionID' => $idPOS
], [
    'RecordOwnerID' => ''
]);

$subNo = 'SUB-DEMO-BENGKEL-001';
$maxUrut = DB::table('subscriptiondetail')->where('NoTransaksi', $subNo)->max('NoUrut') ?? 100;
DB::table('subscriptiondetail')->updateOrInsert([
    'NoTransaksi' => $subNo,
    'PermissionID' => $idPOS
], [
    'NoUrut' => $maxUrut + 1
]);

echo "OK\n";
