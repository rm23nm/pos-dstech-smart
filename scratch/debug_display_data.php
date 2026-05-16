<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$RecordOwnerID = 'Q0wwMDEw'; // From the URL in screenshot
$today = Carbon::today('Asia/Jakarta');
$now = Carbon::now('Asia/Jakarta');
$cutoff = $now->copy()->addMinutes(30);

echo "Checking orders for RecordOwnerID: $RecordOwnerID on $today\n";
echo "Now: $now, Cutoff: $cutoff\n\n";

$orders = DB::table('tableorderheader')
    ->where('RecordOwnerID', $RecordOwnerID)
    ->whereDate('TglTransaksi', $today->toDateString())
    ->get();

echo "Total orders today: " . $orders->count() . "\n";
foreach ($orders as $o) {
    echo "- NoTrx: {$o->NoTransaksi}, Status: {$o->Status}, DocStatus: {$o->DocumentStatus}, KitchenStatus: " . ($o->kitchen_order_status ?? 'NULL') . ", JamMulai: {$o->JamMulai}\n";
}

echo "\nRunning CustomerDisplayData query logic...\n";

$data = DB::table('tableorderheader')
    ->leftJoin('pelanggan', function($join) {
        $join->on('tableorderheader.KodePelanggan', '=', 'pelanggan.KodePelanggan')
             ->on('tableorderheader.RecordOwnerID', '=', 'pelanggan.RecordOwnerID');
    })
    ->leftJoin('titiklampu', function($join) {
        $join->on('tableorderheader.tableid', '=', 'titiklampu.id')
             ->on('tableorderheader.RecordOwnerID', '=', 'titiklampu.RecordOwnerID');
    })
    ->select(
        'tableorderheader.NoTransaksi',
        'tableorderheader.QueueNumber',
        DB::raw('COALESCE(tableorderheader.kitchen_order_status, 0) as status'),
        'tableorderheader.DocumentStatus',
        'tableorderheader.JamMulai'
    )
    ->where('tableorderheader.RecordOwnerID', $RecordOwnerID)
    ->whereDate('tableorderheader.TglTransaksi', $today->toDateString())
    ->where('tableorderheader.Status', '!=', 0)
    ->whereIn('tableorderheader.DocumentStatus', ['O', 'D'])
    ->get();

echo "Query found: " . $data->count() . " rows\n";
foreach ($data as $d) {
    $match = ($d->DocumentStatus == 'O' || ($d->DocumentStatus == 'D' && $d->JamMulai <= $cutoff->toTimeString()));
    echo "- NoTrx: {$d->NoTransaksi}, Status: {$d->status}, DocStatus: {$d->DocumentStatus}, JamMulai: {$d->JamMulai}, Matches Timing: " . ($match ? 'YES' : 'NO') . "\n";
}
