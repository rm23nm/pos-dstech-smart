<?php
use Illuminate\Support\Facades\DB;

$res = DB::select('DESCRIBE bengkel_bookings');
echo json_encode($res, JSON_PRETTY_PRINT);
