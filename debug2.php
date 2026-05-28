<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$no = '26050000000010';
$headers = \DB::table('fakturpenjualanheader')->where('NoTransaksi', $no)->get();
print_r($headers);
