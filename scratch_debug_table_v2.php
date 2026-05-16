<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "--- TABLES WITH ORDERS ---\n";
$orders = DB::table('tableorderheader')
    ->join('titiklampu', 'tableorderheader.tableid', '=', 'titiklampu.id')
    ->whereIn('tableorderheader.DocumentStatus', ['O', 'D'])
    ->select('tableorderheader.*', 'titiklampu.NamaTitikLampu')
    ->get();

foreach ($orders as $o) {
    echo "NoTrx: {$o->NoTransaksi}, Table: {$o->NamaTitikLampu} (ID: {$o->tableid}), Status: {$o->Status}, DocStatus: {$o->DocumentStatus}, Start: {$o->JamMulai}\n";
}

echo "\n--- TABLES IN BASKET GROUP ---\n";
$basketTables = DB::table('titiklampu')
    ->join('tkelompoklampu', 'titiklampu.KelompokLampu', '=', 'tkelompoklampu.KodeKelompok')
    ->where('tkelompoklampu.NamaKelompok', 'like', '%BASKET%')
    ->select('titiklampu.*')
    ->get();

foreach ($basketTables as $bt) {
    echo "ID: {$bt->id}, Name: {$bt->NamaTitikLampu}, Status: {$bt->Status}\n";
}
