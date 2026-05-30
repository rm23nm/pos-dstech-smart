with open('resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php', 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()
    idx = content.find('id="modalReceiptPreview"')
    if idx == -1:
        idx = content.find("id='modalReceiptPreview'")
    if idx != -1:
        start = max(0, idx - 500)
        print(content[start:idx+500])
