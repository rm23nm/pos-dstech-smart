<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$companies = DB::table('company')->get();
$users = DB::table('users')->get();
echo json_encode(['companies' => $companies, 'users' => $users]);
