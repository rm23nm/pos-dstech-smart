<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$defaultSetting = DB::table('settingaccount')->where('RecordOwnerID', 'CL0009')->first();
$demoSetting = DB::table('settingaccount')->where('RecordOwnerID', 'demoapotek')->first();

if ($demoSetting) {
    echo "demoapotek HAS setting account:\n";
    print_r((array)$demoSetting);
    
    // Check if it's empty
    if (empty($demoSetting->InvAcctHargaPokokPenjualan)) {
        DB::table('settingaccount')->where('RecordOwnerID', 'demoapotek')->update([
            'InvAcctHargaPokokPenjualan' => $defaultSetting->InvAcctHargaPokokPenjualan,
            'InvAcctPendapatanJual' => $defaultSetting->InvAcctPendapatanJual,
            'InvAcctPendapatanJasa' => $defaultSetting->InvAcctPendapatanJasa,
            'InvAcctPersediaan' => $defaultSetting->InvAcctPersediaan,
        ]);
        echo " -> Updated demoapotek with CL0009 values.\n";
    }
} else {
    echo "NO setting account for demoapotek.\n";
}
