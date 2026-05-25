<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $subs = DB::table('subscriptionheader')->get();
    foreach ($subs as $sub) {
        $exists = DB::table('subscriptiondetail')
            ->where('NoTransaksi', $sub->NoTransaksi)
            ->where('PermissionID', 122)
            ->exists();
        if (!$exists) {
            $maxUrut = DB::table('subscriptiondetail')
                ->where('NoTransaksi', $sub->NoTransaksi)
                ->max('NoUrut');
            DB::table('subscriptiondetail')->insert([
                'NoTransaksi' => $sub->NoTransaksi,
                'PermissionID' => 122,
                'NoUrut' => ($maxUrut ? $maxUrut + 1 : 1)
            ]);
        }
    }
    echo "Done assigning permission 122 to all subscriptions.";
} catch (\Exception $e) {
    echo $e->getMessage();
}
