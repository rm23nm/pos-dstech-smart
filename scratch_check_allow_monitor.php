<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Check AllowMonitorAntrean di subscriptionheader ===\n";
$hasCols = Schema::getColumnListing('subscriptionheader');
echo "Columns in subscriptionheader: " . json_encode($hasCols) . "\n\n";

$subs = DB::table('subscriptionheader')->get();
echo "Subscriptions:\n";
foreach ($subs as $s) {
    $allow = isset($s->AllowMonitorAntrean) ? $s->AllowMonitorAntrean : 'N/A';
    echo "NoTransaksi: {$s->NoTransaksi} | AllowMonitorAntrean: {$allow}\n";
}

echo "\n=== Check company subscription ===\n";
$companies = DB::table('company')->select('KodePartner', 'NamaPartner', 'KodePaketLangganan')->get();
foreach ($companies as $c) {
    echo "KodePartner: {$c->KodePartner} | {$c->NamaPartner} | Paket: {$c->KodePaketLangganan}\n";
}
