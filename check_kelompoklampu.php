<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select("SHOW COLUMNS FROM member_packages");
foreach($columns as $col) {
    if ($col->Field == 'KelompokLampu') {
        echo json_encode($col) . "\n";
    }
}
