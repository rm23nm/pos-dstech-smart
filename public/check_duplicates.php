<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $perms = DB::table('permission')->where('MenuInduk', 100)->get(['id', 'PermissionName', 'Link', 'MenuInduk', 'SubMenu']);
    echo "Permissions under Sistem & Pengaturan:\n";
    foreach ($perms as $p) {
        echo "- ID: {$p->id} | {$p->PermissionName} | {$p->Link} | Sub: {$p->SubMenu}\n";
    }

    echo "\nCheck if any duplicates exist in permission table:\n";
    $duplicates = DB::select("SELECT PermissionName, COUNT(*) as count FROM permission GROUP BY PermissionName HAVING count > 1");
    foreach ($duplicates as $d) {
        echo "- {$d->PermissionName}: {$d->count} times\n";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
