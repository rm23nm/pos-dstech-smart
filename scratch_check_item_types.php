<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$types = DB::table('itemmaster')
    ->join('tjenisitem', 'itemmaster.KodeJenis', '=', 'tjenisitem.KodeJenis')
    ->select('itemmaster.TypeItem', 'tjenisitem.NamaJenis')
    ->distinct()
    ->get();

echo json_encode($types, JSON_PRETTY_PRINT);
