<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$faktur = DB::table('fakturpenjualanheader')->where('NoPKB', 'PKB-20260527191136')->get();
echo "FAKTUR FOR 191136:\n";
echo json_encode($faktur, JSON_PRETTY_PRINT);
