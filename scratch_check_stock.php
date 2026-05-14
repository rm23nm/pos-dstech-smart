<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$gudang = DB::table('company')->value('GudangPoS') ?? 'GDG01';
echo "Warehouse: $gudang\n";

echo "Items Stock in $gudang:\n";
$stocks = DB::table('itemmaster as i')
    ->leftJoin('itemwarehouses as w', function($join) use ($gudang) {
        $join->on('i.KodeItem', '=', 'w.KodeItem')
             ->on('i.RecordOwnerID', '=', 'w.RecordOwnerID')
             ->where('w.KodeGudang', '=', $gudang);
    })
    ->whereIn('i.TypeItem', [1,2])
    ->select('i.KodeItem', 'i.NamaItem', 'w.Qty')
    ->take(10)
    ->get();

foreach ($stocks as $s) {
    echo "Item: " . $s->KodeItem . " | Name: " . $s->NamaItem . " | Stock: " . ($s->Qty ?? 'NULL (No record!)') . "\n";
}
