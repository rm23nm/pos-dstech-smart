<?php
$file = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';
$content = file_get_contents($file);

// Find the modalCheckout block
if (preg_match('/<!-- Checkout Modal -->(.*?)<\/div>\s*<\/div>\s*<\/div>/s', $content, $m)) {
    $modalHtml = $m[1];
    
    // Hide Pajak PPN
    $modalHtml = str_replace('<label class="text-sm font-bold text-slate-600">Pajak (PPN', '<label class="text-sm font-bold text-slate-600" style="display:none;">Pajak (PPN', $modalHtml);
    $modalHtml = str_replace('<input type="hidden" id="chkPpnRate">', '', $modalHtml);
    $modalHtml = str_replace('<input type="text" class="form-control" id="chkPpnNominal" readonly style="background:#e2e8f0;">', '', $modalHtml);
    
    // Hide Pajak Hiburan
    $modalHtml = str_replace('<label class="text-sm font-bold text-slate-600">Pajak Hiburan', '<label class="text-sm font-bold text-slate-600" style="display:none;">Pajak Hiburan', $modalHtml);
    $modalHtml = str_replace('<input type="hidden" id="chkPb1Rate">', '', $modalHtml);
    $modalHtml = str_replace('<input type="text" class="form-control" id="chkPb1Nominal" readonly style="background:#e2e8f0;">', '', $modalHtml);
    
    // Hide Voucher
    $modalHtml = str_replace('<label class="text-sm font-bold text-slate-600">Kode Voucher', '<label class="text-sm font-bold text-slate-600" style="display:none;">Kode Voucher', $modalHtml);
    $modalHtml = preg_replace('/<div class="input-group">.*?<\/div>/s', '', $modalHtml, 1);
    $modalHtml = str_replace('<label class="text-sm font-bold text-slate-600 mt-2">Voucher', '<label class="text-sm font-bold text-slate-600 mt-2" style="display:none;">Voucher', $modalHtml);
    $modalHtml = str_replace('<input type="text" class="form-control" id="chkVoucher" value="0" readonly style="background:#e2e8f0; font-weight:bold; color:#dc2626;">', '', $modalHtml);
    
    $content = str_replace($m[1], $modalHtml, $content);
    file_put_contents($file, $content);
    echo "modalCheckout cleaned up successfully!\n";
} else {
    echo "modalCheckout not found.\n";
}
