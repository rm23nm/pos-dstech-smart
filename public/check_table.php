<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $hasTable = \Illuminate\Support\Facades\Schema::hasTable('login_slides');
    echo $hasTable ? "Table login_slides exists" : "Table login_slides DOES NOT exist";
} catch (\Exception $e) {
    echo $e->getMessage();
}
