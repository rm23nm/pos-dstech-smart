import mysql.connector

try:
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="xpos"
    )
    cursor = conn.cursor()

    # Create member_packages table if not exists
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS `member_packages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `KodePaket` varchar(50) NOT NULL,
      `KategoriPaket` varchar(50) NOT NULL,
      `NamaPaket` varchar(255) NOT NULL,
      `LimitBulanan` int(11) NOT NULL DEFAULT '0' COMMENT '0 berarti unlimited',
      `RecordOwnerID` varchar(50) NOT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `KodePaket` (`KodePaket`,`RecordOwnerID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    """)

    # Create customer_memberships table if not exists
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS `customer_memberships` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `KodePelanggan` varchar(50) NOT NULL,
      `KodePaketMember` varchar(50) NOT NULL,
      `ValidFrom` date NOT NULL,
      `ValidUntil` date NOT NULL,
      `MaxPlay` int(11) NOT NULL DEFAULT '0' COMMENT 'Total kuota, 0=unlimited',
      `Played` int(11) NOT NULL DEFAULT '0',
      `RecordOwnerID` varchar(50) NOT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `KodePelanggan` (`KodePelanggan`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    """)

    conn.commit()
    print("Tables created successfully locally!")
    
except Exception as e:
    print(f"Error: {e}")
finally:
    if 'cursor' in locals():
        cursor.close()
    if 'conn' in locals():
        conn.close()
