<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$perms = DB::table('permission')->select('id', 'PermissionName', 'Level', 'MenuInduk')->orderBy('id', 'desc')->limit(100)->get();
echo "Last 100 Permissions: " . json_encode($perms, JSON_PRETTY_PRINT) . "\n";
