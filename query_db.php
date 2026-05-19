<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->get();
foreach ($users as $u) {
    echo "Email: {$u->email} | Name: {$u->name} | RecordOwnerID: {$u->RecordOwnerID}\n";
}
