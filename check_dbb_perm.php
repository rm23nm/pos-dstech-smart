<?php
use Illuminate\Support\Facades\DB;

$res = DB::table('permission')->where('PermissionName', 'Data Booking Bengkel')->first();
echo json_encode($res, JSON_PRETTY_PRINT);
