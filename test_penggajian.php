<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

try {
    Auth::loginUsingId(39);
    $req = Request::create('/proses-penggajian', 'GET', ['bulan' => '06', 'tahun' => '2026']);
    $controller = app()->make(\App\Http\Controllers\PenggajianController::class);
    $view = $controller->prosesPenggajian($req);
    echo $view->render();
} catch (\Exception $e) {
    echo "ERROR_THROWN: " . $e->getMessage() . " at line " . $e->getLine();
}
