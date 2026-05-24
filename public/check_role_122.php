<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$permissionrole = \Illuminate\Support\Facades\DB::table('permissionrole')
    ->where('permissionid', 122)
    ->get();

echo "Assigned to roles:\n";
echo json_encode($permissionrole, JSON_PRETTY_PRINT);
