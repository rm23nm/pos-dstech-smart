<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$order = DB::table('tableorderheader')->where('NoTransaksi', '202605160001')->first();
echo "NoTrx: {$order->NoTransaksi}\n";
echo "DocStatus: {$order->DocumentStatus}\n";
echo "JamMulai: {$order->JamMulai}\n";
echo "JamSelesai: {$order->JamSelesai}\n";
echo "Now (Jakarta): " . \Carbon\Carbon::now('Asia/Jakarta')->toDateTimeString() . "\n";
