<?php
use Illuminate\Support\Facades\DB;
$cols = DB::select('SHOW COLUMNS FROM subscriptiondetail');
foreach($cols as $c) echo $c->Field . PHP_EOL;
