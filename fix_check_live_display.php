<?php
// Script untuk dijalankan di LIVE terminal
// php fix_check_live_display.php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== 1. Status AllowMonitorAntrean di subscriptionheader ===\n";
$hasCols = Schema::hasColumn('subscriptionheader', 'AllowMonitorAntrean');
echo "Kolom AllowMonitorAntrean ada: " . ($hasCols ? "YA" : "TIDAK") . "\n";

if ($hasCols) {
    $subs = DB::table('subscriptionheader')->get();
    foreach ($subs as $s) {
        echo "  Paket: {$s->NoTransaksi} | AllowMonitorAntrean: {$s->AllowMonitorAntrean}\n";
    }
}

echo "\n=== 2. Permission yang ada untuk Display/Monitor ===\n";
$perms = DB::table('permission')
    ->where(function($q) {
        $q->where('PermissionName', 'LIKE', '%Display%')
          ->orWhere('PermissionName', 'LIKE', '%Monitor%')
          ->orWhere('PermissionName', 'LIKE', '%Kitchen%')
          ->orWhere('PermissionName', 'LIKE', '%Queue%')
          ->orWhere('PermissionName', 'LIKE', '%Antrean%');
    })
    ->get();
foreach ($perms as $p) {
    echo "  ID: {$p->id} | Level: {$p->Level} | Parent: {$p->MenuInduk} | {$p->PermissionName} -> Link: {$p->Link}\n";
}

echo "\n=== 3. Routes yang tersedia (dari nama route) ===\n";
$routes = ['infokitchen', 'customerdisplay', 'countermonitor', 'monitorantrean', 'monitorcounter', 'queue-management', 'fpenjualan-custdisplay'];
foreach ($routes as $r) {
    try {
        $url = route($r);
        echo "  route('{$r}') = {$url} ✓\n";
    } catch (\Exception $e) {
        echo "  route('{$r}') = ERROR: Route tidak ada!\n";
    }
}

echo "\n=== 4. Company subscription (pakai paket apa) ===\n";
$companies = DB::table('company')->select('KodePartner', 'NamaPartner', 'KodePaketLangganan')->limit(10)->get();
foreach ($companies as $c) {
    echo "  {$c->KodePartner} | {$c->NamaPartner} | Paket: {$c->KodePaketLangganan}\n";
}
