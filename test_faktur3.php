<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$faktur = DB::table('fakturpenjualanheader')->where('NoPKB', 'PKB-20260527195146')->get();
echo "FAKTUR FOR 195146:\n";
echo json_encode($faktur, JSON_PRETTY_PRINT);
