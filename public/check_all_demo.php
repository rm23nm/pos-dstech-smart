<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')
    ->where('email', 'like', '%demo%')
    ->orWhere('email', 'like', '%gor%')
    ->orWhere('email', 'like', '%service%')
    ->select('id', 'name', 'email', 'RecordOwnerID')
    ->get();

echo "Demo Users:\n";
echo json_encode($users, JSON_PRETTY_PRINT);
