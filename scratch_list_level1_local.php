<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$perms = DB::table('permission')->where('Level', 1)->get();
echo "Level 1 Menus: " . json_encode($perms, JSON_PRETTY_PRINT) . "\n";
