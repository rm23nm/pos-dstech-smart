import os

view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

old_html = """                                         data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                         data-namakelompok="{{ $tl->NamaKelompok }}"
"""

new_html = """                                         data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                         data-namakelompok="{{ $tl->NamaKelompok }}"
                                         data-kelompoklampu="{{ $item->KelompokLampu ?? '' }}"
"""

view_content = view_content.replace(old_html, new_html)

with open(view_path, 'w') as f:
    f.write(view_content)

os.system('C:\\xampp\\php\\php.exe artisan view:clear')
