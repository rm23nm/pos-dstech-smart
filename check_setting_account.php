<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$companies = DB::table('company')->get();
$defaultSetting = DB::table('settingaccount')->where('RecordOwnerID', 'CL0009')->first();

if (!$defaultSetting) {
    echo "CL0009 settingaccount not found!";
    exit;
}

$defaultData = (array)$defaultSetting;
unset($defaultData['id']); // remove id if exists
unset($defaultData['RecordOwnerID']);

foreach($companies as $c) {
    $existing = DB::table('settingaccount')->where('RecordOwnerID', $c->KodePartner)->first();
    if (!$existing) {
        $newData = $defaultData;
        $newData['RecordOwnerID'] = $c->KodePartner;
        DB::table('settingaccount')->insert($newData);
        echo " -> Created settingaccount for " . $c->KodePartner . "\n";
    }
}
echo "Done setting accounts.\n";
