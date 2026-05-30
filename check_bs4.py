from bs4 import BeautifulSoup

with open('resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php', 'r', encoding='utf-8', errors='ignore') as f:
    soup = BeautifulSoup(f, 'html.parser')
    modal = soup.find(id='modalReceiptPreview')
    if modal:
        curr = modal.parent
        print("Ancestors:")
        while curr and curr.name:
            print(curr.name, curr.get('class'))
            curr = curr.parent
    else:
        print("modalReceiptPreview not found!")
