<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "<pre>";
try {
    $tables = ['tableorderheader'];
    foreach ($tables as $table) {
        echo "Checking table: $table\n";
        
        // Fix access_kitchen_order_status
        if (Schema::hasColumn($table, 'access_kitchen_order_status')) {
            echo "Renaming access_kitchen_order_status to kitchen_order_status...\n";
            Schema::table($table, function (Blueprint $tableObj) {
                $tableObj->renameColumn('access_kitchen_order_status', 'kitchen_order_status');
            });
            echo "Done.\n";
        } else {
            echo "Column access_kitchen_order_status not found or already renamed.\n";
        }

        // Fix access_call_trigger
        if (Schema::hasColumn($table, 'access_call_trigger')) {
            echo "Renaming access_call_trigger to call_trigger...\n";
            Schema::table($table, function (Blueprint $tableObj) {
                $tableObj->renameColumn('access_call_trigger', 'call_trigger');
            });
            echo "Done.\n";
        } else {
            echo "Column access_call_trigger not found or already renamed.\n";
        }
    }
    echo "\nAll database fixes attempted successfully.";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
echo "</pre>";
