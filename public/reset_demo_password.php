<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email1 = 'gor.servicepos@gmail.com';
$email2 = 'gor.servicepos@pos.dstechsmart.com';
$password = bcrypt('12345678');

\Illuminate\Support\Facades\DB::table('users')
    ->whereIn('email', [$email1, $email2])
    ->update(['password' => $password]);

echo "Passwords updated for both demo accounts.";
