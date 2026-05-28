<?php
use Illuminate\Support\Facades\DB;

$kode = 'DEMO-BENGKEL-001';

// Fix 1: Update item Active dari 1 ke 'Y' (sesuai dengan query di PoSController)
$updated = DB::table('itemmaster')
    ->where('RecordOwnerID', $kode)
    ->where('Active', 1)
    ->update(['Active' => 'Y']);
echo "Items Active updated to 'Y': " . $updated . PHP_EOL;

// Verify
$items = DB::table('itemmaster')->where('RecordOwnerID', $kode)->get();
echo "Total items: " . count($items) . PHP_EOL;
foreach ($items as $item) {
    echo "  " . $item->KodeItem . " | " . $item->NamaItem . " | Active:" . $item->Active . PHP_EOL;
}

// Fix 2: Update Pelanggan Status juga
$pelanggan = DB::table('pelanggan')->where('RecordOwnerID', $kode)->get();
echo PHP_EOL . "Pelanggan count: " . count($pelanggan) . PHP_EOL;
foreach ($pelanggan as $p) {
    echo "  " . $p->KodePelanggan . " | " . $p->NamaPelanggan . " | Status:" . $p->Status . PHP_EOL;
}

echo PHP_EOL . "DONE!" . PHP_EOL;
