<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = DB::select('SHOW TABLES');
foreach($tables as $t) {
    $val = array_values((array)$t)[0];
    if (stripos($val, 'item') !== false || stripos($val, 'produk') !== false || stripos($val, 'faktur') !== false) {
        echo $val . "\n";
    }
}
