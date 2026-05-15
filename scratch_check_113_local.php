<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$perm = DB::table('permission')->where('id', 113)->first();
echo "Permission 113: " . json_encode($perm, JSON_PRETTY_PRINT) . "\n";
