<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $parent = DB::table('permission')->where('id', 74)->first();
    echo json_encode($parent, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo $e->getMessage();
}
