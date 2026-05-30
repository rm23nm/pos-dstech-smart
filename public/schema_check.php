<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function dump_table($name) {
    try {
        $res = \DB::select("DESCRIBE $name");
        echo "=== $name ===\n";
        foreach($res as $r) echo $r->Field . " | " . $r->Type . "\n";
    } catch (\Exception $e) {
        echo "=== $name NOT FOUND ===\n";
    }
}

dump_table('fakturpenjualanheader');
dump_table('fakturpenjualandetail');
dump_table('tableorderheader');
