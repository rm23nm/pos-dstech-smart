<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$cols = Schema::getColumnListing('subscriptiondetail');
echo "Columns in subscriptiondetail: " . json_encode($cols, JSON_PRETTY_PRINT) . "\n";

// Ambil 3 contoh data
$samples = DB::table('subscriptiondetail')->limit(3)->get();
echo "\nSample data: " . json_encode($samples, JSON_PRETTY_PRINT) . "\n";
