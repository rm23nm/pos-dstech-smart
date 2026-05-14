<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$roid = 'CL0010';
$company = DB::table('company')->where('KodePartner', $roid)->first();
echo "AllowNegativeInventory for $roid: " . ($company->AllowNegativeInventory ?? 'N') . "\n";
echo "GudangPoS for $roid: " . ($company->GudangPoS ?? 'N/A') . "\n";

echo "\nChecking ItemWarehouses for Teh Botol (assuming code BRG01 or similar):\n";
$items = DB::table('itemmaster')->where('NamaItem', 'like', '%Teh Botol%')->get();
foreach($items as $i) {
    echo "Item: $i->NamaItem ($i->KodeItem)\n";
    $stocks = DB::table('itemwarehouses')->where('KodeItem', $i->KodeItem)->get();
    foreach($stocks as $s) {
        echo "  Gudang $s->KodeGudang: Qty $s->Qty\n";
    }
}
