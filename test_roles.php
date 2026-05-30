<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = DB::table('roles')->whereIn('RecordOwnerID', ['CL0001', 'demogate'])->get();
foreach($roles as $r) {
    echo "ID: " . $r->id . " - Role: " . $r->RoleName . " - Owner: " . $r->RecordOwnerID . "\n";
}
