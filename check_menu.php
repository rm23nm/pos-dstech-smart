<?php
use Illuminate\Support\Facades\DB;

$roles = DB::select("SHOW TABLES LIKE '%role%'");
echo json_encode($roles);
