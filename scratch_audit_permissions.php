<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

// Cek semua permission yang ada di lokal
$allPerms = DB::table('permission')
    ->select('id', 'PermissionName', 'Link', 'Level', 'MenuInduk', 'Order', 'Status', 'isSuperAdmin')
    ->orderBy('Level')
    ->orderBy('Order')
    ->get();

echo "=== SEMUA PERMISSION LOKAL ===\n";
foreach ($allPerms as $p) {
    echo "ID: {$p->id} | Level: {$p->Level} | Parent: {$p->MenuInduk} | {$p->PermissionName} -> {$p->Link} | Status: {$p->Status} | SuperAdmin: {$p->isSuperAdmin}\n";
}

echo "\n=== SUBSCRIPTION DETAIL UNTUK PAKET 2003 ===\n";
$subDetails = DB::table('subscriptiondetail')->where('NoTransaksi', '2003')->get();
foreach ($subDetails as $s) {
    $perm = DB::table('permission')->where('id', $s->PermissionID)->first();
    $name = $perm ? $perm->PermissionName : 'UNKNOWN';
    echo "PermissionID: {$s->PermissionID} -> {$name}\n";
}
