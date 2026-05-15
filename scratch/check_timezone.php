<?php
require __DIR__.'/vendor/autoload.php';
use Carbon\Carbon;

echo "System Time: " . date('Y-m-d H:i:s') . "\n";
echo "PHP Default Timezone: " . date_default_timezone_get() . "\n";
echo "Carbon::now(): " . Carbon::now()->toDateTimeString() . "\n";
echo "Carbon::now('Asia/Jakarta'): " . Carbon::now('Asia/Jakarta')->toDateTimeString() . "\n";
echo "Carbon::now('UTC'): " . Carbon::now('UTC')->toDateTimeString() . "\n";
