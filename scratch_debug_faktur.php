<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$no = '26050000000016';
echo "Faktur: $no\n";
$details = DB::table('fakturpenjualandetail')->where('NoTransaksi', $no)->get();
foreach ($details as $d) {
    echo "Item: " . $d->KodeItem . " | Qty: " . $d->Qty . " | BaseReff: " . $d->BaseReff . "\n";
}

$header = DB::table('fakturpenjualanheader')->where('NoTransaksi', $no)->first();
if ($header) {
    echo "Header BaseReff (NoReff): " . $header->NoReff . "\n";
    
    // Check other items with same BaseReff
    echo "Other items with BaseReff " . $details[0]->BaseReff . ":\n";
    $others = DB::table('fakturpenjualandetail')->where('BaseReff', $details[0]->BaseReff)->get();
    foreach ($others as $o) {
        echo "NoTrans: " . $o->NoTransaksi . " | Item: " . $o->KodeItem . " | Qty: " . $o->Qty . "\n";
    }
}
