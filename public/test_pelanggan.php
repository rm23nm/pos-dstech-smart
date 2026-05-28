<?php
require '../vendor/autoload.php';
$app = require_once '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Pelanggan::orderBy('created_at', 'desc')->first();
print_r($p ? $p->toArray() : 'None');
