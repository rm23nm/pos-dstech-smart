<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $jenis = DB::table('tjenisusaha')->get();
    echo json_encode($jenis);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
