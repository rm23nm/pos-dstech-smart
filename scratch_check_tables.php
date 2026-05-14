<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

$tables = DB::table('titiklampu')->where('RecordOwnerID', 10)->get();
foreach($tables as $t) {
    echo "ID: {$t->id} | DigitalInput: {$t->DigitalInput} | Name: {$t->NamaTitikLampu} | Status: {$t->Status}\n";
}
