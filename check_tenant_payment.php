<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::pluck('RecordOwnerID')->unique();
echo "MetodePembayaran count per tenant:\n";
foreach($users as $u) {
    if ($u) {
        $count = App\Models\MetodePembayaran::where('RecordOwnerID', $u)->count();
        echo str_pad($u, 20) . " : " . $count . "\n";
    }
}
