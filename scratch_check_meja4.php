<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$noTrans = '2605000000033';
echo "Checking tableorderfnb for $noTrans:\n";
$items = DB::table('tableorderfnb')->where('NoTransaksi', $noTrans)->get();
print_r($items);

echo "\nChecking tableorderheader for $noTrans:\n";
$header = DB::table('tableorderheader')->where('NoTransaksi', $noTrans)->first();
print_r($header);
