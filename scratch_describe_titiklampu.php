<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$columns = DB::select('DESCRIBE titiklampu');
foreach ($columns as $c) {
    echo "{$c->Field} ({$c->Type})\n";
}
