<?php
use Illuminate\Support\Facades\DB;
$columns = DB::select('SHOW COLUMNS FROM subscriptionheader');
$cols = [];
foreach ($columns as $column) {
    $cols[] = $column->Field;
}
echo implode(", ", $cols) . "\n";
