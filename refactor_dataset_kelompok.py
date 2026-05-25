import re

with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "r") as f:
    content = f.read()

# 1. Add data-kelompoklampu to the HTML titik-box
old_html = """                                           data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                           data-namakelompok="{{ $tl->NamaKelompok }}\""""
new_html = """                                           data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                           data-namakelompok="{{ $tl->NamaKelompok }}"
                                           data-kelompoklampu="{{ $item->KelompokLampu }}\""""
content = content.replace(old_html, new_html)

# 2. Add KelompokLampu to updateUIWithLatestData
old_js1 = """                  el.dataset.rawjamselesai = item.JamSelesai || '';
                  el.dataset.namakelompok = item.NamaKelompok || '';"""
new_js1 = """                  el.dataset.rawjamselesai = item.JamSelesai || '';
                  el.dataset.namakelompok = item.NamaKelompok || '';
                  if (item.KelompokLampu) el.dataset.kelompoklampu = item.KelompokLampu;"""
content = content.replace(old_js1, new_js1)

# 3. Add KelompokLampu to selectTitikLampu(el)
old_js2 = """            rawjamselesai: el.dataset.rawjamselesai,
            namakelompok: el.dataset.namakelompok,"""
new_js2 = """            rawjamselesai: el.dataset.rawjamselesai,
            namakelompok: el.dataset.namakelompok,
            KelompokLampu: el.dataset.kelompoklampu,"""
content = content.replace(old_js2, new_js2)

# 4. Also update the other selectTitikLampu logic inside setInterval where mockEl is created
old_js3 = """                          totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                          nettotal:        parseFloat(mockEl.dataset.nettotal || 0)
                      };"""
new_js3 = """                          totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                          nettotal:        parseFloat(mockEl.dataset.nettotal || 0),
                          KelompokLampu:   mockEl.dataset.kelompoklampu
                      };"""
content = content.replace(old_js3, new_js3)

with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "w") as f:
    f.write(content)

print("billing_new.blade.php updated completely for KelompokLampu!")
