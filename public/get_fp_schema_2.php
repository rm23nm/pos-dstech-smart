<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tables = ['fakturpenjualan', 'fakturpenjualandetail'];
foreach($tables as $t) {
    echo "--- $t ---\n";
    $cols = \DB::select("DESCRIBE $t");
    foreach($cols as $c) echo $c->Field . " (" . $c->Type . ")\n";
}
