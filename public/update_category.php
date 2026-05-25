<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::table('company')->where('KodePartner', '999999')->update(['JenisUsaha' => 'Retail']);
    echo "Superadmin JenisUsaha updated to Retail.\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
