<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$columns = Illuminate\Support\Facades\Schema::getColumnListing('fakturpenjualanheader');
file_put_contents('cols.txt', implode(', ', $columns));
echo "SUCCESS";
