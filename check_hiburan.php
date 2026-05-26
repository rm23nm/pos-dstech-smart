<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = App\Models\Company::where('JenisUsaha', 'Hiburan')->orWhere('NamaPartner', 'like', '%Hiburan%')->orWhere('KodePartner', 'like', '%HIB%')->get();
foreach($c as $item) {
    echo $item->KodePartner . ' : ' . $item->NamaPartner . ' (' . $item->JenisUsaha . ")\n";
}
