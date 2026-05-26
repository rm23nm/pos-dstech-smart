<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "PKT-APOTEK-12 Details: " . DB::table('subscriptiondetail')->where('NoTransaksi','PKT-APOTEK-12')->count() . "\n";
echo "Roles demoapotek: " . DB::table('roles')->where('RecordOwnerID','demoapotek')->count() . "\n";
echo "UserRoles demoapotek: " . DB::table('userrole')->where('RecordOwnerID','demoapotek')->count() . "\n";
echo "PKT-APOTEK-1 Details: " . DB::table('subscriptiondetail')->where('NoTransaksi','PKT-APOTEK-1')->count() . "\n";
