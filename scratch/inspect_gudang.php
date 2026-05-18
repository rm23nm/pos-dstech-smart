<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gudang = DB::table('gudang')->get();
echo "All warehouses:\n";
print_r($gudang);
