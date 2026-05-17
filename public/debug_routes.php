<?php
// Enable error reporting to see why it crashes
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h3>Laravel Route Debugger</h3><pre>";

// 1. Check if routes/web.php exists and output its content
$webPath = __DIR__ . '/../routes/web.php';
if (file_exists($webPath)) {
    echo "<b>routes/web.php file size:</b> " . filesize($webPath) . " bytes\n";
    $content = file_get_contents($webPath);
    echo "<b>First 500 chars:</b>\n" . htmlspecialchars(substr($content, 0, 500)) . "\n...\n";
    echo "<b>Search for 'login' in web.php:</b>\n";
    $lines = explode("\n", $content);
    foreach ($lines as $num => $line) {
        if (stripos($line, 'login') !== false) {
            echo "Line " . ($num + 1) . ": " . htmlspecialchars($line) . "\n";
        }
    }
} else {
    echo "routes/web.php NOT found!\n";
}

// 2. Load Laravel and inspect routes
echo "\n<b>Attempting to boot Laravel and list routes...</b>\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    // Get all registered routes
    $router = app('router');
    $routes = $router->getRoutes();
    echo "Total registered routes: " . count($routes) . "\n";
    
    echo "Searching for route named 'login' in Laravel:\n";
    if ($router->has('login')) {
        $route = $routes->getByName('login');
        echo "✅ Route [login] IS defined!\n";
        echo "URI: " . $route->uri() . "\n";
        echo "Action: " . json_encode($route->getAction()) . "\n";
    } else {
        echo "❌ Route [login] is NOT defined in the router!\n";
        echo "Available named routes:\n";
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name) {
                echo " - " . $name . " (" . $route->uri() . ")\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Failed to load Laravel or routes: " . $e->getMessage() . "\n";
} catch (Throwable $t) {
    echo "Fatal error loading Laravel: " . $t->getMessage() . "\n";
}

echo "</pre>";
