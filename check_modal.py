with open('resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php', 'r', encoding='utf-8', errors='ignore') as f:
    lines = f.readlines()
    for i, line in enumerate(lines):
        if i >= 1795 and i <= 1820:
            print(f"{i+1}: {line.strip()}")
