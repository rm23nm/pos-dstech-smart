<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = DB::select('SHOW COLUMNS FROM bengkel_work_orders');
foreach($cols as $col) {
    if($col->Field == 'KodeMekanik') {
        print_r($col);
    }
}
