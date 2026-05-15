<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$data = DB::table('tableorderheader')
    ->leftJoin('tableorderfnb', 'tableorderheader.NoTransaksi', '=', 'tableorderfnb.NoTransaksi')
    ->leftJoin('itemmaster', 'tableorderfnb.KodeItem', '=', 'itemmaster.KodeItem')
    ->select('tableorderheader.NoTransaksi', 'tableorderheader.kitchen_order_status', 'itemmaster.NamaItem', 'tableorderfnb.isCompleted', 'tableorderheader.TglTransaksi')
    ->whereDate('tableorderheader.TglTransaksi', '2026-05-15')
    ->get();

echo json_encode($data, JSON_PRETTY_PRINT);
