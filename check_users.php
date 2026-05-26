<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::select("SELECT email, RecordOwnerID FROM users");
echo "ALL USERS:\n";
foreach($users as $u) {
    echo $u->email . " - " . $u->RecordOwnerID . "\n";
}

$companies = DB::select("SELECT KodePartner, NamaPartner, KodePaketLangganan FROM company");
echo "\nALL COMPANIES:\n";
foreach($companies as $c) {
    echo $c->KodePartner . " - " . $c->NamaPartner . " - " . $c->KodePaketLangganan . "\n";
}

// And let's fix the first issue (Controller permissions showing up).
// In SubscriptionController, we need to exclude isSuperAdmin=1. 
// We can just modify the database or modify the Controller. 
// First, let's see if isSuperAdmin exists and its value.
$perms = DB::select("SELECT id, PermissionName, isSuperAdmin FROM permission WHERE PermissionName LIKE '%Controller%' OR isSuperAdmin = 1");
echo "\nPERMISSIONS SUPERADMIN:\n";
foreach($perms as $p) {
    echo $p->id . " - " . $p->PermissionName . " - " . $p->isSuperAdmin . "\n";
}
