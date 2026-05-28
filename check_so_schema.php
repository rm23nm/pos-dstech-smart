<?php
use Illuminate\Support\Facades\DB;
$cols = DB::select('SHOW COLUMNS FROM orderpenjualanheader');
foreach($cols as $c) echo $c->Field . PHP_EOL;
