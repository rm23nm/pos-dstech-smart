<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
$comps = DB::table('company')->select('KodePartner', 'NamaPartner', 'PosTemplate')->get();
foreach ($comps as $c) {
    echo $c->KodePartner . ' : ' . $c->PosTemplate . PHP_EOL;
}
