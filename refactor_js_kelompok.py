import re

with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "r") as f:
    content = f.read()

# Fix the casing of KelompokLampu in Javascript validation
old_js = """              var validMemberships = dataCustomerMemberships.filter(function(m) {
                  return m.KodePelanggan == kodePelanggan && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.kelompoklampu);
              });"""

new_js = """              var validMemberships = dataCustomerMemberships.filter(function(m) {
                  return m.KodePelanggan == kodePelanggan && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.KelompokLampu);
              });"""

content = content.replace(old_js, new_js)

with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "w") as f:
    f.write(content)

print("billing_new.blade.php updated to fix KelompokLampu capitalization")
