<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tables = DB::select('SHOW TABLES');
    echo json_encode($tables);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
