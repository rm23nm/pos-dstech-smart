<?php
use Illuminate\Support\Facades\DB;

$res = DB::table('company')->select('KodePartner', 'NamaPartner', 'KodePaketLangganan')->get();
echo json_encode($res, JSON_PRETTY_PRINT);
