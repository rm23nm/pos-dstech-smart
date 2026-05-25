<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = 'gor.service@gmail.com';
$user = \Illuminate\Support\Facades\DB::table('users')->where('email', $email)->first();

echo "User:\n";
print_r($user);

if ($user) {
    $company = \Illuminate\Support\Facades\DB::table('company')->where('KodePartner', $user->RecordOwnerID)->first();
    echo "\nCompany:\n";
    print_r($company);
}
