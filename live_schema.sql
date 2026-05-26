/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(128) NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` varchar(255) NOT NULL,
  `action` varchar(32) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `before` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `after` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `method` varchar(16) DEFAULT NULL,
  `batch_id` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `RecordOwnerID` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `activity_logs_table_index` (`table`) USING BTREE,
  KEY `activity_logs_model_index` (`model`) USING BTREE,
  KEY `activity_logs_model_id_index` (`model_id`) USING BTREE,
  KEY `activity_logs_action_index` (`action`) USING BTREE,
  KEY `activity_logs_user_id_index` (`user_id`) USING BTREE,
  KEY `activity_logs_batch_id_index` (`batch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `addonmenudata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addonmenudata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Father` varchar(255) NOT NULL,
  `AddonMenuID` int(11) NOT NULL,
  `RecordOwnerID` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank` (
  `KodeBank` varchar(255) NOT NULL,
  `NamaBank` varchar(255) NOT NULL,
  `NamaPemilik` varchar(255) NOT NULL,
  `CabangPembukaRekening` varchar(255) NOT NULL,
  `NoRekeningBank` varchar(255) NOT NULL,
  `RecordOwnerID` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`KodeBank`,`RecordOwnerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `biayadetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biayadetail` (
  `NoTransaksi` varchar(255) NOT NULL,
  `NoUrut` int(11) NOT NULL,
  `KodeRekening` varchar(255) NOT NULL,
  `TotalTransaksi` double NOT NULL,
  `NoReff` varchar(255) DEFAULT NULL,
  `Keterangan` varchar(255) DEFAULT NULL,
  `LineStatus` varchar(255) NOT NULL,
  `CreatedBy` varchar(255) DEFAULT NULL,
  `UpdatedBy` varchar(255) DEFAULT NULL,
  `RecordOwnerID` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `biayaheader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biayaheader` (
  `NoTransaksi` varchar(255) NOT NULL,
  `Periode` varchar(255) NOT NULL,
  `TglTransaksi` date NOT NULL,
  `NoReff` varchar(255) DEFAULT NULL,
  `Keterangan` varchar(255) DEFAULT NULL,
  `TotalTransaksi` double NOT NULL,
  `KodeRekening` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Posted` int(11) NOT NULL,
  `CreatedBy` varchar(255) DEFAULT NULL,
  `UpdatedBy` varchar(255) DEFAULT NULL,
  `RecordOwnerID` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`NoTransaksi`,`RecordOwnerID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bookingtableonline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookingtableonline` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `NoTransaksi` varchar(55) NOT NULL,
  `TglBooking` datetime NOT NULL,
  `JamMulai` time NOT NULL,
  `JamSelesai` time NOT NULL,
  `mejaID` bigint(20) unsigned NOT NULL,
  `paketid` bigint(20) unsigned NOT NULL,
  `KodeSales` varchar(55) NOT NULL,
  `KodePelanggan` varchar(55) NOT NULL,
  `StatusTransaksi` tinyint(4) NOT NULL DEFAULT 0,
  `Keterangan` varchar(254) DEFAULT NULL,
  `ExtraRequest` varchar(254) DEFAULT NULL,
  `TotalTransaksi` double NOT NULL,
  `TotalTax` double NOT NULL DEFAULT 0,
  `TotalDiskon` double NOT NULL DEFAULT 0,
  `TotalLainLain` double NOT NULL DEFAULT 0,
  `NetTotal` double NOT NULL,
  `RecordOwnerID` varchar(254) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bookingtableonline_notransaksi_unique` (`NoTransaksi`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `KodePartner` varchar(255) NOT NULL,
  `NamaPartner` varchar(255) NOT NULL,
  `CustomDomain` varchar(255) DEFAULT NULL,
  `AlamatTagihan` varchar(255) NOT NULL,
  `NoTlp` varchar(255) NOT NULL,
  `NoHP` varchar(255) NOT NULL,
  `NIKPIC` varchar(255) NOT NULL,
  `NamaPIC` varchar(255) NOT NULL,
  `tempStore` varchar(255) NOT NULL,
  `icon` longtext DEFAULT NULL,
  `StartSubs` date DEFAULT NULL,
  `EndSubs` date DEFAULT NULL,
  `ExtraDays` int(11) DEFAULT NULL,
  `NPWP` varchar(255) DEFAULT NULL,
  `TglPKP` date DEFAULT NULL,
  `PPN` double DEFAULT NULL,
  `KonversiRupiahKePoin` double NOT NULL DEFAULT 10000 COMMENT 'Setiap belanja nominal ini dapat 1 Poin',
  `NilaiTukarPoin` double NOT NULL DEFAULT 100 COMMENT '1 Poin bernilai diskon sebesar nominal ini',
  `isHargaJualIncludePPN` int(11) DEFAULT NULL,
  `isPostingAkutansi` int(11) DEFAULT NULL,
  `NamaPosPrinter` varchar(255) DEFAULT NULL,
  `PosTemplate` varchar(255) DEFAULT 'NormalPoS',
  `FooterNota` varchar(255) DEFAULT NULL,
  `LebarKertas` varchar(255) DEFAULT NULL,
  `JenisUsaha` varchar(255) DEFAULT NULL,
  `GudangPoS` varchar(255) DEFAULT NULL,
  `TerminBayarPoS` varchar(255) DEFAULT NULL,
  `AllowNegativeInventory` varchar(255) DEFAULT NULL,
  `DefaultSlip` varchar(255) DEFAULT NULL,
  `Logo` longtext DEFAULT NULL,
  `Banner1` longtext DEFAULT NULL,
  `Banner2` longtext DEFAULT NULL,
  `Banner3` longtext DEFAULT NULL,
  `BannerHeader1` longtext DEFAULT NULL,
  `BannerHeader2` longtext DEFAULT NULL,
  `BannerHeader3` longtext DEFAULT NULL,
  `BannerText1` longtext DEFAULT NULL,
  `BannerText2` longtext DEFAULT NULL,
  `BannerText3` longtext DEFAULT NULL,
  `ShowLinkInReciept` int(6) unsigned zerofill DEFAULT NULL,
  `KodePaketLangganan` varchar(255) DEFAULT NULL,
  `isSuspended` int(1) unsigned zerofill DEFAULT 0,
  `SuspendReason` varchar(255) DEFAULT NULL,
  `ProvID` int(11) DEFAULT NULL,
  `KotaID` int(11) DEFAULT NULL,
  `KelID` int(11) DEFAULT NULL,
  `KecID` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isActive` int(11) DEFAULT 0,
  `isInitialSetting` int(11) DEFAULT 1,
  `ImageCustDisplay1` longtext DEFAULT NULL,
  `ImageCustDisplay2` longtext DEFAULT NULL,
  `ImageCustDisplay3` longtext DEFAULT NULL,
  `ImageCustDisplay4` longtext DEFAULT NULL,
  `ImageCustDisplay5` longtext DEFAULT NULL,
  `PromoDsiplay` longtext DEFAULT NULL,
  `RunningText` varchar(255) DEFAULT NULL,
  `PajakHiburan` double(16,2) DEFAULT 0.00,
  `WarningTimer` int(11) DEFAULT NULL,
  `ItemHiburan` varchar(255) DEFAULT NULL,
  `BannerBooking` longtext DEFAULT NULL,
  `HeadlineBanner` longtext DEFAULT NULL,
  `SubHeadlineBanner` longtext DEFAULT NULL,
  `JamAwalBooking` time DEFAULT NULL,
  `JamAkhirBooking` time DEFAULT NULL,
  `TermAndCondition` longtext DEFAULT NULL,
  `AboutUs` longtext DEFAULT NULL,
  `ImageGallery1` longtext DEFAULT NULL,
  `ImageGallery2` longtext DEFAULT NULL,
  `ImageGallery3` longtext DEFAULT NULL,
  `ImageGallery4` longtext DEFAULT NULL,
  `ImageGallery5` longtext DEFAULT NULL,
  `ImageGallery6` longtext DEFAULT NULL,
  `ImageGallery7` longtext DEFAULT NULL,
  `ImageGallery8` longtext DEFAULT NULL,
  `ImageGallery9` longtext DEFAULT NULL,
  `ImageGallery10` longtext DEFAULT NULL,
  `ImageGallery11` longtext DEFAULT NULL,
  `ImageGallery12` longtext DEFAULT NULL,
  `ReminderSended` int(1) DEFAULT 0,
  `VideoCustomerDisplay1` longtext DEFAULT NULL,
  `VideoCustomerDisplay2` longtext DEFAULT NULL,
  `VideoCustomerDisplay3` longtext DEFAULT NULL,
  `VideoCustomerDisplay4` longtext DEFAULT NULL,
  `VideoCustomerDisplay5` longtext DEFAULT NULL,
  `orderSlip` varchar(255) DEFAULT NULL,
  `InventorySlip` varchar(255) DEFAULT NULL,
  `FakturSlip` varchar(255) DEFAULT NULL,
  `PaymentSlip` varchar(255) DEFAULT NULL,
  `ReturSlip` varchar(255) DEFAULT NULL,
  `TermAndConditionBookingOnline` longtext DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `DefaultLandingPageColor` varchar(255) DEFAULT NULL,
  `DefaultLandingPages` varchar(255) DEFAULT NULL,
  `TypeBackgraund` varchar(255) DEFAULT NULL,
  `Backgraund` longtext DEFAULT NULL,
  `RunningTextSelfServices` longtext DEFAULT NULL,
  `QueueDesignSetting` longtext DEFAULT NULL,
  `JenisLangganan` longtext DEFAULT NULL,
  `ShowMetodePembayaran` varchar(2) DEFAULT '00',
  `MaximalUser` int(255) DEFAULT 1,
  `TypeKitchenBackgraund` varchar(50) NOT NULL,
  `KitchenBackgraund` longtext NOT NULL,
  UNIQUE KEY `company_kodepartner_unique` (`KodePartner`) USING BTREE,
  KEY `company_customdomain_index` (`CustomDomain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Company_SetDefaultPrinter` AFTER UPDATE ON `company` FOR EACH ROW BEGIN
	UPDATE printer set Used = 0 WHERE RecordOwnerID = NEW.KodePartner;
	UPDATE printer set Used = 1 WHERE DeviceAddress = NEW.NamaPosPrinter AND RecordOwnerID = NEW.KodePartner;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_update_user` AFTER UPDATE ON `company` FOR EACH ROW BEGIN
	UPDATE users set Active = 'Y' WHERE RecordOwnerID = NEW.KodePartner;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
DROP TABLE IF EXISTS `customer_memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_memberships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `KodePelanggan` varchar(55) NOT NULL,
  `KodePaketMember` varchar(55) NOT NULL,
  `ValidUntil` datetime DEFAULT NULL,
  `MaxPlay` int(11) NOT NULL DEFAULT 0,
  `Played` int(11) NOT NULL DEFAULT 0,
  `maxTimePerPlay` int(11) NOT NULL DEFAULT 0,
  `RecordOwnerID` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `deliverynotedetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverynotedetail` (
  `NoTransaksi` varchar(255) NOT NULL,
  `BaseReff` varchar(255) NOT NULL,
  `BaseLine` int(11) NOT NULL,
  `BaseType` varchar(255) NOT NULL,
  `NoUrut` int(11) NOT NULL,
  `KodeItem` varchar(255) NOT NULL,
  `Qty` double NOT NULL,
  `QtyKonversi` double NOT NULL,
  `QtyRetur` double(16,2) DEFAULT NULL,
  `Satuan` varchar(255) NOT NULL,
  `Harga` double NOT NULL,
  `Discount` double NOT NULL,
  `HargaNet` double NOT NULL,
  `LineStatus` varchar(255) NOT NULL,
  `KodeGudang` varchar(255) NOT NULL,
  `Keterangan` varchar(255) NOT NULL,
  `HargaPokokPenjualan` double(16,2) DEFAULT NULL,
  `VatPercent` double(16,2) DEFAULT NULL,
  `VatTotal` double(16,2) DEFAULT NULL,
  `RecordOwnerID` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Moving_History` AFTER INSERT ON `deliverynotedetail` FOR EACH ROW BEGIN
	SET @StatusTransaksi = '';
	SELECT `Status` INTO @StatusTransaksi FROM deliverynoteheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @StatusTransaksi = 'D' THEN
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'ODLN',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
	ELSE 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'ODLN',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Whs_stock` AFTER INSERT ON `deliverynotedetail` FOR EACH ROW BEGIN
	SET @RowCount = 0;
	SET @StatusTransaksi = '';
	SELECT `Status` INTO @StatusTransaksi FROM deliverynoteheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
	
	IF @StatusTransaksi = 'D' THEN
		UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID AND KodeGudang = NEW.KodeGudang;
		
	ELSE
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID AND KodeGudang = NEW.KodeGudang;
			END IF;
			
			IF @RowCount = 0 THEN 
				INSERT INTO itemwarehouses
				VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty * -1, NEW.RecordOwnerID, NOW(), NOW());
			END IF;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
ALTER DATABASE `xpos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Update_Stock` AFTER INSERT ON `deliverynotedetail` FOR EACH ROW BEGIN
	SET @OnHand = 0;
	SET @SumPrice = 0;
	SET @StatusTransaksi = '';
	SELECT `Status` INTO @StatusTransaksi FROM deliverynoteheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	SELECT Stock INTO @OnHand FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @StatusTransaksi = 'D' THEN
		UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	ELSE
		UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @sa