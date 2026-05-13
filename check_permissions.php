<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = DB::table('permission')->get();
foreach($permissions as $p) {
    echo "ID: {$p->id} | Name: {$p->PermissionName} | Link: {$p->Link}\n";
}
