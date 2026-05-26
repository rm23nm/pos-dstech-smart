<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sourceOwner = 'CL0009';

// Get source methods
$sourceMethods = App\Models\MetodePembayaran::where('RecordOwnerID', $sourceOwner)->get();

if ($sourceMethods->isEmpty()) {
    echo "Source methods not found for $sourceOwner\n";
    exit;
}

// Get all users/tenants
$users = App\Models\User::pluck('RecordOwnerID')->unique();

$copiedTo = [];

foreach($users as $u) {
    if (!$u) continue;
    
    // Check if they have 0 methods
    $count = App\Models\MetodePembayaran::where('RecordOwnerID', $u)->count();
    if ($count == 0) {
        foreach($sourceMethods as $sm) {
            $newMethod = $sm->replicate();
            $newMethod->RecordOwnerID = $u;
            $newMethod->save();
        }
        $copiedTo[] = $u;
    }
}

echo "Successfully copied methods to: " . implode(', ', $copiedTo) . "\n";
