<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$permissions = DB::table('permission')->where('Level', 1)->pluck('PermissionName');
foreach ($permissions as $p) {
    echo $p . PHP_EOL;
}
