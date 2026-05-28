<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- itemmaster ---\n";
print_r(DB::select('SHOW COLUMNS FROM itemmaster'));
echo "\n--- fakturpenjualandetail ---\n";
print_r(DB::select('SHOW COLUMNS FROM fakturpenjualandetail'));
