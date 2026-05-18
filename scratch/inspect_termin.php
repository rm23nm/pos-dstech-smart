<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$termin = DB::table('terminpembayaran')->get();
echo "All payment terms:\n";
print_r($termin);
