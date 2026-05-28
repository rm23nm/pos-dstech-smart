import mysql.connector

try:
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="xpos"
    )
    cursor = conn.cursor()

    # 1. Create kendaraan table
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS kendaraan (
        KodeKendaraan VARCHAR(50) PRIMARY KEY,
        KodePelanggan VARCHAR(50),
        PlatNomor VARCHAR(20),
        Merek VARCHAR(50),
        Tipe VARCHAR(50),
        Tahun INT,
        Warna VARCHAR(30),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
    """)
    print("Table 'kendaraan' created or already exists.")

    # 2. Create mekanik table
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS mekanik (
        KodeMekanik VARCHAR(50) PRIMARY KEY,
        NamaMekanik VARCHAR(100),
        NoHP VARCHAR(20),
        PersentaseKomisi DECIMAL(5,2) DEFAULT 0,
        RecordOwnerID VARCHAR(50) DEFAULT '',
        Status INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
    """)
    print("Table 'mekanik' created or already exists.")

    # 3. Alter transaksipenjualan (Check what the actual table name is)
    # The system uses various headers. Let's add PlatNomor and KodeMekanik to `fakturpenjualanheader` which is typical for PoS.
    # Wait, the POS uses something like `fakturpenjualanheader` or `tableorderheader`.
    try:
        cursor.execute("ALTER TABLE fakturpenjualanheader ADD COLUMN PlatNomor VARCHAR(20) DEFAULT ''")
        print("Column PlatNomor added to fakturpenjualanheader.")
    except Exception as e:
        print("Could not add PlatNomor to fakturpenjualanheader:", e)

    try:
        cursor.execute("ALTER TABLE fakturpenjualanheader ADD COLUMN KodeMekanik VARCHAR(50) DEFAULT ''")
        print("Column KodeMekanik added to fakturpenjualanheader.")
    except Exception as e:
        print("Could not add KodeMekanik to fakturpenjualanheader:", e)

    conn.commit()
except Exception as e:
    print(f"Error: {e}")
finally:
    if 'conn' in locals() and conn.is_connected():
        cursor.close()
        conn.close()
