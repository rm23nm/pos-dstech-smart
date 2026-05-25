<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $cols = DB::getSchemaBuilder()->getColumnListing('kelompokmeja');
    echo json_encode($cols);
} catch (\Exception $e) {
    echo $e->getMessage();
}
