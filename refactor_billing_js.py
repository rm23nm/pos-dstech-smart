import re

with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "r") as f:
    content = f.read()

old_vars = """    var dataPelangganAll = {!! json_encode($pelanggan) !!}; // Data Member Injected"""
new_vars = """    var dataPelangganAll = {!! json_encode($pelanggan) !!}; // Data Member Injected
    var dataCustomerMemberships = {!! json_encode($customerMemberships ?? []) !!}; // Active Memberships"""

content = content.replace(old_vars, new_vars)

old_js_validation1 = """            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (!memberData) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member tidak ditemukan (Harap Refresh).</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            } else if (memberData.isPaidMembership != 1) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member belum aktif (Nilai: ' + memberData.isPaidMembership + '). Harap Refresh!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }

            // Batasi durasi dengan maxTimePerPlay 
            // Anggap maxTimePerPlay adalah dalam jumlah SLOT atau JAM
            var maxTime = parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi > maxTime) {
            }"""

new_js_validation1 = """            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (!memberData) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member tidak ditemukan (Harap Refresh).</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            } else if (memberData.isPaidMembership != 1) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member belum aktif (Nilai: ' + memberData.isPaidMembership + '). Harap Refresh!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }
            
            // Cek paket membership untuk KelompokLampu ini
            var validMemberships = dataCustomerMemberships.filter(function(m) {
                return m.KodePelanggan == memberId && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.kelompoklampu);
            });
            if (validMemberships.length === 0) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member tidak memiliki paket aktif untuk kategori ' + selectedTitik.kelompoklampu + '.</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }

            // Batasi durasi dengan maxTimePerPlay 
            // Anggap maxTimePerPlay adalah dalam jumlah SLOT atau JAM
            var maxTime = parseInt(validMemberships[0].maxTimePerPlay) || parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi > maxTime) {
            }"""

content = content.replace(old_js_validation1, new_js_validation1)

old_js_validation2 = """        if (jenisPaket === 'PAKETMEMBER') {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == kodePelanggan);
            if (!memberData || memberData.isPaidMembership != 1) return;
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 0;
            var maxTime = parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi !== maxTime) return;
        }"""

new_js_validation2 = """        if (jenisPaket === 'PAKETMEMBER') {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == kodePelanggan);
            if (!memberData || memberData.isPaidMembership != 1) return;
            var validMemberships = dataCustomerMemberships.filter(function(m) {
                return m.KodePelanggan == kodePelanggan && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.kelompoklampu);
            });
            if (validMemberships.length === 0) return;
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 0;
            var maxTime = parseInt(validMemberships[0].maxTimePerPlay) || parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi !== maxTime) return;
        }"""

content = content.replace(old_js_validation2, new_js_validation2)


with open("resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "w") as f:
    f.write(content)

print("billing_new logic updated!")
