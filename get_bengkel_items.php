<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$result = Illuminate\Support\Facades\DB::select("SELECT KodeItem, NamaItem, TypeItem FROM itemmaster WHERE RecordOwnerID = 'DEMO-BENGKEL-001'");
print_r($result);
