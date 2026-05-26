<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$methods = App\Models\MetodePembayaran::where('RecordOwnerID', 'CL0009')->get();
echo "Metode Pembayaran untuk CL0009:\n";
foreach($methods as $m) {
    echo "- " . $m->NamaMetodePembayaran . " (Tipe: " . $m->TipePembayaran . ", Active: " . $m->Active . ")\n";
}

$methods = App\Models\MetodePembayaran::where('RecordOwnerID', 'CL0001')->get();
echo "\nMetode Pembayaran untuk CL0001:\n";
foreach($methods as $m) {
    echo "- " . $m->NamaMetodePembayaran . " (Tipe: " . $m->TipePembayaran . ", Active: " . $m->Active . ")\n";
}
