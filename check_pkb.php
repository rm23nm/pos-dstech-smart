<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$res = DB::table('fakturpenjualanheader')->where('RecordOwnerID', '26050000000001')->select('NoTransaksi', 'NoPKB', 'Status')->get();
echo json_encode($res, JSON_PRETTY_PRINT);
