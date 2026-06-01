<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $paket = DB::table('tpaket')->get();
    echo json_encode($paket);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
