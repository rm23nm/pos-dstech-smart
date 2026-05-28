<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$res = DB::table('bengkel_work_orders')->orderBy('created_at', 'DESC')->first();
print_r($res);
