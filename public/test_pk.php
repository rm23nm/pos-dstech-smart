<?php
require '../vendor/autoload.php';
$app = require_once '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$keys = Illuminate\Support\Facades\DB::select("SHOW KEYS FROM pelanggan WHERE Key_name = 'PRIMARY'");
print_r($keys);
