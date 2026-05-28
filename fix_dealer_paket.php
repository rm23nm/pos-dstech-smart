<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

// Find a valid KodePaketLangganan that has subscriptiondetails
$validPaket = DB::table('subscriptionheader')
    ->join('subscriptiondetail', 'subscriptionheader.NoTransaksi', '=', 'subscriptiondetail.NoTransaksi')
    ->select('subscriptionheader.NoTransaksi')
    ->first();

if ($validPaket) {
    echo "Found valid paket: " . $validPaket->NoTransaksi . "\n";
    DB::table('company')->where('KodePartner', 'DEALER-001')->update(['KodePaketLangganan' => $validPaket->NoTransaksi]);
    echo "Updated DEALER-001 company.\n";
} else {
    echo "No valid paket found.\n";
}
