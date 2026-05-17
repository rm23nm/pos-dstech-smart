<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "--- MEJA COLUMNS ---\n";
print_r(Schema::getColumnListing('meja'));

echo "\n--- TITIKLAMPU COLUMNS ---\n";
print_r(Schema::getColumnListing('titiklampu'));

echo "\n--- TABLEORDERHEADER COLUMNS ---\n";
print_r(Schema::getColumnListing('tableorderheader'));

echo "\n--- SAMPLE MEJA ---\n";
print_r(DB::table('meja')->limit(2)->get()->toArray());

echo "\n--- SAMPLE TITIKLAMPU ---\n";
print_r(DB::table('titiklampu')->limit(2)->get()->toArray());
