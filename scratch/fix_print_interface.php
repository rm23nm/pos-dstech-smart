<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dir = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/';
$files = glob($dir . '*.blade.php');

$fixedCount = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Look for the "Interface belum tersedia" block
    if (strpos($content, 'Interface belum tersedia') !== false) {
        
        // We want to replace the whole `else{ Swal.fire( "Interface belum tersedia" ) }` 
        // with fallback to web printing.
        
        // The pattern is typically:
        // else{
        // 	Swal.fire({
        // 		icon: "error",
        // 		title: "Opps...",
        // 		text: "Interface belum tersedia",
        // 	});
        // }
        // Let's replace it with web print fallback (same as USB).
        
        $fallbackCode = "else {\n\t\t\tlet url = \"{{ url('') }}\";\n\t\t\turl += \"/fpenjualan/printthermal/\"+NoTransaksi;\n\t\t\twindow.open(url, \"_blank\");\n\t\t\tlocation.reload();\n\t\t}";
        
        $pattern = '/else\s*\{\s*Swal\.fire\(\{\s*icon:\s*"error",\s*title:\s*"Opps\.\.\.",\s*text:\s*"Interface belum tersedia",?\s*\}\);\s*\}/';
        
        if (preg_match($pattern, $content)) {
            $newContent = preg_replace($pattern, $fallbackCode, $content);
            
            // Also fix the `.then((result) => { return; })` inside PrintStruk so it actually returns.
            // Actually, we can just replace `}).then((result) => { return; });` with `}); return;`
            $newContent = str_replace("}).then((result) => {\n\t\t\t\treturn;\n\t\t\t});", "});\n\t\t\treturn;", $newContent);
            $newContent = str_replace("}).then((result) => {\r\n\t\t\t\treturn;\r\n\t\t\t});", "});\n\t\t\treturn;", $newContent);
            $newContent = str_replace("}).then((result) => {\n\t\t\t\treturn;\n\t\t\t});", "});\n\t\t\treturn;", $newContent);
            // Some use 3 tabs, some use 4. Let's use regex for the return fix too.
            $newContent = preg_replace('/\}\\)\.then\(\(result\)\s*=>\s*\{\s*return;\s*\}\);/', '}); return;', $newContent);

            if ($newContent !== $content) {
                file_put_contents($file, $newContent);
                echo "Fixed: " . basename($file) . "\n";
                $fixedCount++;
            }
        } else {
            echo "Pattern not matched in: " . basename($file) . "\n";
        }
    }
}

echo "\nTotal files fixed: $fixedCount\n";
