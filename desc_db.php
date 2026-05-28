<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$res = DB::select("DESCRIBE fakturpenjualanheader");
echo json_encode($res, JSON_PRETTY_PRINT);
