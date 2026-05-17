<?php
echo "<pre>";
echo "<h3>Checking routes/web.php Content:</h3>";
if (file_exists(__DIR__ . '/../routes/web.php')) {
    $content = file_get_contents(__DIR__ . '/../routes/web.php');
    echo htmlspecialchars(substr($content, 0, 2000));
} else {
    echo "routes/web.php not found!";
}

echo "<h3>Running artisan route:list:</h3>";
chdir(__DIR__ . '/..');
echo shell_exec("php artisan route:list --path=login 2>&1");
echo shell_exec("php artisan route:list --path=landingpage 2>&1");

echo "<h3>Current git status on live:</h3>";
echo shell_exec("git status 2>&1");
echo shell_exec("git log -n 3 --oneline 2>&1");
echo "</pre>";
