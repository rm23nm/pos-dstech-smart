<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Clearing cache...<br>";
try {
    Artisan::call('route:clear');
    echo "Route cache cleared.<br>";
    Artisan::call('view:clear');
    echo "View cache cleared.<br>";
    Artisan::call('config:clear');
    echo "Config cache cleared.<br>";
    Artisan::call('cache:clear');
    echo "Application cache cleared.<br>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
