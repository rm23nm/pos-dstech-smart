<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$perms = DB::table('permissionrole')->where('roleid', 1)->pluck('permissionid')->toArray();
$package_perms = DB::table('subscriptiondetail')->where('NoTransaksi', '2003')->pluck('PermissionID')->toArray();

$missing_in_superadmin = array_diff($package_perms, $perms);
$missing_in_package = array_diff($perms, $package_perms);

echo "Missing in Superadmin (These are in package but NOT assigned to superadmin):\n";
print_r($missing_in_superadmin);

echo "\nMissing in Package (Assigned to superadmin but NOT in package):\n";
print_r($missing_in_package);
