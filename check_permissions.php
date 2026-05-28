<?php
use Illuminate\Support\Facades\DB;

$res = [];
$res['permissions'] = DB::table('permission')
    ->whereIn('PermissionName', ['Manajemen Bengkel', 'Service Advisor', 'Laporan Penjualan', 'Keuangan & Laporan'])
    ->get();
    
echo json_encode($res, JSON_PRETTY_PRINT);
