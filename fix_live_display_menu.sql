-- ============================================================
-- SCRIPT PERBAIKAN LIVE: Menu Display & AllowMonitorAntrean
-- Tanggal: 15 Mei 2026
-- Dibuat oleh: Antigravity AI Agent
-- 
-- INSTRUKSI: Jalankan script ini di MySQL live server
-- Aman: Hanya INSERT IGNORE dan UPDATE, tidak menghapus data
-- ============================================================

-- 1. Pastikan kolom AllowMonitorAntrean ada di subscriptionheader
-- (Jika sudah ada, perintah ini akan diabaikan secara otomatis)
ALTER TABLE `subscriptionheader` 
ADD COLUMN IF NOT EXISTS `AllowMonitorAntrean` TINYINT(1) NOT NULL DEFAULT 0;

-- 2. Aktifkan AllowMonitorAntrean untuk paket 2003 (PRO/Monitor)
UPDATE `subscriptionheader` 
SET `AllowMonitorAntrean` = 1 
WHERE `NoTransaksi` = '2003';

-- 3. Cek apakah permission "Display" sudah ada
-- Jika belum ada, insert baru dengan ID yang tepat
INSERT IGNORE INTO `permission` 
    (`id`, `PermissionName`, `Link`, `Icon`, `Level`, `MenuInduk`, `SubMenu`, `Order`, `Status`, `isSuperAdmin`)
SELECT 
    (SELECT COALESCE(MAX(id), 0) + 1 FROM permission p2),
    'Display', '#', 'fas fa-desktop', 1, 0, 1, 26, 1, 0
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `permission` WHERE `PermissionName` = 'Display' AND `Level` = 1
);

-- 4. Tampilkan status setelah update
SELECT 'subscriptionheader paket 2003:' as Info;
SELECT `NoTransaksi`, `AllowMonitorAntrean` FROM `subscriptionheader` WHERE `NoTransaksi` = '2003';

SELECT 'permission Display:' as Info;
SELECT `id`, `PermissionName`, `Level`, `MenuInduk`, `Link` FROM `permission` WHERE `PermissionName` = 'Display';

SELECT 'permission Info Kitchen:' as Info;
SELECT `id`, `PermissionName`, `Level`, `MenuInduk`, `Link` FROM `permission` WHERE `id` = 113;
