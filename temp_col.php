<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = DB::select('SHOW COLUMNS FROM company WHERE Field="JenisUsaha"');
$paket = DB::select('SHOW TABLES LIKE "%paket%"');
echo json_encode(['company' => $company, 'paket' => $paket]);
