<?php
$files = glob('resources/views/Transaksi/Penjualan/PoS/*.blade.php');
foreach($files as $file) {
    $content = file_get_contents($file);

    // Replace Intl.NumberFormat with simpler toLocaleString to avoid engine call stack issues
    $content = preg_replace(
        '/let formattedAmount = new Intl\.NumberFormat\(\'id-ID\', \{ style: \'currency\', currency: \'IDR\' \}\)\.format\((.*?)\)\.replace\("Rp", "Rp\. "\)\.trim\(\);/s',
        'let valToFormat = $1; if (isNaN(valToFormat)) valToFormat = 0; let formattedAmount = "Rp. " + parseFloat(valToFormat).toLocaleString("id-ID", {minimumFractionDigits: 0, maximumFractionDigits: 0});',
        $content
    );

    // Append barcode scanner interceptor script if not already present
    if (strpos($content, 'var _globalBarcodeScannerBuffer') === false) {
        $interceptor = <<<JS

<script>
    var _globalBarcodeScannerBuffer = "";
    var _globalBarcodeScannerTimer = null;
    
    $(document).on("keypress", function(e) {
        if (e.target.id === "_Barcode") return; // Ignore if already focused on barcode
        
        if (e.key && e.key.length === 1 && !e.ctrlKey && !e.altKey) {
            _globalBarcodeScannerBuffer += e.key;
            
            if (_globalBarcodeScannerTimer) clearTimeout(_globalBarcodeScannerTimer);
            
            _globalBarcodeScannerTimer = setTimeout(function() {
                _globalBarcodeScannerBuffer = "";
            }, 60); // Scanner types very fast
            
        } else if (e.key === "Enter" || e.keyCode === 13) {
            if (_globalBarcodeScannerBuffer.length >= 3) {
                // It's a scanner!
                e.preventDefault();
                $('#_Barcode').val(_globalBarcodeScannerBuffer);
                _globalBarcodeScannerBuffer = "";
                $('#_Barcode').focus();
                
                var eEnter = $.Event('keypress');
                eEnter.which = 13;
                eEnter.keyCode = 13;
                $('#_Barcode').trigger(eEnter);
            } else {
                _globalBarcodeScannerBuffer = "";
            }
        }
    });
</script>
JS;
        $content = str_replace('</body>', $interceptor . "\n</body>", $content);
    }
    
    file_put_contents($file, $content);
}
echo "Barcode scanner interceptor added and formatting logic updated.\n";
