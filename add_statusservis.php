use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    DB::statement("ALTER TABLE fakturpenjualanheader ADD COLUMN StatusServis INT DEFAULT 0");
    echo "Column StatusServis added successfully.\n";
} catch (\Exception $e) {
    echo "StatusServis might already exist: " . $e->getMessage() . "\n";
}
