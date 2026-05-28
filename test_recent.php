<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$recent = DB::table('fakturpenjualanheader')->orderBy('created_at', 'DESC')->limit(10)->get();
echo "RECENT TRANSACTIONS:\n";
echo json_encode($recent, JSON_PRETTY_PRINT);
