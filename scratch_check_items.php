<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$items = DB::table('itemmaster')
    ->select('NamaItem', 'TypeItem')
    ->limit(20)
    ->get();

echo json_encode($items, JSON_PRETTY_PRINT);
