<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$perms = DB::table('permissionrole')->where('roleid', 1)->get();
echo 'SuperAdmin (CL0001) perms count: ' . count($perms) . "\n";

$package_perms = DB::table('subscriptiondetail')->where('NoTransaksi', '2003')->get();
echo 'Package 2003 perms count: ' . count($package_perms) . "\n";
