<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$owner = 'CL0010';

echo "=== Active orders (Status=1) with DocumentStatus for $owner ===\n";
$active = DB::table('tableorderheader')
    ->where('RecordOwnerID', $owner)
    ->where('Status', 1)
    ->select('NoTransaksi', 'Status', 'DocumentStatus', 'tableid', 'TglTransaksi', 'JamMulai', 'JamSelesai', 'NetTotal', 'TotalTerbayar')
    ->get();
foreach ($active as $o) {
    echo "No: {$o->NoTransaksi}\n";
    echo "  Status: {$o->Status}, DocStatus: " . ($o->DocumentStatus ?? 'NULL') . "\n";
    echo "  Table: {$o->tableid}, Tgl: {$o->TglTransaksi}\n";
    echo "  JamMulai: {$o->JamMulai}, JamSelesai: " . ($o->JamSelesai ?? 'NULL') . "\n";
    echo "  NetTotal: {$o->NetTotal}, TotalTerbayar: {$o->TotalTerbayar}\n\n";
}

echo "\n=== Checking if order 202605160004 has fakturpenjualandetail ===\n";
$details = DB::table('fakturpenjualandetail')
    ->where('RecordOwnerID', $owner)
    ->where(function($q) {
        $q->where('BaseReff', '202605160004')
          ->orWhere('NoTransaksi', '202605160004');
    })
    ->select('NoTransaksi', 'BaseReff', 'KodeItem', 'Qty')
    ->get();
foreach ($details as $d) {
    echo "NoTrx: {$d->NoTransaksi}, BaseReff: {$d->BaseReff}, Item: {$d->KodeItem}, Qty: {$d->Qty}\n";
}
echo "Count: " . count($details) . "\n";

echo "\n=== FakturHeader for BaseReff = 202605160004 ===\n";
$fh = DB::table('fakturpenjualanheader')
    ->where('RecordOwnerID', $owner)
    ->where('NoTransaksi', '202605160004')
    ->get();
foreach ($fh as $f) {
    echo "NoTrx: {$f->NoTransaksi}, Status: {$f->Status}, NoReff: {$f->NoReff}\n";
}
echo "Count: " . count($fh) . "\n";

echo "\n=== FIX: Close stale orders that cannot be linked to faktur ===\n";
echo "The 4 active orders (Status=1) for CL0010 are stale demo data.\n";
echo "Orders with table IDs 40, 42, 45 don't exist in titiklampu for CL0010.\n";
echo "We should close these stale orders.\n";
