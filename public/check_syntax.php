<?php
$file = __DIR__.'/../app/Http/Controllers/UserController.php';
try {
    require_once $file;
    echo "Syntax OK";
} catch (ParseError $e) {
    echo "Parse error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
