<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$now = Carbon::now('Asia/Jakarta');
echo "Server Time (Asia/Jakarta): " . $now->toDateTimeString() . "\n";

$roid = 2003; // GOR RecordOwnerID dari laporan sebelumnya
$basket5 = DB::table('tableorderheader')
    ->where('RecordOwnerID', $roid)
    ->where('tableid', 5)
    ->whereIn('DocumentStatus', ['O', 'D'])
    ->get();

echo "Active/Booking for Basket 5:\n";
foreach ($basket5 as $b) {
    echo "No: {$b->NoTransaksi}, StatusDoc: {$b->DocumentStatus}, JamMulai: {$b->JamMulai}, JamSelesai: {$b->JamSelesai}, Status: {$b->Status}\n";
}
