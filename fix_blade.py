import re

file_path = 'resources/views/Transaksi/Penjualan/PoS/PrintThermalTiket.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# First restore original
if '<div class="footer">' not in content.split('@else')[0]:
    print("Oops, footer is missing. Wait, this script needs the original content.")

