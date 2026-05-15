<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

// Get all orders from today that are NOT status 3 (Taken)
$headers = DB::table('tableorderheader')
    ->whereDate('TglTransaksi', '2026-05-15')
    ->where('kitchen_order_status', '<', 3)
    ->get();

foreach ($headers as $h) {
    // Check remaining items (excluding Type 4)
    $remaining = DB::table('tableorderfnb')
        ->join('itemmaster', function($join) {
            $join->on('tableorderfnb.KodeItem', '=', 'itemmaster.KodeItem')
                 ->on('tableorderfnb.RecordOwnerID', '=', 'itemmaster.RecordOwnerID');
        })
        ->where('tableorderfnb.NoTransaksi', $h->NoTransaksi)
        ->where('tableorderfnb.RecordOwnerID', $h->RecordOwnerID)
        ->where('tableorderfnb.isCompleted', 0)
        ->where('itemmaster.TypeItem', '<>', 4)
        ->count();
    
    if ($remaining == 0) {
        // If all kitchen items are done, but status is not yet 2, update it.
        if ($h->kitchen_order_status != 2) {
            echo "Updating {$h->NoTransaksi} to status 2\n";
            DB::table('tableorderheader')
                ->where('NoTransaksi', $h->NoTransaksi)
                ->where('RecordOwnerID', $h->RecordOwnerID)
                ->update(['kitchen_order_status' => 2]);
        }
    } else {
        // If there are still items to do, but status is 0, update to 1 (Proses) if any item is done.
        $doneCount = DB::table('tableorderfnb')
            ->where('NoTransaksi', $h->NoTransaksi)
            ->where('isCompleted', 1)
            ->count();
        if ($doneCount > 0 && $h->kitchen_order_status == 0) {
            echo "Updating {$h->NoTransaksi} to status 1\n";
            DB::table('tableorderheader')
                ->where('NoTransaksi', $h->NoTransaksi)
                ->where('RecordOwnerID', $h->RecordOwnerID)
                ->update(['kitchen_order_status' => 1]);
        }
    }
}
