<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$noTransaksi = '26050000000019';
echo "Checking Faktur: $noTransaksi\n";

$header = DB::table('fakturpenjualanheader')->where('NoTransaksi', $noTransaksi)->first();
if ($header) {
    echo "Header Found: TotalPembelian = " . $header->TotalPembelian . " | TotalPembayaran = " . $header->TotalPembayaran . "\n";
} else {
    echo "Header NOT FOUND\n";
}

$details = DB::table('fakturpenjualandetail')->where('NoTransaksi', $noTransaksi)->get();
echo "Total Details: " . count($details) . "\n";
foreach ($details as $d) {
    echo "Item: " . $d->KodeItem . " | Qty: " . $d->Qty . " | HargaNet: " . $d->HargaNet . " | BaseReff: " . $d->BaseReff . "\n";
}

$trdr = DB::table('fakturpenjualandetail')->where('BaseReff', 'LIKE', 'TRDR%')->orderBy('created_at', 'desc')->limit(5)->get();
echo "\nRecent TRDR linked details:\n";
foreach ($trdr as $t) {
    echo "Faktur: " . $t->NoTransaksi . " | Item: " . $t->KodeItem . " | BaseReff: " . $t->BaseReff . "\n";
}
