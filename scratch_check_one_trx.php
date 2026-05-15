<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$data = DB::table('tableorderfnb')
    ->where('NoTransaksi', '202605150008')
    ->get();

echo json_encode($data, JSON_PRETTY_PRINT);
