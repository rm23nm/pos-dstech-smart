<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== DocumentNumbering for CL0005 (complete template) ===" . PHP_EOL;
$records = DB::table('documentnumbering')->where('RecordOwnerID', 'CL0005')->get();
foreach ($records as $r) {
    echo "DocumentID: " . $r->DocumentID . ", Prefix: " . $r->prefix . ", Length: " . $r->NumberLength . PHP_EOL;
}

echo PHP_EOL . "=== DocumentNumbering for CL0007 (complete template) ===" . PHP_EOL;
$records = DB::table('documentnumbering')->where('RecordOwnerID', 'CL0007')->get();
foreach ($records as $r) {
    echo "DocumentID: " . $r->DocumentID . ", Prefix: " . $r->prefix . ", Length: " . $r->NumberLength . PHP_EOL;
}
