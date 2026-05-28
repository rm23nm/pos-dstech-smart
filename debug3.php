<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$no = '26050000000010';
$det = \DB::table('fakturpenjualandetail')->where('NoTransaksi', $no)->where('RecordOwnerID', 'DEMO-BENGKEL-001')->get();
echo "faktur detail:\n";
print_r($det);

$pkb = \DB::table('bengkel_work_order_details')->where('NoPKB', 'PKB-20260528085444')->get();
echo "\npkb detail:\n";
print_r($pkb);
