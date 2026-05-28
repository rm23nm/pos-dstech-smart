<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sql = "
CREATE TABLE IF NOT EXISTS `inventory_kendaraan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `KodeItem` varchar(255) NOT NULL,
  `NoRangka` varchar(100) NOT NULL,
  `NoMesin` varchar(100) NOT NULL,
  `Warna` varchar(50) DEFAULT NULL,
  `Tahun` int(4) DEFAULT NULL,
  `HargaBeli` decimal(15,2) DEFAULT 0.00,
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Ready, 1: Terjual, 2: Booking/Indent',
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_norangka` (`NoRangka`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leasing` (
  `KodeLeasing` varchar(50) NOT NULL,
  `NamaLeasing` varchar(100) NOT NULL,
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`KodeLeasing`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `fakturpenjualanheader` 
ADD COLUMN `KodeLeasing` varchar(50) NULL DEFAULT NULL AFTER `NoPKB`,
ADD COLUMN `UangMuka` decimal(15,2) NULL DEFAULT 0.00 AFTER `KodeLeasing`,
ADD COLUMN `Tenor` int(11) NULL DEFAULT 0 AFTER `UangMuka`,
ADD COLUMN `Cicilan` decimal(15,2) NULL DEFAULT 0.00 AFTER `Tenor`;

ALTER TABLE `fakturpenjualandetail` 
ADD COLUMN `NoRangka` varchar(100) NULL DEFAULT NULL AFTER `KodeItem`,
ADD COLUMN `NamaSTNK` varchar(100) NULL DEFAULT NULL AFTER `NoRangka`,
ADD COLUMN `KTPSTNK` varchar(50) NULL DEFAULT NULL AFTER `NamaSTNK`;
";

try {
    DB::unprepared($sql);
    echo "Database updated successfully for Dealer POS!\n";
} catch (\Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
