<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== COMPANY ===\n";
$company = DB::table('company')->where('KodePartner', 'demoapotek')->first();
print_r($company);

echo "=== PRINTER ===\n";
$printers = DB::table('printer')->get();
foreach ($printers as $p) {
    echo "ID: {$p->id}, Name: {$p->NamaPrinter}, Interface: {$p->PrinterInterface}, Owner: {$p->RecordOwnerID}\n";
}
