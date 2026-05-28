<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$no = '26050000000010';
$faktur = \DB::table('fakturpenjualandetail')->where('NoTransaksi', $no)->get();
echo "Faktur:\n";
print_r($faktur);

$pkb = \DB::table('bengkel_work_order_details')->where('NoPKB', $no)->get();
echo "\nPKB:\n";
print_r($pkb);

$header = \DB::table('fakturpenjualanheader')->where('NoTransaksi', $no)->first();
echo "\nHeader:\n";
print_r($header);
