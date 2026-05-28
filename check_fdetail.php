<?php
use Illuminate\Support\Facades\DB;
$cols = DB::select('SHOW COLUMNS FROM fakturpenjualandetail');
foreach($cols as $c) echo $c->Field . PHP_EOL;
