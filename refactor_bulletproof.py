import os

view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

# 1. Update updateUIWithLatestData to include kelompoklampu
old_ui = """                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.namakelompok = item.NamaKelompok || '';"""

new_ui = """                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.namakelompok = item.NamaKelompok || '';
                el.dataset.kelompoklampu = item.KelompokLampu || '';"""

view_content = view_content.replace(old_ui, new_ui)


# 2. Update onJenisPaketChange to be bulletproof against whitespaces
old_filter = """        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "") : "";
            
            console.log("=== DEBUG PAKET MEMBER ===");
            console.log("memberId: ", memberId);
            console.log("kelLampu (from selectedTitik): ", kelLampu, typeof kelLampu);
            console.log("selectedTitik: ", selectedTitik);
            console.log("dataCustomerMemberships: ", dataCustomerMemberships);
            
            var validMemberships = [];
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
                validMemberships = dataCustomerMemberships.filter(function(m) {
                    return m.KodePelanggan == memberId && (!m.KelompokLampu || m.KelompokLampu == kelLampu);
                });
            }"""

new_filter = """        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value.trim();
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "").toString().trim() : "";
            
            console.log("=== DEBUG PAKET MEMBER ===");
            console.log("memberId: ", memberId);
            console.log("kelLampu (from selectedTitik): ", kelLampu, typeof kelLampu);
            console.log("selectedTitik: ", selectedTitik);
            console.log("dataCustomerMemberships: ", dataCustomerMemberships);
            
            var validMemberships = [];
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
                validMemberships = dataCustomerMemberships.filter(function(m) {
                    var mId = (m.KodePelanggan || "").toString().trim();
                    var mKl = (m.KelompokLampu || "").toString().trim();
                    return mId == memberId && (!mKl || mKl == kelLampu);
                });
                console.log("validMemberships after filter: ", validMemberships);
            }"""

view_content = view_content.replace(old_filter, new_filter)

with open(view_path, 'w') as f:
    f.write(view_content)

os.system('C:\\xampp\\php\\php.exe artisan view:clear')
