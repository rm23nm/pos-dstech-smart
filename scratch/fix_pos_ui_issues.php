<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

// 1. Set default POS to 'NormalPoS' for all business partners
DB::table('company')->update(['PosTemplate' => 'NormalPoS']);
echo "Updated PosTemplate to NormalPoS for all companies.\n";

// 2. Fix UI issues in POS Blade files
$dir = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/';
$files = glob($dir . '*.blade.php');
$fixedCount = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Fix: Total Items displaying as currency
    $content = str_replace(
        "formatCurrency($('#_TotalItem'), _tempTotalItem);",
        "$('#_TotalItem').text(_tempTotalItem);",
        $content
    );
    // Handle variations in spacing
    $content = preg_replace(
        "/formatCurrency\(\\$?\('#_TotalItem'\),\s*_tempTotalItem\);/",
        "$('#_TotalItem').text(_tempTotalItem);",
        $content
    );

    // Fix: editDraft breaking due to dxDataGrid store insert
    // We will find the editDraft function and replace the insert logic.
    if (strpos($content, 'function editDraft(NoTransaksi)') !== false) {
        
        // Remove the buggy clearing code that breaks dataSource
        $content = preg_replace(
            "/var dataSource = dataGridInstance\.getDataSource\(\);\s*dataGridInstance\.option\(\"dataSource\", \[\]\);/",
            "// Cleared array safely\n\t\tallRowsData = [];",
            $content
        );

        // Replace the slow/buggy insert with pushing to allRowsData
        $insertPattern = "/dataSource\.store\(\)\.insert\(item\)\.then\(function\(\)\s*\{\s*dataSource\.reload\(\);\s*\}\)/";
        $content = preg_replace($insertPattern, "allRowsData.push(item);", $content);
        
        // Ensure dataGridInstance is refreshed after loop
        $refreshPattern = "/xLine \+=1;\s*\n?\s*\n?\s*\}\);\s*CalculateTotal\(\)/";
        $refreshReplacement = "xLine +=1;\n        \t});\n        \tdataGridInstance.option('dataSource', allRowsData);\n        \tdataGridInstance.refresh();\n        \tCalculateTotal()";
        
        $content = preg_replace($refreshPattern, $refreshReplacement, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "Fixed issues in: " . basename($file) . "\n";
        $fixedCount++;
    }
}

echo "\nTotal POS files fixed: $fixedCount\n";
