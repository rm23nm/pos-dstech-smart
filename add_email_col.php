<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('kendaraan', 'Email')) {
    Schema::table('kendaraan', function (Blueprint $table) {
        $table->string('Email')->nullable()->after('NamaSTNK');
    });
    echo "Column 'Email' added to 'kendaraan' table.\n";
} else {
    echo "Column 'Email' already exists.\n";
}
