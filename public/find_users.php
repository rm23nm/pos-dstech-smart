<?php
$sqlFile = 'C:\Users\DSTech  Smart\Downloads\xpos.sql';
if (!file_exists($sqlFile)) die("File not found!");

$handle = fopen($sqlFile, "r");
$found = 0;
while (($line = fgets($handle)) !== false) {
    if (stripos($line, 'users') !== false && stripos($line, 'INSERT') !== false) {
        echo trim(substr($line, 0, 200)) . "...\n";
        $found++;
        if ($found >= 10) break;
    }
}
fclose($handle);
if ($found == 0) echo "No INSERT statements with 'users' found.";
