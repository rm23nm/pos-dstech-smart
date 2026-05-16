<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Assuming Basket 1 is tableid 5 or search by name
$roid = 1; // Need to know the actual roid, but let's find Basket 1
$table = DB::table('titiklampu')->where('NamaTitikLampu', 'like', '%Basket 1%')->first();

if (!$table) {
    echo "Table 'Basket 1' not found.\n";
    // List all tables
    $tables = DB::table('titiklampu')->get();
    foreach ($tables as $t) {
        echo "ID: {$t->id}, Name: {$t->NamaTitikLampu}, Status: {$t->Status}, ROID: {$t->RecordOwnerID}\n";
    }
} else {
    echo "Found Table: ID {$table->id}, Name {$table->NamaTitikLampu}, Status {$table->Status}, ROID {$table->RecordOwnerID}\n";
    
    $now = Carbon::now('Asia/Jakarta');
    echo "Current Server Time (Jakarta): " . $now->toDateTimeString() . "\n";
    
    $orders = DB::table('tableorderheader')
        ->where('tableid', $table->id)
        ->where('RecordOwnerID', $table->RecordOwnerID)
        ->whereIn('DocumentStatus', ['O', 'D'])
        ->get();
        
    echo "Found " . count($orders) . " active/draft orders for this table.\n";
    foreach ($orders as $o) {
        echo "NoTrx: {$o->NoTransaksi}, Status: {$o->Status}, DocStatus: {$o->DocumentStatus}, Start: {$o->JamMulai}, End: {$o->JamSelesai}\n";
    }
}
