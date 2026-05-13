-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 157.66.34.199    Database: xpos
-- ------------------------------------------------------
-- Server version	10.11.10-MariaDB-log
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
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
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Moving_History_del` AFTER DELETE ON `deliverynotedetail` FOR EACH ROW BEGIN
	SET @StatusDocument = '';
	SELECT `Status` INTO @StatusDocument FROM deliverynoteheader WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	
	IF @StatusDocument != "D"
	THEN
		DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'ODLN' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
	END IF;
	
	IF @StatusDocument = 'D'
	THEN 
		INSERT INTO  itemmovinghistory
		VALUES(OLD.KodeItem,OLD.KodeGudang,NOW(),OLD.NoTransaksi,'CODLN',OLD.Qty,0,OLD.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Whs_stock_Del` AFTER DELETE ON `deliverynotedetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_DN_Update_Stock_Del` AFTER DELETE ON `deliverynotedetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `detailjurnal_insert` AFTER INSERT ON `detailjurnal` FOR EACH ROW BEGIN
	
	
	SET @StatusTransaksi = -1;
	SELECT `StatusTransaksi` INTO @StatusTransaksi FROM `headerjurnal`
	WHERE `KodeTransaksi` = NEW.`KodeTransaksi` AND `NoTransaksi` = NEW.`NoTransaksi` AND RecordOwnerID = NEW.RecordOwnerID;
	
	SET @Periode = '' COLLATE utf8mb4_unicode_ci;
	SELECT `Periode` INTO @Periode FROM `headerjurnal`
	WHERE `KodeTransaksi` = NEW.`KodeTransaksi` AND `NoTransaksi` = NEW.`NoTransaksi` and RecordOwnerID = NEW.RecordOwnerID;
	
	
-- 	IF @StatusTransaksi = -1 THEN
-- 	   CALL sp_raise_error(9999, '[Internal] Data Header Jurnal Tidak Ditemukan');
-- 	END IF;
	SET @KodeRekening = NEW.KodeRekening;
	WHILE @KodeRekening <> "" DO
	
		
		SET @Posisi   = 0;
		SET @KodeMataUang = '';
		
		SELECT K.`Posisi` INTO @Posisi
		FROM `rekeningakutansi` R
		LEFT JOIN `kelompokrekening` K ON K.`id` = R.`KodeKelompok` AND R.RecordOwnerID = K.RecordOwnerID
		WHERE R.`KodeRekening` = @KodeRekening AND K.RecordOwnerID = NEW.RecordOwnerID LIMIT 1;
		
		-- SELECT R.`KodeMataUang` INTO @KodeMataUang 
		-- FROM `rekeningakutansi` R
		-- LEFT JOIN `kelompokrekening` K ON K.`id` = R.`KodeKelompok`
		-- WHERE R.`KodeRekening` = @KodeRekening LIMIT 1;
		
		
					
		IF (SELECT COUNT(*) FROM saldorekening WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID)=0 THEN
		    INSERT INTO saldorekening
		    (Periode, KodeRekening, SaldoAwal, MutasiDebet, MutasiKredit, SaldoAkhir, ValasSaldoAwal, ValasMutasiDebet, ValasMutasiKredit, ValasSaldoAkhir, RecordOwnerID) 
		    VALUES 
		    (@Periode, @KodeRekening, 0, 0, 0, 0, 0, 0, 0, 0, NEW.RecordOwnerID);       
		END IF;
			
		
		
		IF @Posisi = 1 THEN			   
		   IF NEW.`DK` = 1 THEN
			
			UPDATE saldorekening SET	   
			MutasiDebet = MutasiDebet + NEW.Jumlah,
			SaldoAkhir = SaldoAwal + MutasiDebet - MutasiKredit,
			ValasMutasiDebet = ValasMutasiDebet + IF(@KodeMataUang = '', 0, NEW.Valas),
			ValasSaldoAkhir = ValasSaldoAwal + ValasMutasiDebet - ValasMutasiKredit						
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas + IF(@KodeMataUang = '', 0, NEW.Valas),
			SaldoBase = SaldoBase + NEW.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
				
		   ELSE
			
			UPDATE saldorekening SET	   
			MutasiKredit = MutasiKredit + NEW.Jumlah,
			SaldoAkhir = SaldoAwal + MutasiDebet - MutasiKredit,
			ValasMutasiKredit = ValasMutasiKredit + IF(@KodeMataUang = '', 0, NEW.Valas),
			ValasSaldoAkhir = ValasSaldoAwal + ValasMutasiDebet - ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas - IF(@KodeMataUang = '', 0, NEW.Valas),
			SaldoBase = SaldoBase - NEW.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
		   END IF;	   
		ELSE
		   IF NEW.`DK` = 1 THEN
			
			UPDATE saldorekening SET	   
			MutasiDebet = MutasiDebet + NEW.Jumlah,
			SaldoAkhir = SaldoAwal - MutasiDebet + MutasiKredit,
			ValasMutasiDebet = ValasMutasiDebet + IF(@KodeMataUang = '', 0, NEW.Valas),
			ValasSaldoAkhir = ValasSaldoAwal - ValasMutasiDebet + ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas - IF(@KodeMataUang = '', 0, NEW.Valas),
			SaldoBase = SaldoBase - NEW.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
		   ELSE
			
			UPDATE saldorekening SET	   
			MutasiKredit = MutasiKredit + NEW.Jumlah,
			SaldoAkhir = SaldoAwal - MutasiDebet + MutasiKredit,
			ValasMutasiKredit = ValasMutasiKredit + IF(@KodeMataUang = '', 0, NEW.Valas),
			ValasSaldoAkhir = ValasSaldoAwal - ValasMutasiDebet + ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas + IF(@KodeMataUang = '', 0, NEW.Valas),
			SaldoBase = SaldoBase + NEW.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID= NEW.RecordOwnerID;
				
		   END IF;	   
		END IF;
		
				
		SELECT `KodeRekeningInduk` INTO @KodeRekening FROM `rekeningakutansi`
		WHERE KodeRekening = @KodeRekening AND RecordOwnerID = NEW.RecordOwnerID; 
		
	END WHILE;			
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `detailjurnal_delete` AFTER DELETE ON `detailjurnal` FOR EACH ROW BEGIN
	
	SET @StatusTransaksi = -1;
	SELECT `StatusTransaksi` INTO @StatusTransaksi FROM `headerjurnal`
	WHERE `KodeTransaksi` = OLD.`KodeTransaksi` AND `NoTransaksi` = OLD.`NoTransaksi` AND RecordOwnerID = OLD.RecordOwnerID;
	
	SET @Periode = '' COLLATE utf8mb4_unicode_ci;
	SELECT `Periode` INTO @Periode FROM `headerjurnal`
	WHERE `KodeTransaksi` = OLD.`KodeTransaksi` AND `NoTransaksi` = OLD.`NoTransaksi` and RecordOwnerID = OLD.RecordOwnerID;
	
	
	
	SET @KodeRekening = OLD.KodeRekening;
	WHILE @KodeRekening <> "" DO
	
		
		SET @Posisi   = 0;
		SET @KodeMataUang = '';
		
		SELECT K.`Posisi` INTO @Posisi
		FROM `rekeningakutansi` R
		LEFT JOIN `kelompokrekening` K ON K.`id` = R.`KodeKelompok` AND R.RecordOwnerID = K.RecordOwnerID
		WHERE R.`KodeRekening` = @KodeRekening AND R.RecordOwnerID = OLD.RecordOwnerID;
		
-- 		SELECT R.`KodeMataUang` INTO @KodeMataUang 
-- 		FROM `rekeningakutansi` R
-- 		LEFT JOIN `kelompokrekening` K ON K.`id` = R.`KodeKelompok` AND R.RecordOwnerID = K.RecordOwnerID
-- 		WHERE R.`KodeRekening` = @KodeRekening AND R.RecordOwnerID = OLD.RecordOwnerID;
		
					
		IF (SELECT COUNT(*) FROM saldorekening WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID)=0 THEN
		    INSERT INTO saldorekening
		    (Periode, KodeRekening, SaldoAwal, MutasiDebet, MutasiKredit, SaldoAkhir, ValasSaldoAwal, ValasMutasiDebet, ValasMutasiKredit, ValasSaldoAkhir, RecordOwnerID) 
		    VALUES 
		    (@Periode, @KodeRekening, 0, 0, 0, 0, 0, 0, 0, 0, OLD.RecordOwnerID);       
		END IF;
			
		
		
		IF @Posisi = 1 THEN			   
		   IF OLD.`DK` = 1 THEN
			
			UPDATE saldorekening SET	   
			MutasiDebet = MutasiDebet - OLD.Jumlah,
			SaldoAkhir = SaldoAwal + MutasiDebet - MutasiKredit,
			ValasMutasiDebet = ValasMutasiDebet - IF(@KodeMataUang = '', 0, OLD.Valas),
			ValasSaldoAkhir = ValasSaldoAwal + ValasMutasiDebet - ValasMutasiKredit						
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas - IF(@KodeMataUang = '', 0, OLD.Valas),
			SaldoBase = SaldoBase - OLD.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
				
		   ELSE
			
			UPDATE saldorekening SET	   
			MutasiKredit = MutasiKredit - OLD.Jumlah,
			SaldoAkhir = SaldoAwal + MutasiDebet - MutasiKredit,
			ValasMutasiKredit = ValasMutasiKredit - IF(@KodeMataUang = '', 0, OLD.Valas),
			ValasSaldoAkhir = ValasSaldoAwal + ValasMutasiDebet - ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas + IF(@KodeMataUang = '', 0, OLD.Valas),
			SaldoBase = SaldoBase + OLD.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;			
			
		   END IF;	   
		ELSE
		   IF OLD.`DK` = 1 THEN
			
			UPDATE saldorekening SET	   
			MutasiDebet = MutasiDebet - OLD.Jumlah,
			SaldoAkhir = SaldoAwal - MutasiDebet + MutasiKredit,
			ValasMutasiDebet = ValasMutasiDebet - IF(@KodeMataUang = '', 0, OLD.Valas),
			ValasSaldoAkhir = ValasSaldoAwal - ValasMutasiDebet + ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas + IF(@KodeMataUang = '', 0, OLD.Valas),
			SaldoBase = SaldoBase + OLD.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
		   ELSE
			
			UPDATE saldorekening SET	   
			MutasiKredit = MutasiKredit - OLD.Jumlah,
			SaldoAkhir = SaldoAwal - MutasiDebet + MutasiKredit,
			ValasMutasiKredit = ValasMutasiKredit - IF(@KodeMataUang = '', 0, OLD.Valas),
			ValasSaldoAkhir = ValasSaldoAwal - ValasMutasiDebet + ValasMutasiKredit			
			WHERE Periode = @Periode AND KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
			
			UPDATE rekeningakutansi SET
			SaldoValas = SaldoValas - IF(@KodeMataUang = '', 0, OLD.Valas),
			SaldoBase = SaldoBase - OLD.Jumlah
			WHERE KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID;
				
		   END IF;	   
		END IF;
		
				
		SELECT `KodeRekeningInduk` INTO @KodeRekening FROM `rekeningakutansi`
		WHERE KodeRekening = @KodeRekening AND RecordOwnerID = OLD.RecordOwnerID; 
		
	END WHILE;			
	
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Moving_History` AFTER INSERT ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	INSERT INTO  itemmovinghistory
	VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'FPB',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Whs_stock` AFTER INSERT ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	SET @RowCount = 0;
	SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
	
	IF @RowCount > 0 THEN
		UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID AND KodeGudang = NEW.KodeGudang;
	END IF;
	
	IF @RowCount = 0 THEN 
		INSERT INTO itemwarehouses
		VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Update_Stock` AFTER INSERT ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	SET @OnHand = 0;
	SET @SumPrice = 0;
	
	SELECT Stock INTO @OnHand FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT (CASE WHEN HargaPokokPenjualan > 0 THEN HargaPokokPenjualan ELSE 0 END  * @OnHand) + NEW.HargaNet INTO @SumPrice FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	
	UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	UPDATE itemmaster SET HargaPokokPenjualan = @SumPrice/Stock, HargaBeliTerakhir = NEW.HargaNet / NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Update_Order` AFTER INSERT ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	SET @BaseReff = "";
	SET @QtyRealisasi = 0;
	set @StatusDocument = "";
	SELECT COALESCE(BaseReff,''), Qty INTO @BaseReff, @QtyRealisasi FROM fakturpembeliandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID AND NoUrut = NEW.BaseLine;
	
	SELECT `Status` INTO @StatusDocument FROM fakturpembelianheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @BaseReff <> '' and @StatusDocument <> 'D' THEN
		SET @OpenLine = 0;
		
		UPDATE orderpembeliandetail SET LineStatus = 'C' WHERE NoTransaksi = @BaseReff AND NoUrut = NEW.BaseLine AND RecordOwnerID = NEW.RecordOwnerID AND @QtyRealisasi BETWEEN Qty AND @QtyRealisasi;
		
		SELECT COUNT(*) INTO @OpenLine FROM orderpembeliandetail WHERE NoTransaksi = @BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND LineStatus = 'O';
		
		IF @OpenLine = 0 THEN
			UPDATE orderpembelianheader SET `Status` = 'C' WHERE NoTransaksi = @BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		END IF;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Moving_History_del` AFTER DELETE ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'FPB' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Whs_stock_Del` AFTER DELETE ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Update_Stock_Del` AFTER DELETE ON `fakturpembeliandetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_BL_Update_Cancel_Document` AFTER UPDATE ON `fakturpembelianheader` FOR EACH ROW BEGIN
	SET @StatusDocument = '';
	SELECT  `Status`  INTO @StatusDocument FROM fakturpembelianheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @StatusDocument ='D' THEN 
		UPDATE orderpembelianheader set `Status` = 'O' WHERE NoTransaksi IN (
			SELECT Distinct BaseReff FROM fakturpembeliandetail WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID
		);
		
		UPDATE orderpembeliandetail set LineStatus = 'O' WHERE NoTransaksi IN (
			SELECT Distinct BaseReff FROM fakturpembeliandetail WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID
		);
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Faktur_Update_Order` AFTER INSERT ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @BaseReff = "";
	SET @QtyRealisasi = 0;
	SELECT COALESCE(BaseReff,''), Qty INTO @BaseReff, @QtyRealisasi FROM fakturpenjualandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID AND NoUrut = NEW.NoUrut and KodeItem = NEW.KodeItem limit 1;
	
	INSERT INTO templogtrigger
	SELECT CONCAT('No Faktur : ', NEW.NoTransaksi, ' RecordOwnerID : ', NEW.RecordOwnerID,' ');
	IF @BaseReff <> '' THEN
		SET @OpenLine = 0;
		
		UPDATE deliverynotedetail SET LineStatus = 'C' WHERE NoTransaksi = @BaseReff AND NoUrut = NEW.BaseLine AND RecordOwnerID = NEW.RecordOwnerID AND @QtyRealisasi BETWEEN Qty AND @QtyRealisasi;
		
		SELECT COUNT(*) INTO @OpenLine FROM deliverynotedetail WHERE NoTransaksi = @BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND LineStatus = 'O';
		
		IF @OpenLine = 0 THEN
			UPDATE deliverynoteheader SET `Status` = 'C' WHERE NoTransaksi = @BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		END IF;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Faktur_Moving_History` AFTER INSERT ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @StatusTransaksi = '';
	SET @Basereff = '';
	SELECT `Status` INTO @StatusTransaksi FROM fakturpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID and NoUrut = NEW.NoUrut and KodeItem = NEW.KodeItem;
	
	IF @Basereff = '' THEN
		IF @StatusTransaksi = 'D' THEN
			INSERT INTO  itemmovinghistory
			VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'OINV',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
		ELSE 
			INSERT INTO  itemmovinghistory
			VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'OINV',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
		END IF;
	END IF;
	
	IF @Basereff = 'POS' THEN
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'OINV',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_faktur_Whs_stock` AFTER INSERT ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @RowCount = 0;
	SET @StatusTransaksi = '';
	SET @BaseReff = '';
	
	SELECT `Status` INTO @StatusTransaksi FROM fakturpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem and NoUrut = NEW.NoUrut;
	
	IF @Basereff = '' THEN
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
	END IF;
	
	IF @BaseReff ='POS' THEN
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_faktur_Update_Stock` AFTER INSERT ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @OnHand = 0;
	SET @SumPrice = 0;
	SET @StatusTransaksi = '';
	SET @BaseReff = '';
	
	SELECT `Status` INTO @StatusTransaksi FROM fakturpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT Stock INTO @OnHand FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID and NoUrut = NEW.NoUrut AND KodeItem = NEW.KodeItem;
	
	IF @Basereff = '' THEN
		IF @StatusTransaksi = 'D' THEN
			UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
		ELSE
			UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
		END IF;
	END IF;
	
	if @Basereff = 'POS' THEN
		UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_faktur_Update_Stock_Del` AFTER DELETE ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @BaseReff = '';
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID and NoUrut = OLD.NoUrut AND KodeItem = OLD.KodeItem;
	
	IF @BaseReff = '' THEN
		UPDATE itemmaster SET Stock = Stock + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_faktur_Whs_stock_Del` AFTER DELETE ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @BaseReff = '';
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID and NoUrut = OLD.NoUrut AND KodeItem = OLD.KodeItem;
	
	IF @BaseReff = '' THEN
		UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
	end if ;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_faktur_Moving_History_del` AFTER DELETE ON `fakturpenjualandetail` FOR EACH ROW BEGIN
	SET @StatusDocument = '';
	SET @BaseReff = '';
	
	SELECT `Status` INTO @StatusDocument FROM fakturpenjualanheader WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	SELECT COALESCE(BaseReff, '') INTO @Basereff FROM fakturpenjualandetail WHERE NoTransaksi = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID and NoUrut = OLD.NoUrut AND KodeItem = OLD.KodeItem;
	
	
	IF @Basereff = '' THEN
		IF @StatusDocument != "D"
		THEN
			DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'OINV' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
		END IF;
		
		IF @StatusDocument = 'D'
		THEN 
			INSERT INTO  itemmovinghistory
			VALUES(OLD.KodeItem,OLD.KodeGudang,NOW(),OLD.NoTransaksi,'COINV',OLD.Qty,0,OLD.RecordOwnerID,NOW(), NOW());
		END IF;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trgUpdateHarga` AFTER INSERT ON `historyhargajual` FOR EACH ROW BEGIN 
	SET @HargaBeliAkhir = 0;
	SELECT COALESCE(HargaBeliTerakhir,0) INTO @HargaBeliAkhir FROM itemmaster WHERE KodeItem = NEW.KodeItem;
	
	IF @HargaBeliAkhir > 0 THEN
		UPDATE itemmaster SET HargaJual = NEW.HargaJual WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
	IF @HargaBeliAkhir = 0 THEN
		UPDATE itemmaster SET HargaJual = NEW.HargaJual, HargaBeliTerakhir = NEW.HargaJual WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_add_jurnaldetail` AFTER INSERT ON `kaskeluardetail` FOR EACH ROW BEGIN
	SET @PostAcct = 0;
	SET @NoTransaksi = '';
	SET @StatusDocument = '';
	SET @KeteranganHeader = '';
	SET @TotalTransaksi = 0;
	SET @AkunHeader = '';
	
	SET @MaxID = -1;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	SELECT StatusDocument, Keterangan, KodeAkun, TotalTransaksi INTO @StatusDocument, @KeteranganHeader, @AkunHeader, @TotalTransaksi FROM kaskeluarheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	SELECT MAX(id) INTO @MaxID FROM kaskeluardetail WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	-- Jika Posting Accounting 
	IF @PostAcct = 1 THEN 
		SELECT NoTransaksi INTO @NoTransaksi FROM headerjurnal WHERE NoReff = NEW.NoTransaksi and KodeTransaksi = 'KOUT';
		
		INSERT INTO detailjurnal VALUES
		('KOUT',@NoTransaksi, NEW.LineNumber, NEW.KodeAkun, '', CASE WHEN @StatusDocument = 'D' THEN 2 ELSE 1 END,'',0,0,NEW.TotalTransaksi, @KeteranganHeader, @KeteranganHeader, NEW.RecordOwnerID, NOW(), NOW());
		
		
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_delete_jurnaldetail` AFTER DELETE ON `kaskeluardetail` FOR EACH ROW BEGIN
	SET @PostAcct = 0;
	SET @JurnalNumber = '';
	SET @StatusDocument = '';
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = OLD.RecordOwnerID;
	SELECT StatusDocument INTO @StatusDocument FROM kaskeluarheader WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	IF @PostAcct = 1 AND @StatusDocument = 'O' THEN 
		SELECT NoTransaksi into @JurnalNumber FROM headerjurnal WHERE NoReff = OLD.NoTransaksi and KodeTransaksi = 'KOUT';
	
		IF @JurnalNumber ='' THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'Jurnal Entry Tidak Valid';
		END IF;
		
		DELETE FROM detailjurnal WHERE NoTransaksi = @JurnalNumber AND NoUrut = OLD.LineNumber and KodeTransaksi = 'KOUT' AND RecordOwnerID = OLD.RecordOwnerID;
		
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_add_jurnalheader` AFTER INSERT ON `kaskeluarheader` FOR EACH ROW begin 
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @PostAcct = 0;
	SET @Prefix = '';
	SET @NumberLength = 6;
	SET @RunningNumber = 0;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE RecordOwnerID = NEW.RecordOwnerID and DocumentID = 'KOUT';
	
	SELECT COUNT(*) INTO @RunningNumber FROM headerjurnal WHERE RecordOwnerID collate utf8mb4_general_ci = NEW.RecordOwnerID and LEFT(NoTransaksi collate utf8mb4_general_ci,LENGTH(CONCAT(@Period,@Prefix))) = CONCAT(@Period,@Prefix);
	
	SET @NomorTransaksi = CONCAT(CONCAT(@Period,@Prefix), LPAD(@RunningNumber +1, @NumberLength, '0'));
	
	IF @PostAcct = 1 THEN 
		INSERT INTO headerjurnal
		VALUES(@Period, 'KOUT', @NomorTransaksi, NEW.TglTransaksi, NEW.NoTransaksi, 'O',NEW.RecordOwnerID, NULL, NOW(), NOW());
		
		-- insert lawan jurnal
		INSERT INTO detailjurnal VALUES
		('KOUT',@NomorTransaksi, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 1 ELSE 2 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_update_jurnalheader` AFTER UPDATE ON `kaskeluarheader` FOR EACH ROW BEGIN 
	SET @PostAcct = 0;
	SET @JurnalNumber = '';
	
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @PostAcct = 0;
	SET @Prefix = '';
	SET @NumberLength = 6;
	SET @RunningNumber = 0;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	
	IF @PostAcct = 1 AND NEW.StatusDocument = 'O' THEN 
		SELECT NoTransaksi into @JurnalNumber FROM headerjurnal WHERE NoReff = OLD.NoTransaksi and KodeTransaksi = 'KOUT';
		DELETE FROM detailjurnal WHERE NoTransaksi = @JurnalNumber AND NoUrut = -1 AND RecordOwnerID = NEW.RecordOwnerID and KodeTransaksi = 'KOUT';
		
		INSERT INTO detailjurnal VALUES
		('KOUT',@JurnalNumber, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 1 ELSE 2 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
	
	IF @PostAcct = 1 AND NEW.StatusDocument = 'D' THEN 
		SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE RecordOwnerID = NEW.RecordOwnerID and DocumentID = 'KOUT';
	
	SELECT COUNT(*) INTO @RunningNumber FROM headerjurnal WHERE RecordOwnerID collate utf8mb4_general_ci = NEW.RecordOwnerID and LEFT(NoTransaksi collate utf8mb4_general_ci,LENGTH(CONCAT(@Period,@Prefix))) = CONCAT(@Period,@Prefix);
	
	SET @NomorTransaksi = CONCAT(CONCAT(@Period,@Prefix), LPAD(@RunningNumber +1, @NumberLength, '0'));
	
		INSERT INTO headerjurnal
		VALUES(@Period, 'KOUT', @NomorTransaksi, NEW.TglTransaksi, NEW.NoTransaksi, 'O',NEW.RecordOwnerID, NULL, NOW(), NOW());
		
		-- insert lawan jurnal
		INSERT INTO detailjurnal VALUES
		('KOUT',@NomorTransaksi, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 1 ELSE 2 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
		
		
		INSERT INTO detailjurnal
		SELECT 'KOUT', @NomorTransaksi, LineNumber, KodeAkun, '',  CASE WHEN NEW.StatusDocument = 'D' THEN 2 ELSE 1 END,'',0,0,TotalTransaksi, Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW() FROM kaskeluardetail WHERE NoTransaksi = NEW.NoTransaksi;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_add_jurnaldetail_kasmasuk` AFTER INSERT ON `kasmasukdetail` FOR EACH ROW BEGIN
	SET @PostAcct = 0;
	SET @NoTransaksi = '';
	SET @StatusDocument = '';
	SET @KeteranganHeader = '';
	SET @TotalTransaksi = 0;
	SET @AkunHeader = '';
	
	SET @MaxID = -1;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	
	SELECT StatusDocument, Keterangan, KodeAkun, TotalTransaksi INTO @StatusDocument, @KeteranganHeader, @AkunHeader, @TotalTransaksi FROM kasmasukheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	SELECT MAX(id) INTO @MaxID FROM kasmasukdetail WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	-- Jika Posting Accounting 
	IF @PostAcct = 1 THEN 
		SELECT NoTransaksi INTO @NoTransaksi FROM headerjurnal WHERE NoReff = NEW.NoTransaksi and KodeTransaksi = 'KIN';
		
		INSERT INTO detailjurnal VALUES
		('KIN',@NoTransaksi, NEW.LineNumber, NEW.KodeAkun, '', CASE WHEN @StatusDocument = 'D' THEN 1 ELSE 2 END,'',0,0,NEW.TotalTransaksi, @KeteranganHeader, @KeteranganHeader, NEW.RecordOwnerID, NOW(), NOW());
		
		
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_delete_jurnaldetail_kasmasuk` AFTER DELETE ON `kasmasukdetail` FOR EACH ROW BEGIN
	SET @PostAcct = 0;
	SET @JurnalNumber = '';
	SET @StatusDocument = '';
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = OLD.RecordOwnerID;
	
	SELECT StatusDocument INTO @StatusDocument FROM kasmasukheader WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	IF @PostAcct = 1 AND @StatusDocument = 'O' THEN 
		SELECT NoTransaksi into @JurnalNumber FROM headerjurnal WHERE NoReff = OLD.NoTransaksi and KodeTransaksi = 'KIN';
	
		IF @JurnalNumber ='' THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'Jurnal Entry Tidak Valid';
		END IF;
		
		DELETE FROM detailjurnal WHERE NoTransaksi = @JurnalNumber AND NoUrut = OLD.LineNumber and KodeTransaksi = 'KIN' AND RecordOwnerID = OLD.RecordOwnerID;
		
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_add_jurnalheader_kasmasuk` AFTER INSERT ON `kasmasukheader` FOR EACH ROW begin 
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @PostAcct = 0;
	SET @Prefix = '';
	SET @NumberLength = 6;
	SET @RunningNumber = 0;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	
	SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE RecordOwnerID = NEW.RecordOwnerID and DocumentID = 'KIN';
	
	SELECT COUNT(*) INTO @RunningNumber FROM headerjurnal WHERE RecordOwnerID collate utf8mb4_general_ci = NEW.RecordOwnerID and LEFT(NoTransaksi collate utf8mb4_general_ci,LENGTH(CONCAT(@Period,@Prefix))) = CONCAT(@Period,@Prefix);
	
	SET @NomorTransaksi = CONCAT(CONCAT(@Period,@Prefix), LPAD(@RunningNumber +1, @NumberLength, '0'));
-- 	Call GenerateDocNumber('KIN', 'headerjurnal', 'NoTransaksi', NEW.RecordOwnerID, @NomorTransaksi);
	
	
	IF @PostAcct = 1 THEN 
		INSERT INTO headerjurnal
		VALUES(@Period, 'KIN', @NomorTransaksi, NEW.TglTransaksi, NEW.NoTransaksi, 'O',NEW.RecordOwnerID, NULL, NOW(), NOW());
		
		-- insert lawan jurnal
		INSERT INTO detailjurnal VALUES
		('KIN',@NomorTransaksi, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 2 ELSE 1 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_update_jurnalheader_kasmasuk` AFTER UPDATE ON `kasmasukheader` FOR EACH ROW BEGIN 
	SET @PostAcct = 0;
	SET @JurnalNumber = '';
	
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @PostAcct = 0;
	SET @Prefix = '';
	SET @NumberLength = 6;
	SET @RunningNumber = 0;
	
	SELECT isPostingAkutansi INTO @PostAcct FROM company WHERE KodePartner = NEW.RecordOwnerID;
	
	IF @PostAcct = 1 AND NEW.StatusDocument = 'O' THEN 
		SELECT NoTransaksi into @JurnalNumber FROM headerjurnal WHERE NoReff = OLD.NoTransaksi and KodeTransaksi = 'KIN';
		DELETE FROM detailjurnal WHERE NoTransaksi = @JurnalNumber AND NoUrut = -1 AND RecordOwnerID = NEW.RecordOwnerID and KodeTransaksi = 'KIN';
		
		INSERT INTO detailjurnal VALUES
		('KIN',@JurnalNumber, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 2 ELSE 1 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
	
	IF @PostAcct = 1 AND NEW.StatusDocument = 'D' THEN 
-- 		Call GenerateDocNumber('KIN', 'headerjurnal', 'NoTransaksi', NEW.RecordOwnerID, @NomorTransaksi);
		SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE RecordOwnerID = NEW.RecordOwnerID and DocumentID = 'KIN';
	
		SELECT COUNT(*) INTO @RunningNumber FROM headerjurnal WHERE RecordOwnerID collate utf8mb4_general_ci = NEW.RecordOwnerID and LEFT(NoTransaksi collate utf8mb4_general_ci,LENGTH(CONCAT(@Period,@Prefix))) = CONCAT(@Period,@Prefix);
		
		SET @NomorTransaksi = CONCAT(CONCAT(@Period,@Prefix), LPAD(@RunningNumber +1, @NumberLength, '0'));
	
		INSERT INTO headerjurnal
		VALUES(@Period, 'KIN', @NomorTransaksi, NEW.TglTransaksi, NEW.NoTransaksi, 'O',NEW.RecordOwnerID, NULL, NOW(), NOW());
		
		-- insert lawan jurnal
		INSERT INTO detailjurnal VALUES
		('KIN',@NomorTransaksi, -1, NEW.KodeAkun, '', CASE WHEN NEW.StatusDocument = 'D' THEN 2 ELSE 1 END,'',0,0,NEW.TotalTransaksi, NEW.Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW());
		
		
		INSERT INTO detailjurnal
		SELECT 'KIN', @NomorTransaksi, LineNumber, KodeAkun, '',  CASE WHEN NEW.StatusDocument = 'D' THEN 1 ELSE 2 END,'',0,0,TotalTransaksi, Keterangan, NEW.Keterangan, NEW.RecordOwnerID, NOW(), NOW() FROM kasmasukdetail WHERE NoTransaksi = NEW.NoTransaksi;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_upd_status_faktur` AFTER INSERT ON `pembayarandetail` FOR EACH ROW BEGIN
	SET @StatusDokumen = '';
	SELECT `Status` INTO @StatusDokumen FROM pembayaranheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @StatusDokumen = 'O' THEN 
		UPDATE fakturpembelianheader SET TotalPembayaran = NEW.TotalPembayaran, `Status` = 'C' WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_update_cancel` AFTER UPDATE ON `pembayaranheader` FOR EACH ROW BEGIN 
	SET @StatusDokumen = (SELECT NEW.Status);
	
	IF @StatusDokumen = 'D' THEN 
		UPDATE fakturpembelianheader
	JOIN pembayarandetail on fakturpembelianheader.NoTransaksi = pembayarandetail.BaseReff and fakturpembelianheader.RecordOwnerID = pembayarandetail.RecordOwnerID
	SET fakturpembelianheader.`Status` = 'O', fakturpembelianheader.TotalPembayaran = 0
	WHERE pembayarandetail.NoTransaksi = NEW.NoTransaksi AND pembayarandetail.RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_upd_status_faktur_penjualan` AFTER INSERT ON `pembayaranpenjualandetail` FOR EACH ROW BEGIN
	SET @StatusDokumen = '';
	SET @NoTransaksiTableOrder = '';
	SET @CountTableOrder = 0;
	SELECT `Status` INTO @StatusDokumen FROM pembayaranpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID;
	
	IF @StatusDokumen = 'O' THEN 
		UPDATE fakturpenjualanheader SET TotalPembayaran = NEW.TotalPembayaran, `Status` = 'C' WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
	IF @StatusDokumen = 'D' THEN
		UPDATE fakturpenjualanheader SET TotalPembayaran = 0, `Status` = 'O' WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_update_cancel_pembayaran_penjualan` AFTER UPDATE ON `pembayaranpenjualanheader` FOR EACH ROW BEGIN 
	SET @StatusDokumen = (SELECT NEW.Status);
	
	IF @StatusDokumen = 'D' THEN 
		UPDATE fakturpenjualanheader
	JOIN pembayaranpenjualandetail on fakturpenjualanheader.NoTransaksi = pembayaranpenjualandetail.BaseReff and fakturpenjualanheader.RecordOwnerID = pembayaranpenjualandetail.RecordOwnerID
	SET fakturpenjualanheader.`Status` = 'O', fakturpenjualanheader.TotalPembayaran = 0
	WHERE pembayaranpenjualandetail.NoTransaksi = NEW.NoTransaksi AND pembayaranpenjualandetail.RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Moving_History` AFTER INSERT ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	INSERT INTO  itemmovinghistory
	VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CONS',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Whs_stock` AFTER INSERT ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	SET @RowCount = 0;
	SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
	
	IF @RowCount > 0 THEN
		UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID AND KodeGudang = NEW.KodeGudang;
	END IF;
	
	IF @RowCount = 0 THEN 
		INSERT INTO itemwarehouses
		VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty, NEW.RecordOwnerID, NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Update_Stock` AFTER INSERT ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	SET @OnHand = 0;
	SET @SumPrice = 0;
	
	SELECT Stock INTO @OnHand FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	SELECT (CASE WHEN HargaPokokPenjualan > 0 THEN HargaPokokPenjualan ELSE 0 END  * @OnHand) + NEW.HargaNet INTO @SumPrice FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	
	UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	UPDATE itemmaster SET HargaPokokPenjualan = @SumPrice/Stock, HargaBeliTerakhir = NEW.HargaNet / NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Moving_History_del` AFTER DELETE ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'CONS' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Whs_stock_del` AFTER DELETE ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_Cons_Update_Stock_del` AFTER DELETE ON `penerimaankonsinyasidetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Moving_History` AFTER INSERT ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM pengakuanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'D' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'GR',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW()),
		(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CGR',NEW.Qty * -1,0,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
	
	IF @DocStatus = 'O' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'GR',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Update_Stock` AFTER INSERT ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM pengakuanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'O' THEN 
		SET @OnHand = 0;
		SET @SumPrice = 0;
		
		SELECT Stock INTO @OnHand FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
		SELECT (CASE WHEN HargaPokokPenjualan > 0 THEN HargaPokokPenjualan ELSE 0 END  * @OnHand) + NEW.TotalTransaksi INTO @SumPrice FROM itemmaster WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
		
		UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
		UPDATE itemmaster SET HargaPokokPenjualan = @SumPrice/Stock, HargaBeliTerakhir = NEW.TotalTransaksi / NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	
-- 		UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Whs_stock` AFTER INSERT ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
		SET @DocStatus = '';
		SELECT `Status` INTO @DocStatus FROM pengakuanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
		
		IF @DocStatus = 'O' THEN 
			SET @RowCount = 0;
			SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
			
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID and KodeGudang = NEW.KodeGudang;
			END IF;
			
			IF @RowCount = 0 THEN 
				INSERT INTO itemwarehouses
				VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty, NEW.RecordOwnerID, NOW(), NOW());
			END IF;
		END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Moving_History_del` AFTER DELETE ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'GR' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Whs_stock_Del` AFTER DELETE ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjIn_Update_Stock_Del` AFTER DELETE ON `pengakuanbarangdetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Update_Stock` AFTER INSERT ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM penghapusanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'O' THEN 
		UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Moving_History` AFTER INSERT ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM penghapusanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'D' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'GI',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW()),
		(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CGI',0,NEW.Qty * -1,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
	
	IF @DocStatus = 'O' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'GI',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Whs_stock` AFTER INSERT ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
		SET @DocStatus = '';
		SELECT `Status` INTO @DocStatus FROM penghapusanbarangheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
		
		IF @DocStatus = 'O' THEN 
			SET @RowCount = 0;
			SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
			
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID and KodeGudang = NEW.KodeGudang;
			END IF;
			
			IF @RowCount = 0 THEN 
				INSERT INTO itemwarehouses
				VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty, NEW.RecordOwnerID, NOW(), NOW());
			END IF;
		END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Moving_History_del` AFTER DELETE ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'GI' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Whs_stock_Del` AFTER DELETE ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_adjOut_Update_Stock_Del` AFTER DELETE ON `penghapusanbarangdetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Update_Stock` AFTER INSERT ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returkonsinyasiheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'O' THEN 
		UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Update_Faktur` AFTER INSERT ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returkonsinyasiheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
-- 	Jika Transaksi Normal
	IF @DocStatus = 'O' THEN 
		-- 	Update Retur
		UPDATE penerimaankonsinyasiheader SET TotalRetur = COALESCE(TotalRetur,0) +  NEW.HargaNet
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		
	-- 	Update Total
		UPDATE penerimaankonsinyasiheader SET TotalTransaksi = COALESCE(TotalPembelian,0) - COALESCE(TotalRetur,0)
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		
	-- 	Update Line Total
		SET @NilaiPerItem = 0;
		SET @Qty = 0;

		SELECT HargaNet, Qty INTO @NilaiPerItem, @Qty FROM returkonsinyasidetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.NoUrut;
		
		UPDATE penerimaankonsinyasidetail SET HargaNet = ((Qty - NEW.Qty) * Harga)
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.BaseLine;
		
		

	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Moving_History` AFTER INSERT ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returkonsinyasiheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'D' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RTC',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW()),
		(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CRTC',0,NEW.Qty * -1,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
	
	IF @DocStatus = 'O' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RTC',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Whs_stock` AFTER INSERT ON `returkonsinyasidetail` FOR EACH ROW BEGIN
		SET @DocStatus = '';
		SELECT `Status` INTO @DocStatus FROM returkonsinyasiheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
		
		IF @DocStatus = 'O' THEN 
			SET @RowCount = 0;
			SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
			
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID and KodeGudang = NEW.KodeGudang;
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Moving_History_del` AFTER DELETE ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'RTC' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Whs_stock_Del` AFTER DELETE ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Update_Stock_Del` AFTER DELETE ON `returkonsinyasidetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retCons_Update_Faktur_Del` AFTER DELETE ON `returkonsinyasidetail` FOR EACH ROW BEGIN
-- 	Update Retur
	UPDATE penerimaankonsinyasiheader SET TotalRetur = COALESCE(TotalRetur,0) -  OLD.HargaNet
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
	
-- 	Update Total
	UPDATE penerimaankonsinyasiheader SET TotalTransaksi = COALESCE(TotalPembelian,0) + COALESCE(TotalRetur,0)
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
	

-- 	Update Line Total
	SET @NilaiPerItem = 0;
	SET @Qty = 0;

	SELECT HargaNet, Qty INTO @NilaiPerItem, @Qty FROM returkonsinyasidetail WHERE NoTransaksi = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.NoUrut;
	
	UPDATE penerimaankonsinyasidetail SET HargaNet = (Qty) * Harga
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.BaseLine;

	
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Update_Faktur` AFTER INSERT ON `returpembeliandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returpembelianheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
-- 	Jika Cancel 
	IF @DocStatus = 'D' THEN 

	-- 	Update Line Status
		SET @NoOrder = '';
		SET @BaseLine = -1;
		SELECT DISTINCT c.NoTransaksi, c.NoUrut INTO @NoOrder, @BaseLine FROM returpembeliandetail a
		LEFT JOIN fakturpembeliandetail b on a.BaseReff = b.NoTransaksi AND a.BaseLine = b.NoUrut AND a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN orderpembeliandetail c on b.BaseReff = c.NoTransaksi and b.BaseLine = c.NoUrut and b.RecordOwnerID = c.RecordOwnerID
		WHERE a.NoTransaksi = NEW.NoTransaksi AND a.KodeItem = NEW.KodeItem AND a.NoUrut = NEW.NoUrut AND a.RecordOwnerID = NEW.RecordOwnerID;
		
		UPDATE orderpembeliandetail set LineStatus = 'C' WHERE LineStatus = 'O' AND NoTransaksi = @NoOrder AND KodeItem = NEW.KodeItem AND NoUrut = @BaseLine;
		
		UPDATE orderpembelianheader set `Status` = 'C' WHERE `Status` = 'O' AND NoTransaksi = @NoOrder AND RecordOwnerID = NEW.RecordOwnerID;
	END IF ;
	
-- 	Jika Transaksi Normal
	IF @DocStatus = 'O' THEN 
		-- 	Update Retur
		UPDATE fakturpembelianheader SET TotalRetur = COALESCE(TotalRetur,0) +  NEW.HargaNet
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		
	-- 	Update Total
		UPDATE fakturpembelianheader SET TotalTransaksi = COALESCE(TotalPembelian,0) - COALESCE(TotalRetur,0)
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
		
	-- 	Update Line Total
		SET @NilaiPerItem = 0;
		SET @Qty = 0;

		SELECT HargaNet, Qty INTO @NilaiPerItem, @Qty FROM returpembeliandetail WHERE NoTransaksi = NEW.NoTransaksi AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.NoUrut;
		UPDATE fakturpembeliandetail SET HargaNet = ((Qty - NEW.Qty) * Harga) - ((Qty - NEW.Qty) * Harga) * Discount / 100
		WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.BaseLine;

	-- 	Update Line Status
		SET @NoOrder = '';
		SET @BaseLine = -1;
		SELECT DISTINCT c.NoTransaksi, c.NoUrut INTO @NoOrder, @BaseLine FROM returpembeliandetail a
		LEFT JOIN fakturpembeliandetail b on a.BaseReff = b.NoTransaksi AND a.BaseLine = b.NoUrut AND a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN orderpembeliandetail c on b.BaseReff = c.NoTransaksi and b.BaseLine = c.NoUrut and b.RecordOwnerID = c.RecordOwnerID
		WHERE a.NoTransaksi = NEW.NoTransaksi AND a.KodeItem = NEW.KodeItem AND a.NoUrut = NEW.NoUrut AND a.RecordOwnerID = NEW.RecordOwnerID;
		
		UPDATE orderpembeliandetail set LineStatus = 'O' WHERE LineStatus = 'C' AND NoTransaksi = @NoOrder AND KodeItem = NEW.KodeItem AND NoUrut = @BaseLine;
		
		UPDATE orderpembelianheader set `Status` = 'O' WHERE `Status` = 'C' AND NoTransaksi = @NoOrder AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Moving_History` AFTER INSERT ON `returpembeliandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returpembelianheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'D' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RTB',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW()),
		(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CRTB',0,NEW.Qty * -1,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
	
	IF @DocStatus = 'O' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RTB',0,NEW.Qty,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Update_Stock` AFTER INSERT ON `returpembeliandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returpembelianheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'O' THEN 
		UPDATE itemmaster SET Stock = Stock - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Whs_stock` AFTER INSERT ON `returpembeliandetail` FOR EACH ROW BEGIN
		SET @DocStatus = '';
		SELECT `Status` INTO @DocStatus FROM returpembelianheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
		
		IF @DocStatus = 'O' THEN 
			SET @RowCount = 0;
			SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
			
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID and KodeGudang = NEW.KodeGudang;
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Moving_History_del` AFTER DELETE ON `returpembeliandetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'RTB' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Whs_stock_Del` AFTER DELETE ON `returpembeliandetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Update_Stock_Del` AFTER DELETE ON `returpembeliandetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock + OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retBeli_Update_Faktur_Del` AFTER DELETE ON `returpembeliandetail` FOR EACH ROW BEGIN
-- 	Update Retur
	UPDATE fakturpembelianheader SET TotalRetur = COALESCE(TotalRetur,0) -  OLD.HargaNet
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
	
-- 	Update Total
	UPDATE fakturpembelianheader SET TotalTransaksi = COALESCE(TotalPembelian,0) + COALESCE(TotalRetur,0)
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
	

-- 	Update Line Total
	SET @NilaiPerItem = 0;
	SET @Qty = 0;

	SELECT HargaNet, Qty INTO @NilaiPerItem, @Qty FROM returpembeliandetail WHERE NoTransaksi = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.NoUrut;
	UPDATE fakturpembeliandetail SET HargaNet = ((Qty) * Harga) - ((Qty ) * Harga) * Discount / 100
	WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.BaseLine;

-- 	Update Line Status
	SET @NoOrder = '' COLLATE utf8mb4_unicode_ci;
	SET @BaseLine = -1;
	SELECT DISTINCT c.NoTransaksi, c.NoUrut INTO @NoOrder, @BaseLine FROM returpembeliandetail a
	LEFT JOIN fakturpembeliandetail b on a.BaseReff = b.NoTransaksi AND a.BaseLine = b.NoUrut AND a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN orderpembeliandetail c on b.BaseReff = c.NoTransaksi and b.BaseLine = c.NoUrut and b.RecordOwnerID = c.RecordOwnerID
	WHERE a.NoTransaksi = OLD.NoTransaksi AND a.KodeItem = OLD.KodeItem AND a.NoUrut = OLD.NoUrut AND a.RecordOwnerID = OLD.RecordOwnerID;
	
	UPDATE orderpembeliandetail set LineStatus = 'C' WHERE LineStatus = 'O' AND NoTransaksi = @NoOrder AND KodeItem = OLD.KodeItem AND NoUrut = @BaseLine AND RecordOwnerID = OLD.RecordOwnerID;
	
	UPDATE orderpembelianheader set `Status` = 'C' WHERE `Status` = 'O' AND NoTransaksi = @NoOrder AND RecordOwnerID = OLD.RecordOwnerID;
	
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Update_Stock` AFTER INSERT ON `returpenjualandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'O' THEN 
		UPDATE itemmaster SET Stock = Stock + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Update_Faktur` AFTER INSERT ON `returpenjualandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SET @BaseType = '';
	SET @NilaiPerItem = 0;
	SET @QtyRetur = 0;
	
	SELECT `Status` INTO @DocStatus FROM returpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	SELECT BaseType,HargaNet, Qty INTO @BaseType,@NilaiPerItem, @QtyRetur FROM returpenjualandetail WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID and NoUrut = NEW.NoUrut;
	
-- 	Jika Transaksi Normal
	IF @DocStatus = 'O' THEN 
		
		IF @BaseType = 'ODLN' THEN
			-- 	Update Retur
			UPDATE deliverynoteheader SET 
				TotalRetur = COALESCE(TotalRetur,0) +  NEW.HargaNet,
				`Status` = CASE WHEN (COALESCE(TotalRetur,0) - TotalPembelian) <= 0 THEN 'C' ELSE 'O' END
			WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
			
			-- 	Update Line Total
			
			UPDATE deliverynotedetail SET 
				QtyRetur = @QtyRetur,
				`LineStatus` = CASE WHEN (COALESCE(QtyRetur, 0) - Qty) <= 0 THEN 'C' ELSE 'O' END
			WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.BaseLine;
			
		END IF;
		
		IF @BaseType = 'OINV' THEN
			-- 	Update Retur
			UPDATE fakturpenjualanheader SET 
				TotalRetur = COALESCE(TotalRetur,0) +  NEW.HargaNet,
				`Status` = CASE WHEN (COALESCE(TotalRetur,0) - TotalPembelian) <= 0 THEN 'C' ELSE 'O' END
			WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID;
			
			
			-- 	Update Line Total
			
			UPDATE fakturpenjualandetail SET 
				QtyRetur = @QtyRetur,
				`LineStatus` = CASE WHEN (COALESCE(QtyRetur, 0) - Qty) <= 0 THEN 'C' ELSE 'O' END
			WHERE NoTransaksi = NEW.BaseReff AND RecordOwnerID = NEW.RecordOwnerID AND KodeItem = NEW.KodeItem AND NoUrut = NEW.BaseLine;
		END IF;

	END IF;
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Moving_History` AFTER INSERT ON `returpenjualandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SELECT `Status` INTO @DocStatus FROM returpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
	
	IF @DocStatus = 'D' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RPJ',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW()),
		(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'CRPJ',NEW.Qty * -1,0,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
	
	IF @DocStatus = 'O' THEN 
		INSERT INTO  itemmovinghistory
		VALUES(NEW.KodeItem,NEW.KodeGudang,NOW(),NEW.NoTransaksi,'RPJ',NEW.Qty,0,NEW.RecordOwnerID,NOW(), NOW());
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Whs_stock` AFTER INSERT ON `returpenjualandetail` FOR EACH ROW BEGIN
		SET @DocStatus = '';
		SELECT `Status` INTO @DocStatus FROM returpenjualanheader WHERE NoTransaksi = NEW.NoTransaksi and RecordOwnerID = NEW.RecordOwnerID;
		
		IF @DocStatus = 'O' THEN 
			SET @RowCount = 0;
			SELECT COUNT(*) into @RowCount FROM itemwarehouses WHERE KodeItem = NEW.KodeItem AND KodeGudang = NEW.KodeGudang AND RecordOwnerID =NEW.RecordOwnerID;
			
			IF @RowCount > 0 THEN
				UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) + NEW.Qty WHERE KodeItem = NEW.KodeItem AND RecordOwnerID = NEW.RecordOwnerID and KodeGudang = NEW.KodeGudang;
			END IF;
			
			IF @RowCount = 0 THEN 
				INSERT INTO itemwarehouses
				VALUES(NEW.KodeItem, NEW.KodeGudang,NEW.Qty , NEW.RecordOwnerID, NOW(), NOW());
			END IF;
		END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Moving_History_del` AFTER DELETE ON `returpenjualandetail` FOR EACH ROW BEGIN
	DELETE FROM itemmovinghistory WHERE KodeItem = OLD.KodeItem AND BaseType = 'RPJ' AND BaseReff = OLD.NoTransaksi AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Whs_stock_del` AFTER DELETE ON `returpenjualandetail` FOR EACH ROW BEGIN
	UPDATE itemwarehouses SET Qty = COALESCE(Qty,0) - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID  and KodeGudang = OLD.KodeGudang;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Update_Stock_del` AFTER DELETE ON `returpenjualandetail` FOR EACH ROW BEGIN
	UPDATE itemmaster SET Stock = Stock - OLD.Qty WHERE KodeItem = OLD.KodeItem AND RecordOwnerID = OLD.RecordOwnerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_retPenj_Update_Faktur_del` AFTER DELETE ON `returpenjualandetail` FOR EACH ROW BEGIN
	SET @DocStatus = '';
	SET @BaseType = '';
	SET @NilaiPerItem = 0;
	SET @QtyRetur = 0;
	
	SELECT `Status` INTO @DocStatus FROM returpenjualanheader WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	SELECT BaseType,HargaNet, Qty INTO @BaseType,@NilaiPerItem, @QtyRetur FROM returpenjualandetail WHERE NoTransaksi = OLD.NoTransaksi and RecordOwnerID = OLD.RecordOwnerID;
	
	IF @BaseType = 'ODLN' THEN
		-- 	Update Retur
		UPDATE deliverynoteheader SET TotalRetur = COALESCE(TotalRetur,0) -  OLD.HargaNet
		WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
		
		
		-- 	Update Line Total
		
		UPDATE deliverynotedetail SET QtyRetur = QtyRetur - @QtyRetur
		WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.BaseLine;
	END IF;
	
	IF @BaseType = 'OINV' THEN
		-- 	Update Retur
		UPDATE fakturpenjualanheader SET TotalRetur = COALESCE(TotalRetur,0) -  OLD.HargaNet
		WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID;
		
		
		-- 	Update Line Total
		
		UPDATE fakturpenjualandetail SET QtyRetur = QtyRetur- @QtyRetur
		WHERE NoTransaksi = OLD.BaseReff AND RecordOwnerID = OLD.RecordOwnerID AND KodeItem = OLD.KodeItem AND NoUrut = OLD.BaseLine;
	END IF;

	
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_updateStatusLampu` AFTER INSERT ON `tableorderheader` FOR EACH ROW BEGIN
    -- Jika order yang dimasukkan adalah pesanan / booking aktif (Status != 0)
    -- ATAU jika itu adalah checkout (Status = 0 / -1) pastikan tidak ada order lain yang sedang berjalan 'O'
    IF NEW.Status != 0 THEN
	    UPDATE titiklampu set `Status` = NEW.`Status` WHERE id = NEW.tableid and RecordOwnerID = NEW.RecordOwnerID;
    ELSE
        -- Jika order baru ini statusnya 0 (Booking yang belum mulai misal dari +Layanan)
        -- Kita hanya set titiklampu jadi 0 jika memang meja sedang kosong (tidak ada order 'O' atau '-1')
        IF (SELECT COUNT(*) FROM tableorderheader WHERE tableid = NEW.tableid AND RecordOwnerID = NEW.RecordOwnerID AND (DocumentStatus = 'O' OR Status = -1) AND NoTransaksi != NEW.NoTransaksi) = 0 THEN
            UPDATE titiklampu set `Status` = NEW.`Status` WHERE id = NEW.tableid and RecordOwnerID = NEW.RecordOwnerID;
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trg_updateStatusLampu_co` AFTER UPDATE ON `tableorderheader` FOR EACH ROW BEGIN
    IF NEW.Status != 0 THEN
	    UPDATE titiklampu set `Status` = NEW.`Status` WHERE id = NEW.tableid and RecordOwnerID = NEW.RecordOwnerID;
    ELSE
        -- Sama seperti di atas, jika order di-update menjadi 0 atau checkout, kita pastikan tidak ada antrian order lain yang sedang 'O'
        IF (SELECT COUNT(*) FROM tableorderheader WHERE tableid = NEW.tableid AND RecordOwnerID = NEW.RecordOwnerID AND (DocumentStatus = 'O' OR Status = -1) AND NoTransaksi != NEW.NoTransaksi) = 0 THEN
            UPDATE titiklampu set `Status` = NEW.`Status` WHERE id = NEW.tableid and RecordOwnerID = NEW.RecordOwnerID;
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`XPOS_Database`@`%`*/ /*!50003 TRIGGER `trgadd` AFTER INSERT ON `testtable` FOR EACH ROW BEGIN
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @PostAcct = 0;
	SET @Prefix = '';
	SET @NumberLength = 6;
	SET @RunningNumber = 1;
	SET @NoTransaksi = '';
	

	SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE RecordOwnerID = 'CL0002' and DocumentID = 'KOUT';
	SELECT COUNT(*) INTO @RunningNumber FROM headerjurnal WHERE RecordOwnerID collate utf8mb4_general_ci = 'CL0001' and LEFT(NoTransaksi collate utf8mb4_general_ci,LENGTH(CONCAT(@Period,@Prefix))) = CONCAT(@Period,@Prefix);
	SET @NomorTransaksi = CONCAT(CONCAT(@Period,@Prefix), LPAD(@RunningNumber +1, @NumberLength, '0'));
	
	IF NEW.Name = 'AJI' THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = @NomorTransaksi;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'xpos'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` FUNCTION `fn_Generate_DocNum`(`DocType` varchar(55),`TableName` varchar(55),`ColomnName` varchar(55),`RecordOwnerID` varchar(55)) RETURNS text CHARSET latin1 COLLATE latin1_swedish_ci
BEGIN
	DECLARE NewDocNumber VARCHAR(255);
	DECLARE LastDocNumber VARCHAR(255);
	DECLARE RunningNumber INT;
	SET @Period = DATE_FORMAT(NOW(), '%Y%m');
	SET @Prefix = '';
	SET @NumberLength = 6;
	
	SELECT prefix, NumberLength INTO @Prefix, @NumberLength FROM documentnumbering WHERE DocumentID = DocType;
	
	SET @sql = CONCAT(
			'SELECT MAX(', ColomnName, ') INTO @LastDocNumber FROM ', TableName,
			' WHERE RecordOwnerID = ? AND LEFT(', ColomnName, ' collate utf8mb4_general_ci, LENGTH(CONCAT(?,?))) = CONCAT(?,?)'
	);
	
	
	
	RETURN @sql;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `fsp_read_penjualan_konsinyasi`(IN `TglAwal` date,IN `TglAkhir` date,IN `RecordOwnerID` varchar(55),IN `Vendor` varchar(55))
BEGIN
	SELECT 
		a.NoTransaksi,
		b.KodeSupplier,
		c.NamaSupplier,
		a.KodeItem,
		b.NamaItem,
		a.NoUrut,
		SUM(a.Qty) Qty,
		SUM(a.Qty * a.HargaPokokPenjualan) TotalTransaksi,
		COALESCE(SUM(d.TotalPembayaran),0) TotalPembayaran
	FROM fakturpenjualandetail a
	LEFT JOIN fakturpenjualanheader a1 on a.NoTransaksi = a1.NoTransaksi and a.RecordOwnerID = a1.RecordOwnerID
	LEFT JOIN itemmaster b on a.KodeItem = b.KodeItem and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN supplier c on b.KodeSupplier = c.KodeSupplier and b.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN pembayarankonsinyasidetail d on a.NoTransaksi = d.BaseReff and a.NoUrut = d.BaseLine and a.RecordOwnerID = d.RecordOwnerID
	WHERE b.TypeItem = 5
	and DATE(a1.TglTransaksi) BETWEEN TglAwal AND TglAkhir
	and a.RecordOwnerID = RecordOwnerID
	and (b.KodeSupplier = Vendor)
	GROUP BY a.NoTransaksi, b.KodeSupplier,c.NamaSupplier, a.KodeItem, b.NamaItem,a.NoUrut
	HAVING SUM(a.Qty * a.HargaPokokPenjualan) - COALESCE(SUM(d.TotalPembayaran),0) > 0;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `GenerateDocNumber`(IN DocType VARCHAR(55),
    IN TableName VARCHAR(55),
    IN ColumnName VARCHAR(55),
    IN RecordOwnerID VARCHAR(55),
    OUT NewDocNumber VARCHAR(255))
BEGIN
    DECLARE LastDocNumber VARCHAR(255);
    DECLARE RunningNumber INT;
		SET @Period = DATE_FORMAT(NOW(), '%Y%m');
    -- Prepare and execute a dynamic SQL statement to get the last document number
    SET @sql = CONCAT(
        'SELECT MAX(', ColumnName, ') INTO @LastDocNumber FROM ', TableName,
        ' WHERE RecordOwnerID = ? AND LEFT(',ColumnName,', LENGTH(',@Period,')) = ', @Period
    );
    -- Execute the query and assign the result to LastDocNumber
    PREPARE stmt FROM @sql;
    SET @RecordOwnerID = RecordOwnerID;
    EXECUTE stmt USING @RecordOwnerID;
    DEALLOCATE PREPARE stmt;
    -- Assign the result of dynamic SQL to LastDocNumber
    SET LastDocNumber = @LastDocNumber;
    -- Check if LastDocNumber is NULL, meaning no previous record exists
    IF LastDocNumber IS NULL THEN
        SET RunningNumber = 1;
    ELSE
        -- Extract the running number and increment it
        SET RunningNumber = CAST(SUBSTRING(LastDocNumber, -4) AS UNSIGNED) + 1;
    END IF;
    -- Generate the new document number in the format of DocType-0000 where 0000 is the running number
    SET NewDocNumber = CONCAT(@Period, '', LPAD(RunningNumber, 6, '0'));
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `InsertNewCoA`(IN KodePartner VARCHAR(55))
BEGIN
	#Routine body goes here...
	DECLARE done INT DEFAULT 0;
		
	DECLARE KelompokLama INT;
	DECLARE KelompokBaru INT;
	DECLARE NamaKelompok VARCHAR(100);
	DECLARE cursor_records CURSOR FOR SELECT TempKelompok, id  FROM kelompokrekening WHERE RecordOwnerID = KodePartner;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cursor_records;
	read_loop: LOOP
			FETCH cursor_records INTO KelompokLama, KelompokBaru;
			IF done THEN
					LEAVE read_loop;
			END IF;
			-- Process each record here
			INSERT INTO rekeningakutansi
			SELECT 
				KodeRekening,	NamaRekening,	KelompokBaru,	`Level`,	Jenis,	KodeRekeningInduk,	
				0,	0,	KodePartner,	NOW(),	NOW()
			FROM rekeningakutansi WHERE RecordOwnerID = 'CL0001' AND KodeKelompok = KelompokLama;
	END LOOP;
	CLOSE cursor_records;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rps_ReturPenjualan`(IN `TglAwal` date,IN `TglAkhir` date,IN `Pelanggan` varchar(50), IN RecordOwnerID VARCHAR(50))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		a.KodePelanggan,
		d.NamaPelanggan,
		b.KodeItem,
		c.NamaItem,
		c.HargaPokokPenjualan,
		b.Qty,
		b.Harga,
		b.HargaNet,
		b.VatPercent,
		CASE WHEN a.`Status` = 'O' THEN 'OPEN' ELSE
			CASE WHEN a.`Status` = 'C' THEN 'CLOSE' ELSE 
				CASE WHEN a.`Status` = 'D' THEN 'CANCEL' ELSE 
					CASE WHEN a.`Status` = 'T' THEN 'DRAFT' ELSE '' END
				END
			END
		END StatusDocument,
		a.TotalTransaksi,
		a.TotalPembelian,
		a.Pajak,
		CONCAT(a.KodePelanggan,'-', d.NamaPelanggan) ConcatPelanggan
	FROM returpenjualanheader a
	LEFT JOIN returpenjualandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN itemmaster c on b.KodeItem = c.KodeItem and b.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN pelanggan d on a.KodePelanggan = d.KodePelanggan and a.RecordOwnerID = d.RecordOwnerID
	WHERE DATE(a.TglTransaksi) BETWEEN TglAwal AND TglAkhir
	AND (a.KodePelanggan = Pelanggan OR Pelanggan = '')
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_laporan_kartu_stock`(IN `TglAwal` date,IN `TglAkhir` date,IN `KodeItem` varchar(55),IN `RecordOwnerID` varchar(55))
BEGIN
	SELECT * FROM (
		SELECT
			0 											AS LineNum,
			''											AS NoTransaksi,
			NOW()										AS Tanggal,
			'SALDO AWAL' 						AS KodeItem,
			'SALDO AWAL'						AS NamaItem,
			SUM(a.QtyIN - a.QtyOut)	AS QtyIN,
			0												AS QtyOut,
			'SALDO AWAL PER'				AS Keterangan
		FROM itemmovinghistory a
		where DATE(a.TglPencatatan) < TglAwal
		and a.RecordOwnerID = RecordOwnerID
		and (a.KodeItem = KodeItem or KodeItem = '' )
		UNION ALL
		SELECT 
			1 											AS LineNum,
			a.BaseReff							AS NoTransaksi,
			a.TglPencatatan					AS Tanggal,
			a.KodeItem 							AS KodeItem,
			b.NamaItem							AS NamaItem,
			a.QtyIN									AS QtyIN,
			a.QtyOut								AS QtyOut,
			c.NamaDokumen						AS Keterangan
		FROM itemmovinghistory a
		LEFT JOIN itemmaster b on a.KodeItem = b.KodeItem and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN documenttype c on a.BaseType = c.KodeDokumen
		WHERE DATE(a.TglPencatatan) BETWEEN TglAwal AND TglAkhir
		AND a.RecordOwnerID = RecordOwnerID
		and (a.KodeItem = KodeItem or KodeItem = '' )
	)x order by x.LineNum,x.Tanggal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_NeracaSaldo`(IN `Periode` varchar(10),IN `Level` int)
BEGIN
	SELECT 
		x.Periode,
		x.KodeRekening,
		x.NamaRekening,
		x.KodeKelompok,
		x.NamaKelompok,
		x.`Level`,
		x.Posisi,
		CONCAT(COALESCE(x.RekeningInduk, x.NamaRekening)) RekeningInduk,
		SUM(x.SaldoAwal) SaldoAwal ,
		SUM(x.MutasiDebet) MutasiDebet,
		SUM(x.MutasiKredit) MutasiKredit,
		SUM(x.SaldoAkhir) SaldoAkhir
	FROM (
		SELECT 
			Periode Periode,
			a.KodeRekening,
			b.NamaRekening,
			b.KodeKelompok,
			c.NamaKelompok,
			b.`Level`,
			c.Posisi,
			d.NamaRekening RekeningInduk,
			SUM(a.SaldoAkhir) SaldoAwal,
			0 MutasiDebet,
			0 MutasiKredit,
			0 SaldoAkhir
		FROM saldorekening a
		LEFT JOIN rekeningakutansi b on a.KodeRekening = b.KodeRekening and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN kelompokrekening c on b.KodeKelompok = c.id and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN rekeningakutansi d on b.KodeRekeningInduk = d.KodeRekening
		WHERE CAST(CONCAT(a.Periode,'01') AS DATE) < CAST(CONCAT(Periode,'01') AS DATE)
		AND a.Periode <> ''
		AND c.NeracaLR = 1
		GROUP BY a.KodeRekening, c.NamaKelompok
		UNION ALL
		SELECT 
			Periode Periode,
			a.KodeRekening,
			b.NamaRekening,
			b.KodeKelompok,
			c.NamaKelompok,
			b.`Level`,
			c.Posisi,
			d.NamaRekening AS RekeningInduk,
			0 SaldoAwal,
			SUM(a.MutasiDebet) MutasiDebet,
			SUM(a.MutasiKredit) MutasiKredit,
			SUM(a.SaldoAkhir) SaldoAkhir
		FROM saldorekening a
		LEFT JOIN rekeningakutansi b on a.KodeRekening = b.KodeRekening and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN kelompokrekening c on b.KodeKelompok = c.id and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN rekeningakutansi d on b.KodeRekeningInduk = d.KodeRekening
		WHERE a.Periode = Periode
		AND a.Periode <> ''
		AND c.NeracaLR = 1
		GROUP BY a.KodeRekening, c.NamaKelompok
	)x
	WHERE x.`Level` = Level
	GROUP BY x.Periode, x.KodeRekening, x.`Level`,x.RekeningInduk
	ORDER BY x.Periode, x.KodeKelompok, x.KodeRekening;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_PembayaranPembelian`(IN `TglAwal` date,IN `TglAkhir` date,IN `RecordOwnerID` varchar(55))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		d.NamaMetodePembayaran,
		a.KodeSupplier,
		c.NamaSupplier,
		a.NoReff,
		b.BaseReff,
		b.TotalPembayaran,
		a.CreatedBy
	FROM pembayaranheader a
	LEFT JOIN pembayarandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN supplier c on a.KodeSupplier = c.KodeSupplier and a.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN metodepembayaran d on b.KodeMetodePembayaran = d.id and b.RecordOwnerID = d.RecordOwnerID
	WHERE a.TglTransaksi BETWEEN TglAwal AND TglAkhir
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_PembayaranPenjualan`(IN `TglAwal` date,IN `TglAkhir` date, IN RecordOwnerID VARCHAR(55))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		d.NamaMetodePembayaran,
		a.KodePelanggan,
		c.NamaPelanggan,
		a.NoReff,
		b.BaseReff,
		b.TotalPembayaran,
		a.CreatedBy
	FROM pembayaranpenjualanheader a
	LEFT JOIN pembayaranpenjualandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN pelanggan c on a.KodePelanggan = c.KodePelanggan and a.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN metodepembayaran d on b.KodeMetodePembayaran = d.id and b.RecordOwnerID = d.RecordOwnerID
	WHERE a.TglTransaksi BETWEEN TglAwal AND TglAkhir
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_Pembelian`(IN `TglAwal` date,IN `TglAkhir` date,IN `Supplier` varchar(50), IN RecordOwnerID VARCHAR(50), IN StatusTransaksi VARCHAR(50))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		a.TglJatuhTempo,
		a.KodeSupplier,
		d.NamaSupplier,
		a.Termin,
		b.KodeItem,
		c.NamaItem,
		c.HargaPokokPenjualan,
		b.Qty,
		b.Harga,
		b.HargaNet,
		b.VatPercent,
		b.Discount,
		CASE WHEN a.`Status` = 'O' THEN 'OPEN' ELSE
			CASE WHEN a.`Status` = 'C' THEN 'CLOSE' ELSE 
				CASE WHEN a.`Status` = 'D' THEN 'CANCEL' ELSE 
					CASE WHEN a.`Status` = 'T' THEN 'DRAFT' ELSE '' END
				END
			END
		END StatusDocument,
		a.TotalTransaksi,
		a.TotalPembelian,
		a.Pajak,
		a.Potongan,
		a.TotalPembayaran,
		CONCAT(a.KodeSupplier,'-', d.NamaSupplier) ConcatSupplier
	FROM fakturpembelianheader a
	LEFT JOIN fakturpembeliandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN itemmaster c on b.KodeItem = c.KodeItem and b.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN supplier d on a.KodeSupplier = d.KodeSupplier and a.RecordOwnerID = d.RecordOwnerID
	WHERE DATE(a.TglTransaksi) BETWEEN TglAwal AND TglAkhir
	AND (a.KodeSupplier = Supplier OR Supplier = '')
	AND (a.`Status` = StatusTransaksi OR StatusTransaksi = '')
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_Penjualan`(IN `TglAwal` date,IN `TglAkhir` date,IN `Pelanggan` varchar(50), IN RecordOwnerID VARCHAR(50), IN StatusTransaksi VARCHAR(50))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		a.TglJatuhTempo,
		a.KodePelanggan,
		d.NamaPelanggan,
		a.Termin,
		b.KodeItem,
		c.NamaItem,
		c.HargaPokokPenjualan,
		b.Qty,
		b.Harga,
		b.HargaNet,
		b.VatPercent,
		b.Discount,
		e.NamaMetodePembayaran,
		a.ReffPembayaran,
		CASE WHEN a.`Status` = 'O' THEN 'OPEN' ELSE
			CASE WHEN a.`Status` = 'C' THEN 'CLOSE' ELSE 
				CASE WHEN a.`Status` = 'D' THEN 'CANCEL' ELSE 
					CASE WHEN a.`Status` = 'T' THEN 'DRAFT' ELSE '' END
				END
			END
		END StatusDocument,
		a.TotalTransaksi,
		a.TotalPembelian,
		a.Pajak,
		a.Potongan,
		a.TotalPembayaran,
		CONCAT(a.KodePelanggan,'-', d.NamaPelanggan) ConcatPelanggan,
		a.KodeSales,
		f.NamaSales,
		CONCAT(a.KodeSales,'-', f.NamaSales) ConcatSales
	FROM fakturpenjualanheader a
	LEFT JOIN fakturpenjualandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN itemmaster c on b.KodeItem = c.KodeItem and b.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN pelanggan d on a.KodePelanggan = d.KodePelanggan and a.RecordOwnerID = d.RecordOwnerID
	LEFT JOIN metodepembayaran e on a.MetodeBayar = e.id and a.RecordOwnerID = e.RecordOwnerID
	LEFT JOIN sales f on a.KodeSales = f.KodeSales and a.RecordOwnerID = f.RecordOwnerID
	WHERE DATE(a.TglTransaksi) BETWEEN TglAwal AND TglAkhir
	AND (a.KodePelanggan = Pelanggan OR Pelanggan = '')
	AND (a.`Status` = StatusTransaksi OR StatusTransaksi = '')
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_perbandinganhargasupplier`(IN `TglAwal` DATE,
    IN `TglAkhir` DATE,
    IN `RecordOwnerID` VARCHAR(55))
BEGIN
    SELECT 
        a.KodeItem,
        a.NamaItem,
        b.KodeSupplier,
        b.NamaSupplier,
        b.Harga
    FROM itemmaster a
    LEFT JOIN (
        SELECT 
            CONVERT(x.KodeSupplier USING utf8mb4) AS KodeSupplier,
            x.NamaSupplier,
            CONVERT(y.KodeItem USING utf8mb4) AS KodeItem,
            y.Harga,
            CONVERT(y.RecordOwnerID USING utf8mb4) AS RecordOwnerID
        FROM supplier x
        LEFT JOIN (
            SELECT 
                CONVERT(y1.KodeSupplier USING utf8mb4) AS KodeSupplier,
                CONVERT(x1.KodeItem USING utf8mb4) AS KodeItem,
                CONVERT(x1.RecordOwnerID USING utf8mb4) AS RecordOwnerID,
                AVG(x1.Harga) AS Harga
            FROM fakturpembeliandetail x1
            LEFT JOIN fakturpembelianheader y1 
                ON x1.NoTransaksi = y1.NoTransaksi 
                AND CONVERT(x1.RecordOwnerID USING utf8mb4) = CONVERT(y1.RecordOwnerID USING utf8mb4)
            WHERE y1.TglTransaksi BETWEEN TglAwal AND TglAkhir
            GROUP BY y1.KodeSupplier, x1.KodeItem, x1.RecordOwnerID
        ) y 
        ON CONVERT(x.KodeSupplier USING utf8mb4) = y.KodeSupplier 
        AND CONVERT(x.RecordOwnerID USING utf8mb4) = y.RecordOwnerID
    ) b 
    ON CONVERT(a.KodeItem USING utf8mb4) = b.KodeItem 
    AND CONVERT(a.RecordOwnerID USING utf8mb4) = b.RecordOwnerID
    WHERE b.KodeSupplier IS NOT NULL 
    AND CONVERT(a.RecordOwnerID USING utf8mb4) = CONVERT(RecordOwnerID USING utf8mb4)
    ORDER BY a.KodeItem;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_ProfitandLost`(IN `Period` varchar(6),IN `RecordOwnerID` varchar(55))
BEGIN
	SELECT 
		x.KodeRekening,
		x.NamaRekening,
		x.`Level`,
		x.KodeKelompok,
		x.NamaKelompok,
		x.Posisi,
		x.FooterLaporan,
		CASE WHEN COALESCE(x.KodeRekeningInduk,'') = '' THEN x.KodeRekening ELSE x.KodeRekeningInduk END AS KodeRekeningInduk,
		CASE WHEN COALESCE(x.NamaRekeningInduk,'') = '' THEN x.NamaRekening ELSE x.NamaRekeningInduk END AS NamaRekeningInduk,
		SUM(x.Nilai) Nilai,
		SUM(x.Nilai_YTD) YTD
	FROM (
		SELECT 
				a.KodeRekening,
				b.NamaRekening,
				b.`Level`,
				b.KodeKelompok,
				c.NamaKelompok,
				c.Posisi,
				c.FooterLaporan,
				a.RecordOwnerID,
				b.KodeRekeningInduk,
				b1.NamaRekening NamaRekeningInduk,
				(CASE WHEN c.Posisi = 1 THEN MutasiKredit - MutasiDebet ELSE SaldoAkhir END) Nilai,
				0 Nilai_YTD
		FROM saldorekening a
		LEFT JOIN rekeningakutansi b on a.KodeRekening = b.KodeRekening and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN rekeningakutansi b1 on b.KodeRekeningInduk = b1.KodeRekening and b.RecordOwnerID = b1.RecordOwnerID
		LEFT JOIN kelompokrekening c on b.KodeKelompok = c.id and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN (
			SELECT 
				cls.KodeRekening,
				SUM(CASE WHEN cls.Posisi = 1 THEN cls.Debit - Kredit ELSE cls.Debit + cls.Kredit end) Saldo
			FROM (
				SELECT 
					x.NoTransaksi,
					x.KodeRekening,
					z.NamaRekening,
					z1.Posisi,
					x.DK,
					CASE WHEN x.DK = 1 THEN x.Jumlah ELSE 0 END AS Debit,
					CASE WHEN x.DK = 2 THEN x.Jumlah ELSE 0 END AS Kredit
				FROM detailjurnal x
				LEFT JOIN headerjurnal y on x.NoTransaksi = y.NoTransaksi and x.RecordOwnerID = y.RecordOwnerID
				LEFT JOIN rekeningakutansi z on x.KodeRekening = z.KodeRekening and z.RecordOwnerID = z.RecordOwnerID
				LEFT JOIN kelompokrekening z1 on z.KodeKelompok = z1.id and z.RecordOwnerID = z1.RecordOwnerID
				WHERE CAST(CONCAT(y.Periode,'01') AS DATE) = CAST(CONCAT(Period,'01') AS DATE)
				AND x.KodeTransaksi = -3
			) cls
			GROUP BY cls.KodeRekening
		) d ON a.KodeRekening = d.KodeRekening
		WHERE CAST(CONCAT(a.Periode,'01') AS DATE) = CAST(CONCAT(Period,'01') AS DATE) AND c.Kelompok = 2
		and a.RecordOwnerID = RecordOwnerID
		UNION ALL
		SELECT 
				a.KodeRekening,
				b.NamaRekening,
				b.`Level`,
				b.KodeKelompok,
				c.NamaKelompok,
				c.Posisi,
				c.FooterLaporan,
				a.RecordOwnerID,
				b.KodeRekeningInduk,
				b1.NamaRekening NamaRekeningInduk,
				0 Nilai,
				SUM(CASE WHEN c.Posisi = 1 THEN MutasiKredit -MutasiDebet ELSE SaldoAkhir END) Nilai_YTD
		FROM saldorekening a
		LEFT JOIN rekeningakutansi b on a.KodeRekening = b.KodeRekening and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN rekeningakutansi b1 on b.KodeRekeningInduk = b1.KodeRekening and b.RecordOwnerID = b1.RecordOwnerID
		LEFT JOIN kelompokrekening c on b.KodeKelompok = c.id and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN (
			SELECT 
				cls.KodeRekening,
				SUM(CASE WHEN cls.Posisi = 1 THEN cls.Debit - Kredit ELSE cls.Debit + cls.Kredit end) Saldo
			FROM (
				SELECT 
					x.NoTransaksi,
					x.KodeRekening,
					z.NamaRekening,
					z1.Posisi,
					x.DK,
					CASE WHEN x.DK = 1 THEN x.Jumlah ELSE 0 END AS Debit,
					CASE WHEN x.DK = 2 THEN x.Jumlah ELSE 0 END AS Kredit
				FROM detailjurnal x
				LEFT JOIN headerjurnal y on x.NoTransaksi = y.NoTransaksi
				LEFT JOIN rekeningakutansi z on x.KodeRekening = z.KodeRekening and x.RecordOwnerID = z.RecordOwnerID
				LEFT JOIN kelompokrekening z1 on z.KodeKelompok = z1.id and z.RecordOwnerID = z1.RecordOwnerID
				WHERE CAST(CONCAT(y.Periode,'01') AS DATE) BETWEEN CAST(CONCAT(LEFT(Period,4),'01','01') AS DATE) and CAST(CONCAT(Period,'01') AS DATE)
				AND x.KodeTransaksi = -3
			) cls
			GROUP BY cls.KodeRekening
		) d ON a.KodeRekening = d.KodeRekening
		WHERE CAST(CONCAT(a.Periode,'01') AS DATE) BETWEEN CAST(CONCAT(LEFT(Period,4),'01','01') AS DATE) AND CAST(CONCAT(Period,'01') AS DATE) AND c.Kelompok = 2
		and a.RecordOwnerID = RecordOwnerID
		GROUP BY A.KodeRekening
	)x
	GROUP BY x.KodeRekening;
		
	-- 	SELECT * FROM saldorekening
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_ProfitandLostPerItem`(IN `Period` varchar(6),IN `RecordOwnerID` varchar(55),IN `ShowZero` int)
BEGIN
	SELECT 
		'1. Penjualan' AS Transaksi,
		a.KodeItem,
		a.NamaItem,
		c.NamaJenis,
		a.RecordOwnerID,
		a.HargaPokokPenjualan,
		COALESCE(b.Qty,0) Terjual,
		COALESCE(b.Harga,0) HargaJual,
		a.HargaPokokPenjualan * COALESCE(b.Qty,0) NilaiInventory,
		COALESCE(b.Harga,0) * COALESCE(b.Qty,0) NilaiPenjualan
	FROM itemmaster a
	LEFT JOIN (
		SELECT 
			x.KodeItem,
			x.RecordOwnerID,
			SUM(x.Qty) Qty,
			AVG(x.Harga) Harga
		FROM fakturpenjualandetail x
		LEFT JOIN fakturpenjualanheader y on x.NoTransaksi = y.NoTransaksi and x.RecordOwnerID = y.RecordOwnerID
		WHERE y.Periode = Period
		and x.RecordOwnerID = RecordOwnerID
		GROUP BY x.KodeItem, x.RecordOwnerID
	) b on a.KodeItem = b.KodeItem and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN jenisitem c on a.KodeJenisItem = c.KodeJenis and a.RecordOwnerID = c.RecordOwnerID
	WHERE a.RecordOwnerID = RecordOwnerID
	and COALESCE(b.Qty,0) > CASE WHEN ShowZero = 0 THEN 0 else -1 END
	UNION ALL 
	SELECT 
		'2. Biaya' AS Transaksi,
		a.KodeRekening,
		c.NamaRekening,
		'Biaya',
		a.RecordOwnerID,
		a.TotalTransaksi,
		0 AS Terjual,
		0 AS HargaJual,
		a.TotalTransaksi * -1 AS NilaiInventory,
		0 NilaiPenjualan
	FROM biayadetail a
	LEFT JOIN biayaheader b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN rekeningakutansi c on a.KodeRekening = c.KodeRekening and a.RecordOwnerID = c.RecordOwnerID
	WHERE b.Periode = Period
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_ReturPembelian`(IN `TglAwal` date,IN `TglAkhir` date,IN `Supplier` varchar(50), IN RecordOwnerID VARCHAR(50))
BEGIN
	SELECT 
		a.NoTransaksi,
		a.TglTransaksi,
		a.KodeSupplier,
		d.NamaSupplier,
		b.KodeItem,
		c.NamaItem,
		c.HargaPokokPenjualan,
		b.Qty,
		b.Harga,
		b.HargaNet,
		b.VatPercent,
		CASE WHEN a.`Status` = 'O' THEN 'OPEN' ELSE
			CASE WHEN a.`Status` = 'C' THEN 'CLOSE' ELSE 
				CASE WHEN a.`Status` = 'D' THEN 'CANCEL' ELSE 
					CASE WHEN a.`Status` = 'T' THEN 'DRAFT' ELSE '' END
				END
			END
		END StatusDocument,
		a.TotalTransaksi,
		a.TotalPembelian,
		a.Pajak,
		a.Potongan,
		CONCAT(a.KodeSupplier,'-', d.NamaSupplier) ConcatSupplier
	FROM returpembelianheader a
	LEFT JOIN returpembeliandetail b on a.NoTransaksi = b.NoTransaksi and a.RecordOwnerID = b.RecordOwnerID
	LEFT JOIN itemmaster c on b.KodeItem = c.KodeItem and b.RecordOwnerID = c.RecordOwnerID
	LEFT JOIN supplier d on a.KodeSupplier = d.KodeSupplier and a.RecordOwnerID = d.RecordOwnerID
	WHERE DATE(a.TglTransaksi) BETWEEN TglAwal AND TglAkhir
	AND (a.KodeSupplier = Supplier OR Supplier = '')
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_SaldoRekening`(IN `KelompokRekening` VARCHAR(55), IN RecordOwnerID VARCHAR(55))
BEGIN
	SELECT 
		a.KodeRekening,
		a.NamaRekening,
		b.NamaKelompok,
		a.`Level`,
		a.SaldoBase
	FROM rekeningakutansi a
	LEFT JOIN kelompokrekening b on a.KodeKelompok = b.id and a.RecordOwnerID = b.RecordOwnerID
	WHERE (a.KodeKelompok = KelompokRekening or KelompokRekening = '')
	AND a.RecordOwnerID = RecordOwnerID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `rsp_SaldoStock`(IN `KodeGudang` varchar(50), IN RecordOwnerID VARCHAR(50),IN `ShowZero` int)
BEGIN
	IF ShowZero = 1 THEN
		SELECT 
			a.KodeItem,
			a.NamaItem,
			d.NamaJenis,
			a.HargaBeliTerakhir,
			a.HargaPokokPenjualan,
			COALESCE(b.KodeGudang, a.KodeGudang) KodeGudang, 
			COALESCE(c.NamaGudang, c1.NamaGudang) NamaGudang,
			COALESCE(b.Qty,0) Qty,
			a.Satuan,
			e.NamaSatuan
		FROM itemmaster a
		LEFT JOIN itemwarehouses b on a.KodeItem = b.KodeItem and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN gudang c on b.KodeGudang = c.KodeGudang and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN gudang c1 on a.KodeGudang = c1.KodeGudang and a.RecordOwnerID = c1.RecordOwnerID
		LEFT JOIN jenisitem d on a.KodeJenisItem = d.KodeJenis and a.RecordOwnerID = d.RecordOwnerID
		LEFT JOIN satuan e on a.Satuan = e.KodeSatuan
		WHERE (COALESCE(b.KodeGudang, a.KodeGudang) = KodeGudang or KodeGudang = '')
		AND a.RecordOwnerID = RecordOwnerID;
	ELSE 
		SELECT 
			a.KodeItem,
			a.NamaItem,
			d.NamaJenis,
			a.HargaBeliTerakhir,
			a.HargaPokokPenjualan,
			COALESCE(b.KodeGudang, a.KodeGudang) KodeGudang, 
			COALESCE(c.NamaGudang, c1.NamaGudang) NamaGudang,
			COALESCE(b.Qty,0) Qty,
			a.Satuan,
			e.NamaSatuan
		FROM itemmaster a
		LEFT JOIN itemwarehouses b on a.KodeItem = b.KodeItem and a.RecordOwnerID = b.RecordOwnerID
		LEFT JOIN gudang c on b.KodeGudang = c.KodeGudang and b.RecordOwnerID = c.RecordOwnerID
		LEFT JOIN gudang c1 on a.KodeGudang = c1.KodeGudang and a.RecordOwnerID = c1.RecordOwnerID
		LEFT JOIN jenisitem d on a.KodeJenisItem = d.KodeJenis and a.RecordOwnerID = d.RecordOwnerID
		LEFT JOIN satuan e on a.Satuan = e.KodeSatuan
		WHERE (COALESCE(b.KodeGudang, a.KodeGudang) = KodeGudang or KodeGudang = '')
		AND COALESCE(b.Qty,0) > 0
		AND a.RecordOwnerID = RecordOwnerID;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `setdefaultaccount`()
BEGIN
	update settingaccount SET InvAcctHargaPokokPenjualan ='5110001' WHERE id <> 1;
update settingaccount SET InvAcctPendapatanJual ='4110001' WHERE id <> 1;
update settingaccount SET InvAcctPendapatanJasa ='4110002' WHERE id <> 1;
update settingaccount SET InvAcctPersediaan ='1310001' WHERE id <> 1;
update settingaccount SET InvAcctPendapatanNonInventory ='4110003' WHERE id <> 1;
update settingaccount SET InvAcctPendapatanLainLain ='4110003' WHERE id <> 1;
update settingaccount SET InvAcctPenyesuaiaanStockMasuk ='7111001' WHERE id <> 1;
update settingaccount SET InvAcctPenyesuaiaanStockKeluar ='8111001' WHERE id <> 1;
update settingaccount SET PbAcctPajakPembelian ='1130001' WHERE id <> 1;
update settingaccount SET PbAcctPembayaranTunai ='1110001' WHERE id <> 1;
update settingaccount SET PbAcctPembayaranNonTunai ='1120001' WHERE id <> 1;
update settingaccount SET PbAcctHutang ='2110001' WHERE id <> 1;
update settingaccount SET PbAcctUangMukaPembelian ='1410001' WHERE id <> 1;
update settingaccount SET PjAcctPajakPenjualan ='2130001' WHERE id <> 1;
update settingaccount SET PjAcctPenjualanTunai ='1110001' WHERE id <> 1;
update settingaccount SET PjAcctPenjualanNonTunai ='1120001' WHERE id <> 1;
update settingaccount SET PjAcctPiutang ='1140001' WHERE id <> 1;
update settingaccount SET PjAcctUangMukaPenjualan ='4120001' WHERE id <> 1;
update settingaccount SET PjAcctGoodsInTransit ='1310002' WHERE id <> 1;
update settingaccount SET PjAcctReturnPenjualan ='4120001' WHERE id <> 1;
update settingaccount SET PjAcctPajakHiburan ='2130002' WHERE id <> 1;
update settingaccount SET KnAcctHutangKonsinyasi ='2110001' WHERE id <> 1;
update settingaccount SET KnAcctPembayaranHutang ='1110001' WHERE id <> 1;
update settingaccount SET KnAcctPenerimaanKonsinyasi ='2110002' WHERE id <> 1;
update settingaccount SET OthAcctModal ='3110001' WHERE id <> 1;
update settingaccount SET OthAcctPrive ='3110004' WHERE id <> 1;
update settingaccount SET OthAcctLabaDitahan ='3110002' WHERE id <> 1;
update settingaccount SET OthAcctLabaTahunBerjalan ='3110003' WHERE id <> 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`XPOS_Database`@`%` PROCEDURE `testloop`()
BEGIN
	#Routine body goes here...
	DECLARE done INT DEFAULT 0;
		
	DECLARE KelompokLama INT;
	DECLARE KelompokBaru INT;
	DECLARE NamaKelompok VARCHAR(100);
	DECLARE cursor_records CURSOR FOR SELECT TempKelompok, id  FROM kelompokrekening WHERE RecordOwnerID = 'CL0002';
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cursor_records;
	read_loop: LOOP
			FETCH cursor_records INTO KelompokLama, KelompokBaru;
			IF done THEN
					LEAVE read_loop;
			END IF;
			-- Process each record here
			SELECT *, KelompokBaru FROM rekeningakutansi WHERE RecordOwnerID = 'CL0001' AND KodeKelompok = KelompokLama;
	END LOOP;
	CLOSE cursor_records;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-13  8:07:16
