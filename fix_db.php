<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('permission')
    ->where('PermissionName', 'Queue Lapangan')
    ->update(['Link' => 'queue']);
    
DB::table('permission')
    ->where('PermissionName', 'Queue Antrian FNB')
    ->update(['Link' => 'queue']);
    
DB::table('permission')
    ->where('PermissionName', 'Monitor Counter (Recall)')
    ->update(['Link' => 'countermonitor']);
    
DB::table('permission')
    ->where('PermissionName', 'Customer Display POS')
    ->update(['Link' => 'customerdisplay']);

echo "Database fixed successfully.\n";
