with open('resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php', 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()
    idx1 = content.find("class='pos-main'")
    if idx1 == -1: idx1 = content.find('class="pos-main"')
    
    idx2 = content.find("id='modalReceiptPreview'")
    if idx2 == -1: idx2 = content.find('id="modalReceiptPreview"')
    
    print('pos-main at', idx1, 'modal at', idx2)
    
    # check if pos-main closes before modal
    if idx1 != -1:
        end_pos_main = content.find('</main>', idx1)
        if end_pos_main == -1: end_pos_main = content.find('</div>', idx1)
        print('end pos main maybe around', end_pos_main)
