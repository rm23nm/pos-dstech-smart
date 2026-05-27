<?php
/**
 * Fix billing_new.blade.php - Final structural fix
 * 
 * Current problem structure (after previous fix):
 * Line 2005: </body>            <- inside template literal (correct)
 * Line 2006: </html>            <- inside template literal (correct)  
 * Line 2007: `);               <- closes document.write template (correct)
 * Line 2008: <script>          <- BARCODE SCANNER STARTS (WRONG POSITION)
 * ...barcode scanner code...
 * Line 2041: </script>
 * Line 2042: printWindow.document.close();   <- WRONG: outside script tag, outside function!
 * Line 2043: }                 <- WRONG: outside script tag!
 * 
 * Expected correct structure:
 * Line 2005: </body>           <- inside template literal
 * Line 2006: </html>           <- inside template literal
 * Line 2007: `);              <- closes document.write template
 * Line 2008: printWindow.document.close();   <- INSIDE printReceipt function
 * Line 2009: }                 <- CLOSES printReceipt function
 * 
 * then later the barcode scanner <script> belongs elsewhere (it's already at end of file)
 */

$filePath = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php';

$lines = file($filePath);
$totalLines = count($lines);
echo "File loaded. Total lines: $totalLines\n";

// Show current structure
echo "\n=== CURRENT structure (lines 2004-2050) ===\n";
for ($i = 2003; $i <= 2049 && $i < $totalLines; $i++) {
    echo ($i + 1) . ": " . $lines[$i];
}

// Verify expected content
// Line 2007 (idx 2006): "        `);\r\n"
// Line 2008 (idx 2007): "<script>\n"  <- barcode scanner
// ...
// Line 2041 (idx 2040): "</script>\n" <- end barcode scanner
// Line 2042 (idx 2041): "        printWindow.document.close();\r\n"
// Line 2043 (idx 2042): "    }\r\n"

$idx2006 = trim($lines[2006] ?? '');  // Should be "`)"
$idx2007 = trim($lines[2007] ?? '');  // Should be "<script>"
$idx2040 = trim($lines[2040] ?? '');  // Should be "</script>"
$idx2041 = trim($lines[2041] ?? '');  // Should be "printWindow.document.close();"
$idx2042 = trim($lines[2042] ?? '');  // Should be "}"

echo "\nKey lines:\n";
echo "Line 2007 (idx 2006): " . json_encode($idx2006) . "\n";
echo "Line 2008 (idx 2007): " . json_encode($idx2007) . "\n";
echo "Line 2041 (idx 2040): " . json_encode($idx2040) . "\n";
echo "Line 2042 (idx 2041): " . json_encode($idx2041) . "\n";
echo "Line 2043 (idx 2042): " . json_encode($idx2042) . "\n";

// Validate
$valid = strpos($idx2006, '`);') !== false &&
         strpos($idx2007, '<script>') !== false &&
         strpos($idx2040, '</script>') !== false &&
         strpos($idx2041, 'printWindow.document.close') !== false;

if (!$valid) {
    echo "\nValidation FAILED. Looking for barcode scanner block...\n";
    // Search for the misplaced block
    for ($i = 2003; $i <= 2060 && $i < $totalLines; $i++) {
        if (strpos($lines[$i], '<script>') !== false || 
            strpos($lines[$i], 'printWindow.document.close') !== false) {
            echo "Found at line " . ($i+1) . ": " . trim($lines[$i]) . "\n";
        }
    }
    die("Cannot proceed - unexpected structure\n");
}

echo "\nValidation passed!\n\n";
echo "Fix plan:\n";
echo "1. Remove barcode scanner block (lines 2008-2041, idx 2007-2040)\n";
echo "2. Move printWindow.document.close() and } to be right after `);\n";
echo "   (These are already at idx 2041 and 2042, just need to be before the barcode scanner)\n";

// The barcode scanner starts at idx 2007 (<script>)
// The barcode scanner ends at idx 2040 (</script>)
// printWindow.document.close() is at idx 2041
// } is at idx 2042

// Build new file:
// Lines 0-2006 (indices 0 to 2006 = lines 1 to 2007): keep as-is (includes the `); at idx 2006)
// Insert printWindow.document.close() and }
// Skip lines idx 2007-2040 (the misplaced barcode scanner)  
// Line idx 2041 has printWindow.document.close() - SKIP (we'll insert it earlier)
// Line idx 2042 has } - SKIP (we'll insert it earlier)
// Continue from idx 2043 onwards

$newLines = [];

// Keep lines 0 to 2006 (line 2007 is `); at idx 2006)
for ($i = 0; $i <= 2006; $i++) {
    $newLines[] = $lines[$i];
}

// Insert the correct printWindow.document.close() and }
$newLines[] = "        printWindow.document.close();\r\n";
$newLines[] = "    }\r\n";

// Skip idx 2007 through 2042 (the barcode scanner + old printWindow.document.close() + old })
$skipFrom = 2007;
$skipTo = 2042;  // inclusive
echo "Skipping lines " . ($skipFrom+1) . " to " . ($skipTo+1) . " (barcode scanner + old printWindow/})\n";

// Continue from idx 2043 onwards
for ($i = 2043; $i < $totalLines; $i++) {
    $newLines[] = $lines[$i];
}

echo "New file will have " . count($newLines) . " lines (removed " . ($skipTo - $skipFrom + 1) . " lines, added 2)\n";

// Write fixed file
$newContent = implode('', $newLines);
file_put_contents($filePath, $newContent);
echo "File saved!\n";

// Verify
echo "\n=== VERIFICATION - printReceipt function (lines 1995-2025) ===\n";
$verifyLines = file($filePath);
for ($i = 1994; $i <= 2024 && $i < count($verifyLines); $i++) {
    echo ($i + 1) . ": " . $verifyLines[$i];
}

echo "\n=== VERIFICATION - end of file (last 25 lines) ===\n";
$verifyTotal = count($verifyLines);
for ($i = max(0, $verifyTotal - 25); $i < $verifyTotal; $i++) {
    echo ($i + 1) . ": " . $verifyLines[$i];
}

echo "\nTotal lines: $verifyTotal\n";
echo "Done!\n";
