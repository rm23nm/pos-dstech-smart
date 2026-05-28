<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = DB::table('bengkel_work_orders')->where('RecordOwnerID', 'DEMO-BENGKEL-001')->get();
echo json_encode($orders, JSON_PRETTY_PRINT);
