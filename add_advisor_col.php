<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('kendaraan', 'KodeAdvisor')) {
    Schema::table('kendaraan', function (Blueprint $table) {
        $table->string('KodeAdvisor', 50)->nullable()->after('Email');
    });
    echo "Column 'KodeAdvisor' added to 'kendaraan' table.\n";
} else {
    echo "Column 'KodeAdvisor' already exists.\n";
}
