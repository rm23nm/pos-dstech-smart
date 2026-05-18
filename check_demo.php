<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "--- itemmaster columns ---" . PHP_EOL;
print_r(Schema::getColumnListing('itemmaster'));

echo "--- menuheader columns ---" . PHP_EOL;
print_r(Schema::getColumnListing('menuheader'));
