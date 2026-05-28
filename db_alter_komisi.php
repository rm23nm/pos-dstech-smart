<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Add KomisiMekanik to itemmaster
    $cols = DB::select("SHOW COLUMNS FROM itemmaster LIKE 'KomisiMekanik'");
    if (empty($cols)) {
        DB::statement("ALTER TABLE itemmaster ADD COLUMN KomisiMekanik DOUBLE DEFAULT 0 AFTER HargaJual");
        echo "Added KomisiMekanik to itemmaster.\n";
    } else {
        echo "KomisiMekanik already exists in itemmaster.\n";
    }

    // Add KomisiMekanik to fakturpenjualandetail
    $cols2 = DB::select("SHOW COLUMNS FROM fakturpenjualandetail LIKE 'KomisiMekanik'");
    if (empty($cols2)) {
        DB::statement("ALTER TABLE fakturpenjualandetail ADD COLUMN KomisiMekanik DOUBLE DEFAULT 0 AFTER HargaNet");
        echo "Added KomisiMekanik to fakturpenjualandetail.\n";
    } else {
        echo "KomisiMekanik already exists in fakturpenjualandetail.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
