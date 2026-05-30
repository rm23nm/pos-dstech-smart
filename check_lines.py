with open('resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php', 'r', encoding='utf-8', errors='ignore') as f:
    for i, line in enumerate(f):
        if 'pos-main' in line: print('pos-main at', i+1)
        if 'modalReceiptPreview' in line: print('modal at', i+1)
        if '</body>' in line: print('body end at', i+1)
