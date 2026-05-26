<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$companies = DB::table('company')->get();
$defaultPrinter = DB::table('printer')->where('RecordOwnerID', 'CL0009')->first();

if (!$defaultPrinter) {
    echo "CL0009 printer not found!";
    exit;
}

$defaultData = (array)$defaultPrinter;
unset($defaultData['id']); 
unset($defaultData['RecordOwnerID']);

foreach($companies as $c) {
    $existing = DB::table('printer')->where('RecordOwnerID', $c->KodePartner)->first();
    if (!$existing) {
        $newData = $defaultData;
        $newData['RecordOwnerID'] = $c->KodePartner;
        DB::table('printer')->insert($newData);
        echo " -> Created printer for " . $c->KodePartner . "\n";
    } elseif (empty($existing->PrinterInterface)) {
        DB::table('printer')
            ->where('RecordOwnerID', $c->KodePartner)
            ->update(['PrinterInterface' => $defaultPrinter->PrinterInterface]);
        echo " -> Updated empty PrinterInterface for " . $c->KodePartner . "\n";
    }
}
echo "Done setting printers.\n";
