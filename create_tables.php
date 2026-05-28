use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    DB::statement("
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
    ");
    echo "Table 'kendaraan' created.\n";

    DB::statement("
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
    ");
    echo "Table 'mekanik' created.\n";

    try {
        DB::statement("ALTER TABLE fakturpenjualanheader ADD COLUMN PlatNomor VARCHAR(20) DEFAULT ''");
        echo "Column PlatNomor added.\n";
    } catch (\Exception $e) {
        echo "PlatNomor might already exist.\n";
    }

    try {
        DB::statement("ALTER TABLE fakturpenjualanheader ADD COLUMN KodeMekanik VARCHAR(50) DEFAULT ''");
        echo "Column KodeMekanik added.\n";
    } catch (\Exception $e) {
        echo "KodeMekanik might already exist.\n";
    }

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
