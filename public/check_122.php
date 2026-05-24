<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$permission = \Illuminate\Support\Facades\DB::table('permission')
    ->where('id', 122)
    ->first();

echo json_encode($permission, JSON_PRETTY_PRINT);
