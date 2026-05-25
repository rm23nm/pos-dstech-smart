<?php
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "Log file not found.";
    exit;
}
$lines = file($logFile);
$lastLines = array_slice($lines, -100);
echo implode("", $lastLines);
