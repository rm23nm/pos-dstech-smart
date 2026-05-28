<?php
$file = 'd:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/routes/web.php';
$content = "\n// Parkir Routes\nRoute::get('/parkir/dashboard', [\App\Http\Controllers\ParkirController::class, 'dashboard'])->name('parkir.dashboard')->middleware(['auth', 'check.session']);\n";
file_put_contents($file, $content, FILE_APPEND);
echo "Appended";
