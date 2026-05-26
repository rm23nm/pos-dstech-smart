<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$owners = ['demoapotek', 'DEMOGATE', 'DEMOTIKET'];

$tables = [
    'company' => 'KodePartner',
    'users' => 'RecordOwnerID',
    'settingaccount' => 'RecordOwnerID',
    'printer' => 'RecordOwnerID',
    'metodepembayaran' => 'RecordOwnerID',
    'satuan' => 'RecordOwnerID',
    'merk' => 'RecordOwnerID',
    'jenisitem' => 'RecordOwnerID',
    'gudang' => 'RecordOwnerID',
    'supplier' => 'RecordOwnerID',
    'itemmaster' => 'RecordOwnerID',
    'itemwarehouses' => 'RecordOwnerID',
];

$export = [];

foreach ($tables as $table => $ownerField) {
    $rows = DB::table($table)->whereIn($ownerField, $owners)->get();
    $export[$table] = $rows;
}

file_put_contents('demo_data.json', json_encode($export, JSON_PRETTY_PRINT));
echo "Dumped to demo_data.json\n";
