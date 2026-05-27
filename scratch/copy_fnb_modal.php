<?php

$billingFile = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php';
$ticketingFile = __DIR__ . '/../resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php';

$billingContent = file_get_contents($billingFile);
$ticketingContent = file_get_contents($ticketingFile);

// Extract the modal from billing_new
$marker = '<!-- ===== MODAL JUAL FnB STANDALONE ===== -->';
$modalPos = strpos($billingContent, $marker);

if ($modalPos !== false) {
    $modalCode = substr($billingContent, $modalPos);
    
    // In billing_new, it was added at the very end of the file (after </body></html> or before it)
    // We should clean it up to just include the modal and script
    // Let's strip any trailing </body></html> from modalCode if they exist
    $modalCode = str_replace(['</body>', '</html>'], '', $modalCode);

    // Remove existing modal in TicketingPoS if any
    $existingModalPos = strpos($ticketingContent, $marker);
    if ($existingModalPos !== false) {
        $ticketingContent = substr($ticketingContent, 0, $existingModalPos);
    }

    // Change the button
    $oldBtn = '<a href="{{ route(\'fpenjualan-pos\') }}" class="btn btn-sm btn-info ms-2 font-bold" style="background-color: #0284c7; color: white; border: none;"><i class="fas fa-hamburger"></i> POS F&B</a>';
    $newBtn = '<button class="btn btn-sm btn-info ms-2 font-bold" style="background-color: #0284c7; color: white; border: none;" onclick="openJualFnbModal()"><i class="fas fa-hamburger"></i> POS F&B</button>';
    
    // We also might have POS F&B (if they changed the text to POS F&B)
    $ticketingContent = str_replace($oldBtn, $newBtn, $ticketingContent);

    // If they already changed the button but we missed it, try a regex
    $ticketingContent = preg_replace('/<a[^>]*href="[^"]*fpenjualan-pos"[^>]*>.*?POS F&B<\/a>/is', $newBtn, $ticketingContent);

    // Remove </body></html> from ticketing content to append cleanly
    $ticketingContent = str_replace(['</body>', '</html>'], '', $ticketingContent);

    // Append new modal
    $finalContent = $ticketingContent . "\n" . $modalCode . "\n</body>\n</html>";

    file_put_contents($ticketingFile, $finalContent);
    echo "Successfully copied FnB Standalone Modal to TicketingPoS!\n";
} else {
    echo "Modal marker not found in billing_new.blade.php\n";
}
