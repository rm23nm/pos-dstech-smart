<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sql = "
-- 1. ROLLBACK PREVIOUS CHANGES (IF THEY EXIST)
-- Drop temporary tables
DROP TABLE IF EXISTS `inventory_kendaraan`;
DROP TABLE IF EXISTS `leasing`;

-- Drop columns from fakturpenjualanheader safely
set @exist = (select count(*) from information_schema.columns where table_schema=database() and table_name='fakturpenjualanheader' and column_name='KodeLeasing');
set @sql = if(@exist>0, 'ALTER TABLE `fakturpenjualanheader` DROP COLUMN `KodeLeasing`, DROP COLUMN `UangMuka`, DROP COLUMN `Tenor`, DROP COLUMN `Cicilan`', 'select 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop columns from fakturpenjualandetail safely
set @exist = (select count(*) from information_schema.columns where table_schema=database() and table_name='fakturpenjualandetail' and column_name='NoRangka');
set @sql = if(@exist>0, 'ALTER TABLE `fakturpenjualandetail` DROP COLUMN `NoRangka`, DROP COLUMN `NamaSTNK`, DROP COLUMN `KTPSTNK`', 'select 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2. CREATE ISOLATED DEALER TABLES
CREATE TABLE IF NOT EXISTS `dealer_customers` (
  `KodePelanggan` varchar(50) NOT NULL,
  `NamaPelanggan` varchar(150) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `NoHP` varchar(50) DEFAULT NULL,
  `NIK` varchar(50) DEFAULT NULL,
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`KodePelanggan`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dealer_leasing` (
  `KodeLeasing` varchar(50) NOT NULL,
  `NamaLeasing` varchar(100) NOT NULL,
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`KodeLeasing`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dealer_inventory` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `KodeItem` varchar(255) NOT NULL COMMENT 'Relasi ke itemmaster TypeItem=7',
  `NoRangka` varchar(100) NOT NULL,
  `NoMesin` varchar(100) NOT NULL,
  `Warna` varchar(50) DEFAULT NULL,
  `Tahun` int(4) DEFAULT NULL,
  `HargaBeli` decimal(15,2) DEFAULT 0.00,
  `Status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Ready, 1: Terjual, 2: Dipesan',
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_norangka` (`NoRangka`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dealer_faktur_header` (
  `NoTransaksi` varchar(50) NOT NULL,
  `TglTransaksi` date NOT NULL,
  `KodePelanggan` varchar(50) NOT NULL,
  `TotalTransaksi` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Potongan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Pajak` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TotalNetto` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TotalPembayaran` decimal(15,2) NOT NULL DEFAULT 0.00,
  `StatusPembayaran` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Belum Lunas, 1: Lunas',
  `JenisPenjualan` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Cash, 1: Leasing/Kredit',
  `KodeLeasing` varchar(50) DEFAULT NULL,
  `UangMuka` decimal(15,2) DEFAULT 0.00,
  `Tenor` int(11) DEFAULT 0,
  `Cicilan` decimal(15,2) DEFAULT 0.00,
  `KodeSales` varchar(50) DEFAULT NULL,
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`NoTransaksi`, `RecordOwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dealer_faktur_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `NoTransaksi` varchar(50) NOT NULL,
  `KodeItem` varchar(255) NOT NULL,
  `NoRangka` varchar(100) NOT NULL,
  `Harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Diskon` decimal(15,2) NOT NULL DEFAULT 0.00,
  `SubTotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `NamaSTNK` varchar(150) DEFAULT NULL,
  `KTPSTNK` varchar(50) DEFAULT NULL,
  `AlamatSTNK` text DEFAULT NULL,
  `RecordOwnerID` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    DB::unprepared($sql);
    echo "Rollback and DB Setup completed successfully!\n";
} catch (\Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
