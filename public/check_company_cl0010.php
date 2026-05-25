<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$company = \Illuminate\Support\Facades\DB::table('company')->where('KodePartner', 'CL0010')->first();
echo json_encode($company, JSON_PRETTY_PRINT);
