<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = new \Illuminate\Http\Request(['type' => 'queue']);
session(['RecordOwnerID' => 'DEMO-BENGKEL-001']);
app()->instance('request', $request);

$controller = app()->make('App\Http\Controllers\DashboardMekanikController');
$res = $controller->getData($request);

echo json_encode($res->getData(), JSON_PRETTY_PRINT);
