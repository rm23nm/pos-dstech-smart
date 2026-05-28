<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sql = "
ALTER TABLE `fakturpenjualandetail` 
ADD COLUMN `NoRangka` varchar(100) NULL DEFAULT NULL AFTER `KodeItem`,
ADD COLUMN `NamaSTNK` varchar(100) NULL DEFAULT NULL AFTER `NoRangka`,
ADD COLUMN `KTPSTNK` varchar(50) NULL DEFAULT NULL AFTER `NamaSTNK`;
";

try {
    DB::unprepared($sql);
    echo "Database updated successfully for Dealer POS (Part 2)!\n";
} catch (\Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
