<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Recent tableorderfnb (May 2026):\n";
$fnb = DB::table('tableorderfnb')->where('created_at', 'LIKE', '2026-05%')->orderBy('created_at', 'desc')->get();
foreach ($fnb as $item) {
    echo "NoTrans: " . $item->NoTransaksi . " | Item: " . $item->KodeItem . " | Status: " . $item->LineStatus . " | Created: " . $item->created_at . "\n";
}
if (count($fnb) == 0) echo "NONE FOUND\n";
