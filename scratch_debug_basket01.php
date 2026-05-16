<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$orders = DB::table('tableorderheader')->where('tableid', 30)->get();
echo "Found " . count($orders) . " orders for Table ID 30 (Basket 01).\n";
foreach ($orders as $o) {
    echo "NoTrx: {$o->NoTransaksi}, DocStatus: {$o->DocumentStatus}, Start: {$o->JamMulai}\n";
}
