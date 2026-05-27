<?php
/**
 * Create DocumentNumbering records for all companies that don't have them yet.
 * Template based on what existing companies use.
 */
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Standard document types that every company needs
// Based on existing records + SIS and PMB needed for FnB standalone
$standardDocs = [
    ['DocumentID' => 'POS',    'prefix' => 'POS', 'NumberLength' => '10'],  // POS Transaksi
    ['DocumentID' => 'POSDRF', 'prefix' => 'DRF', 'NumberLength' => '6'],   // Draft POS
    ['DocumentID' => 'SIS',    'prefix' => 'SIS', 'NumberLength' => '10'],  // Faktur FnB Standalone
    ['DocumentID' => 'PMB',    'prefix' => 'PMB', 'NumberLength' => '10'],  // Pembayaran
    ['DocumentID' => 'OUTPAY', 'prefix' => 'PMB', 'NumberLength' => '7'],   // Payment Out
    ['DocumentID' => 'GI',     'prefix' => 'GI',  'NumberLength' => '7'],   // Goods Issue
    ['DocumentID' => 'GR',     'prefix' => 'GR',  'NumberLength' => '7'],   // Goods Receipt
    ['DocumentID' => 'JE',     'prefix' => 'JM',  'NumberLength' => '5'],   // Journal Entry
    ['DocumentID' => 'CONS',   'prefix' => 'KON', 'NumberLength' => '10'],  // Konsinyasi
    ['DocumentID' => 'FPB',    'prefix' => 'FB',  'NumberLength' => '10'],  // Faktur Pembelian
    ['DocumentID' => 'PBL',    'prefix' => 'PB',  'NumberLength' => '10'],  // Pembelian
    ['DocumentID' => 'RTB',    'prefix' => 'RB',  'NumberLength' => '10'],  // Retur Barang
];

// Get all existing owners in documentnumbering
$existingOwners = DB::table('documentnumbering')->distinct()->pluck('RecordOwnerID')->toArray();
$existingOwners = array_map('trim', $existingOwners);

// Get all companies
$companies = DB::table('company')->get();

$now = Carbon::now();
$inserted = 0;
$skipped = 0;

foreach ($companies as $company) {
    $kode = trim($company->KodePartner);
    
    // Skip 999999 (full admin) and companies that already have records
    if ($kode === '999999') {
        echo "SKIP (admin): $kode\n";
        $skipped++;
        continue;
    }
    
    // Check which docs are MISSING for this company
    $existingDocs = DB::table('documentnumbering')
        ->where('RecordOwnerID', 'LIKE', $kode . '%')  // Use LIKE to handle trailing spaces
        ->pluck('DocumentID')
        ->toArray();
    
    $missingDocs = [];
    foreach ($standardDocs as $doc) {
        if (!in_array($doc['DocumentID'], $existingDocs)) {
            $missingDocs[] = $doc;
        }
    }
    
    if (empty($missingDocs)) {
        echo "OK: $kode (" . $company->NamaPartner . ") - all docs present\n";
        $skipped++;
        continue;
    }
    
    echo "ADDING for: $kode (" . $company->NamaPartner . ") - missing: " . implode(', ', array_column($missingDocs, 'DocumentID')) . "\n";
    
    foreach ($missingDocs as $doc) {
        DB::table('documentnumbering')->insert([
            'DocumentID'    => $doc['DocumentID'],
            'prefix'        => $doc['prefix'],
            'NumberLength'  => $doc['NumberLength'],
            'RecordOwnerID' => $kode,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);
        $inserted++;
    }
}

echo PHP_EOL . "=== DONE ===\n";
echo "Inserted: $inserted records\n";
echo "Skipped/Already OK: $skipped companies\n";

// Verify
echo PHP_EOL . "=== VERIFICATION - DocumentNumbering count per owner ===\n";
$counts = DB::table('documentnumbering')
    ->selectRaw('TRIM(RecordOwnerID) as owner, COUNT(*) as total')
    ->groupByRaw('TRIM(RecordOwnerID)')
    ->orderBy('owner')
    ->get();
foreach ($counts as $c) {
    echo $c->owner . ": " . $c->total . " docs\n";
}
