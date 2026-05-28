<?php
use Illuminate\Support\Facades\DB;

$res = [];
$res['company'] = DB::table('company')->where('KodePartner', 'DEMO-BENGKEL-001')->first();
echo json_encode($res, JSON_PRETTY_PRINT);
