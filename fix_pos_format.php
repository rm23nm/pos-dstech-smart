<?php
$files = glob('resources/views/Transaksi/Penjualan/PoS/*.blade.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    
    // Fix formatCurrency function
    $content = preg_replace(
        '/let formattedAmount = parsedAmount\.toLocaleString\(\'en-US\', \{\s*style: \'decimal\',\s*minimumFractionDigits: 2,\s*maximumFractionDigits: 2\s*\}\);/s',
        'let formattedAmount = new Intl.NumberFormat(\'id-ID\', { style: \'currency\', currency: \'IDR\' }).format(parsedAmount).replace("Rp", "Rp. ").trim();',
        $content
    );

    // Fix kembalian sweetalert
    $content = preg_replace(
        '/let formattedAmount = parseFloat\(response\.Kembalian\)\.toLocaleString\(\'en-US\', \{\s*style: \'decimal\',\s*minimumFractionDigits: 2,\s*maximumFractionDigits: 2\s*\}\);/s',
        'let formattedAmount = new Intl.NumberFormat(\'id-ID\', { style: \'currency\', currency: \'IDR\' }).format(parseFloat(response.Kembalian)).replace("Rp", "Rp. ").trim();',
        $content
    );

    // Fix other toLocaleString
    $content = preg_replace(
        '/v\.TotalHutang\.toLocaleString\(\'en-US\'\)/',
        'new Intl.NumberFormat(\'id-ID\', { style: \'currency\', currency: \'IDR\' }).format(v.TotalHutang).replace("Rp", "Rp. ").trim()',
        $content
    );
    
    // Fix SetEnableCommand validation for JumlahBayar
    $content = preg_replace(
        '/\/\/\s*if \(\$\(\'#JumlahBayar\'\)\.attr\(\'originalvalue\'\) < \$\(\'#_TotalTagihan\'\)\.val\(\)\) \{\s*\/\/\s*ErrorCount \+=1;\s*\/\/\s*\}/s',
        'if (parseFloat($(\'#JumlahBayar\').attr(\'originalvalue\') || 0) < parseFloat($(\'#_TotalNetBayar\').attr(\'originalvalue\') || 0)) { ErrorCount += 1; }',
        $content
    );
    
    file_put_contents($file, $content);
}
echo "Replaced formatting and validation logic across PoS files.\n";
