<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$companies = DB::table('company')->get();
foreach($companies as $c) {
    echo "Partner: " . $c->KodePartner . " - Paket: " . $c->KodePaketLangganan . "\n";
}
