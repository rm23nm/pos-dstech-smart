<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$perms = DB::table('permission')
    ->where('PermissionName', 'like', '%Antrian FNB%')
    ->orWhere('PermissionName', 'like', '%Layar Antrean%')
    ->orWhere('PermissionName', 'like', '%Queue%')
    ->orWhere('PermissionName', 'like', '%Customer Display%')
    ->orWhere('PermissionName', 'like', '%Info Kitchen%')
    ->orWhere('PermissionName', 'like', '%Monitor Counter%')
    ->get();

foreach ($perms as $p) {
    echo "ID: $p->id | Name: $p->PermissionName | Status: $p->Status | L2: $p->MenuInduk \n";
}
