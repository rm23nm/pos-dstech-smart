<?php
use Illuminate\Support\Facades\DB;
$tables = DB::select('SHOW TABLES');
foreach($tables as $t) {
    echo array_values((array)$t)[0] . "\n";
}
