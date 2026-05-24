<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$permissions = \Illuminate\Support\Facades\DB::table('permission')
    ->get(['id', 'PermissionName', 'MenuInduk', 'Level']);

echo json_encode($permissions, JSON_PRETTY_PRINT);
