<?php
/**
 * Fix stale/orphan orders for all companies
 * Close orders that:
 * 1. Have Status=1 but DocumentStatus='C' (inconsistent)
 * 2. Have Status=1, DocumentStatus='O', JamSelesai=NULL, NetTotal=0, TglTransaksi old (>7 days)
 * 3. Have Status=1 and tableid that doesn't exist in titiklampu for that company
 */
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$now = Carbon::now('Asia/Jakarta');
$cutoffDate = $now->copy()->subDays(7)->toDateTimeString();

echo "=== Fixing Stale/Inconsistent Orders ===\n";
echo "Cutoff date: $cutoffDate\n\n";

// 1. Fix: Status=1 but DocStatus='C' (closed but still marked active)
$fixed1 = DB::table('tableorderheader')
    ->where('Status', 1)
    ->where('DocumentStatus', 'C')
    ->update(['Status' => 99]);  // Mark as checkout-done
echo "Fixed (Status=1, DocStatus=C): $fixed1 records → set to Status=99\n";

// 2. Fix: Status=1, DocStatus='O', JamSelesai=NULL, NetTotal=0, old orders (> 7 days)
$staleOrders = DB::table('tableorderheader')
    ->where('Status', 1)
    ->where('DocumentStatus', 'O')
    ->whereNull('JamSelesai')
    ->where(function($q) {
        $q->where('NetTotal', 0)->orWhereNull('NetTotal');
    })
    ->where('TglTransaksi', '<', substr($cutoffDate, 0, 10))
    ->select('NoTransaksi', 'RecordOwnerID', 'tableid', 'TglTransaksi')
    ->get();

echo "\nStale orders to close (No JamSelesai, NetTotal=0, old):\n";
$fixed2 = 0;
foreach ($staleOrders as $o) {
    echo "  Closing: {$o->NoTransaksi} (Owner: {$o->RecordOwnerID}, Table: {$o->tableid}, Tgl: {$o->TglTransaksi})\n";
    
    // Close the order
    DB::table('tableorderheader')
        ->where('NoTransaksi', $o->NoTransaksi)
        ->where('RecordOwnerID', $o->RecordOwnerID)
        ->update(['Status' => 0, 'DocumentStatus' => 'C']);
    
    // Also turn off the table if it's on
    DB::table('titiklampu')
        ->where('id', $o->tableid)
        ->where('RecordOwnerID', $o->RecordOwnerID)
        ->where('Status', '!=', 0)
        ->update(['Status' => 0]);
    
    $fixed2++;
}
echo "Fixed stale orders: $fixed2\n";

// 3. Fix: Turn off titiklampu that have no active orders
$stuckLights = DB::table('titiklampu')
    ->whereIn('Status', [1, 99, -1])
    ->whereNotExists(function($query) {
        $query->select(DB::raw(1))
            ->from('tableorderheader')
            ->whereColumn('tableorderheader.tableid', 'titiklampu.id')
            ->whereColumn('tableorderheader.RecordOwnerID', 'titiklampu.RecordOwnerID')
            ->where('tableorderheader.DocumentStatus', 'O');
    })
    ->select('id', 'RecordOwnerID', 'NamaTitikLampu', 'Status')
    ->get();

echo "\nStuck lights to turn off:\n";
$fixed3 = 0;
foreach ($stuckLights as $l) {
    echo "  Turning off: ID={$l->id} ({$l->NamaTitikLampu}, Owner: {$l->RecordOwnerID}, Status: {$l->Status})\n";
    DB::table('titiklampu')
        ->where('id', $l->id)
        ->where('RecordOwnerID', $l->RecordOwnerID)
        ->update(['Status' => 0]);
    $fixed3++;
}
echo "Fixed stuck lights: $fixed3\n";

echo "\n=== DONE ===\n";
echo "Total fixed: " . ($fixed1 + $fixed2 + $fixed3) . " records\n";

// Verify
echo "\n=== Verification: Active orders remaining ===\n";
$remaining = DB::table('tableorderheader')
    ->where('Status', 1)
    ->where('DocumentStatus', 'O')
    ->select('NoTransaksi', 'RecordOwnerID', 'tableid', 'TglTransaksi', 'JamSelesai', 'NetTotal')
    ->orderBy('TglTransaksi', 'desc')
    ->limit(20)
    ->get();

foreach ($remaining as $r) {
    echo "Active: {$r->NoTransaksi} (Owner: {$r->RecordOwnerID}, Table: {$r->tableid}, Tgl: {$r->TglTransaksi}, JamSelesai: " . ($r->JamSelesai ?? 'NULL') . ", NetTotal: {$r->NetTotal})\n";
}
echo "Total active: " . DB::table('tableorderheader')->where('Status', 1)->where('DocumentStatus', 'O')->count() . "\n";
