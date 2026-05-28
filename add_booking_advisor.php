<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "--- bengkel_bookings Columns ---\n";
print_r(Schema::getColumnListing('bengkel_bookings'));

if (!Schema::hasColumn('bengkel_bookings', 'KodeAdvisor')) {
    Schema::table('bengkel_bookings', function (Blueprint $table) {
        $table->string('KodeAdvisor', 50)->nullable()->after('Keluhan');
    });
    echo "Column 'KodeAdvisor' added to 'bengkel_bookings' table.\n";
} else {
    echo "Column 'KodeAdvisor' already exists.\n";
}
