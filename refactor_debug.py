import os

view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

old_js = """        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "") : "";"""

new_js = """        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "") : "";
            
            console.log("=== DEBUG PAKET MEMBER ===");
            console.log("memberId: ", memberId);
            console.log("kelLampu (from selectedTitik): ", kelLampu, typeof kelLampu);
            console.log("selectedTitik: ", selectedTitik);
            console.log("dataCustomerMemberships: ", dataCustomerMemberships);"""

view_content = view_content.replace(old_js, new_js)

with open(view_path, 'w') as f:
    f.write(view_content)

os.system('C:\\xampp\\php\\php.exe artisan view:clear')
