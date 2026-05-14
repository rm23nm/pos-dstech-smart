<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$companies = DB::table('company')->select('KodePartner', 'NamaPartner', 'GudangPoS')->get();
foreach ($companies as $c) {
    echo "ID: " . $c->KodePartner . " | Name: " . $c->NamaPartner . " | Warehouse: " . ($c->GudangPoS ?: 'EMPTY') . "\n";
}
