<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$noTransaksi = '26050000000018';
echo "Checking NoTransaksi: $noTransaksi\n";

$header = DB::table('tableorderheader')->where('NoTransaksi', $noTransaksi)->first();
if ($header) {
    echo "Header Found: TotalMakanan = " . $header->TotalMakanan . " | KitchenStatus = " . $header->kitchen_order_status . "\n";
} else {
    echo "Header NOT FOUND\n";
}

$fnb = DB::table('tableorderfnb')->where('NoTransaksi', $noTransaksi)->get();
echo "Total FnB records: " . count($fnb) . "\n";
foreach ($fnb as $item) {
    echo "Item: " . $item->KodeItem . " | Qty: " . $item->Qty . " | Status: " . $item->LineStatus . "\n";
}

$faktur = DB::table('fakturpenjualandetail')->where('BaseReff', $noTransaksi)->get();
echo "Total FakturDetail records: " . count($faktur) . "\n";
foreach ($faktur as $f) {
    echo "Faktur: " . $f->NoTransaksi . " | Item: " . $f->KodeItem . " | Qty: " . $f->Qty . "\n";
}
