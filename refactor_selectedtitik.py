import os

view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

old_newData = """                      var newData = {
                          id: mockEl.dataset.id,
                          namatitiklampu: mockEl.dataset.namatitiklampu,
                          status: mockEl.dataset.status,
                          bookingid: mockEl.dataset.notransaksi,
                          rawjammulai: mockEl.dataset.jammulai,
                          rawjamsel: mockEl.dataset.jamselesai,
                          namakelompok: mockEl.dataset.namakelompok,
                          totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                          nettotal:        parseFloat(mockEl.dataset.nettotal || 0)
                      };"""

new_newData = """                      var newData = {
                          id: mockEl.dataset.id,
                          namatitiklampu: mockEl.dataset.namatitiklampu,
                          status: mockEl.dataset.status,
                          bookingid: mockEl.dataset.notransaksi,
                          rawjammulai: mockEl.dataset.jammulai,
                          rawjamsel: mockEl.dataset.jamselesai,
                          namakelompok: mockEl.dataset.namakelompok,
                          KelompokLampu: mockEl.dataset.kelompoklampu,
                          totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                          nettotal:        parseFloat(mockEl.dataset.nettotal || 0)
                      };"""

view_content = view_content.replace(old_newData, new_newData)

with open(view_path, 'w') as f:
    f.write(view_content)

os.system('C:\\xampp\\php\\php.exe artisan view:clear')
