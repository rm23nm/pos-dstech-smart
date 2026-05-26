<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'fulladmin@gmail.com';
$user = App\Models\User::where('email', $email)->first();
echo "User: " . ($user ? $user->email . ' - ' . $user->RecordOwnerID : 'null') . "\n";
if ($user) {
    $company = App\Models\Company::where('KodePartner', $user->RecordOwnerID)->first();
    echo "Company: " . ($company ? $company->KodePartner . ' - ' . $company->NamaPartner : 'null') . "\n";
}
