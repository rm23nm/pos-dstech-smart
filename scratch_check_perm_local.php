<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$perm = DB::table('permission')->where('id', 114)->first();
echo "Permission 114: " . json_encode($perm, JSON_PRETTY_PRINT) . "\n\n";

$sub = DB::table('subscriptiondetail')->where('NoTransaksi', '2003')->where('PermissionID', 114)->get();
echo "Subscription 2003 for 114: " . json_encode($sub, JSON_PRETTY_PRINT) . "\n";
