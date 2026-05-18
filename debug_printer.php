<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$c = App\Models\Company::first();
$p = App\Models\Printer::where('DeviceAddress', $c->NamaPosPrinter)->first();
echo json_encode(['NamaPosPrinter'=>$c->NamaPosPrinter, 'printer_record'=>$p]);
