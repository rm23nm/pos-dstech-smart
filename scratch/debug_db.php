<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$roid = 1; // Assuming 1, but I should check what it is for the user.
// Let's just get the last few transactions.

$recent = DB::table('tableorderheader')
    ->orderBy('TglPencatatan', 'desc')
    ->limit(5)
    ->get();

echo "Current Server Time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
echo "Current Jakarta Time: " . Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s') . "\n";
echo "--------------------------------------------------\n";

foreach ($recent as $r) {
    echo "NoTransaksi: " . $r->NoTransaksi . "\n";
    echo "TableID: " . $r->tableid . "\n";
    echo "DocumentStatus: " . $r->DocumentStatus . "\n";
    echo "Status: " . $r->Status . "\n";
    echo "JamMulai: " . $r->JamMulai . "\n";
    echo "JamSelesai: " . $r->JamSelesai . "\n";
    echo "TglPencatatan: " . $r->TglPencatatan . "\n";
    echo "--------------------------------------------------\n";
}
