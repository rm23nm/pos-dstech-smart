<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
try {
    DB::statement('ALTER TABLE bengkel_work_orders ADD COLUMN EstimasiWaktu INT DEFAULT 0 AFTER Keluhan');
    echo "Column added successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
