<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "--- Kendaraan Columns ---\n";
print_r(Schema::getColumnListing('kendaraan'));

echo "\n--- Users ---\n";
$users = DB::table('users')->select('id', 'name', 'RoleID', 'RecordOwnerID')->get();
print_r($users);
