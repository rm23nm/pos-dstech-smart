<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = App\Models\Company::where('KodePartner', 'CL0001')->first();
$user = App\Models\User::where('RecordOwnerID', 'CL0001')->first();

echo "Company Name: " . ($company ? $company->NamaPartner : "Not Found") . "\n";
echo "User Name: " . ($user ? $user->name : "Not Found") . "\n";
