<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$migration = '2024_04_18_210656_sales';
if (!DB::table('migrations')->where('migration', $migration)->exists()) {
    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => DB::table('migrations')->max('batch') + 1
    ]);
    echo "Migration $migration registered.\n";
} else {
    echo "Migration $migration already registered.\n";
}
