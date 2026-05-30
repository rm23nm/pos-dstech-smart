<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Queue Antrian FNB is actually the 3-column customer display for FNB
DB::table('permission')
    ->where('PermissionName', 'Queue Antrian FNB')
    ->update(['Link' => 'customerdisplay']);

// Customer Display POS is actually the custom display controller with midtrans
DB::table('permission')
    ->where('PermissionName', 'Customer Display POS')
    ->update(['Link' => 'fpenjualan/custdisplay']);

echo "Database links fixed.\n";
