ALTER TABLE itemmaster ADD COLUMN isFlashSale CHAR(1) DEFAULT 'N' AFTER Active;
ALTER TABLE itemmaster ADD COLUMN FlashSalePrice DECIMAL(18,2) DEFAULT 0 AFTER isFlashSale;
ALTER TABLE itemmaster ADD COLUMN FlashSaleUntil DATETIME NULL AFTER FlashSalePrice;
ALTER TABLE itemmaster ADD COLUMN isBestSeller CHAR(1) DEFAULT 'N' AFTER FlashSaleUntil;
