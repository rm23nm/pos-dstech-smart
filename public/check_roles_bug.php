<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $roles = DB::table('userrole')->where('userid', 1)->get(); // assuming Superadmin is ID 1
    echo json_encode($roles, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo $e->getMessage();
}
