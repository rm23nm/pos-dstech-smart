import os

# 1. Update TableOrderController
ctrl_path = 'app/Http/Controllers/TableOrderController.php'
with open(ctrl_path, 'r') as f:
    ctrl_content = f.read()

ctrl_content = ctrl_content.replace(
    "->select('customer_memberships.*', 'member_packages.KelompokLampu')",
    "->select('customer_memberships.*', 'member_packages.KelompokLampu', 'member_packages.NamaPaket')"
)

with open(ctrl_path, 'w') as f:
    f.write(ctrl_content)

# 2. Update billing_new.blade.php
view_path = 'resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

# Replace onJenisPaketChange handling of PAKETMEMBER
old_paket_logic = """        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
            }
        }

        var cat = selectedTitik && selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        dataPaketAll.forEach(function(p) {
            if (p.JenisPaket === jenis) {
                // Filter berdasarkan kategori meja
                if (cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                    var opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.NamaPaket;
                    opt.dataset.harga = p.HargaNormal || 0;
                    opt.dataset.durasi = p.DurasiPaket || 1; // Inject package base durasi
                    sel.appendChild(opt);
                }
            }
        });"""

new_paket_logic = """        var cat = selectedTitik && selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        
        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "") : "";
            
            var validMemberships = [];
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
                validMemberships = dataCustomerMemberships.filter(function(m) {
                    return m.KodePelanggan == memberId && (!m.KelompokLampu || m.KelompokLampu == kelLampu);
                });
            }

            validMemberships.forEach(function(m) {
                var opt = document.createElement('option');
                opt.value = m.KodePaketMember; // Gunakan string ID PaketMember 
                opt.text = (m.NamaPaket || m.KodePaketMember) + " (Berlaku s/d " + m.ValidUntil + ")";
                opt.dataset.harga = 0;
                opt.dataset.durasi = m.maxTimePerPlay || 1;
                sel.appendChild(opt);
            });
            
            // Set Unit Label
            document.getElementById('tdUnitLabel').textContent = 'Jam';
            
            if (sel.options.length === 1) { // Hanya "-- Pilih Paket --"
                swal("Info", "Member belum dipilih atau tidak memiliki paket aktif untuk meja ini.", "info");
                return;
            }
        } else {
            dataPaketAll.forEach(function(p) {
                if (p.JenisPaket === jenis) {
                    // Filter berdasarkan kategori meja
                    if (cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                        var opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = p.NamaPaket;
                        opt.dataset.harga = p.HargaNormal || 0;
                        opt.dataset.durasi = p.DurasiPaket || 1; // Inject package base durasi
                        sel.appendChild(opt);
                    }
                }
            });
            
            if (sel.options.length === 1) {
                swal("Info", "Tidak ada paket tambahan yang tersedia untuk jenis paket ini.", "info");
                return;
            }
        }"""

view_content = view_content.replace(old_paket_logic, new_paket_logic)


old_member_change = """        var jenis = document.getElementById('ppJenisPaket').value;
        if(jenis === 'PAKETMEMBER') {
            var mId = document.getElementById('ppKodePelanggan').value;
            if (mId) {
                var mData = dataPelangganAll.find(m => m.KodePelanggan == mId);
                if (mData && mData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(mData.maxTimePerPlay) || 1;
                }
            }

            updateJamSelesai();
            
            // Re-render slots to reflect new member status (lock/unlock)
            if (rawSlots && rawSlots.length > 0) {
                // Clear selections
                selectedSlots = [];
                // document.getElementById('ppDurasi').value = 1;
                renderSlots(rawSlots);
            }
        }"""

new_member_change = """        var jenis = document.getElementById('ppJenisPaket').value;
        if(jenis === 'PAKETMEMBER') {
            var mId = document.getElementById('ppKodePelanggan').value;
            if (mId) {
                var mData = dataPelangganAll.find(m => m.KodePelanggan == mId);
                if (mData && mData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(mData.maxTimePerPlay) || 1;
                }
            }

            // Panggil fungsi ini untuk merefresh ulang dropdown paket berlangganan
            onJenisPaketChange('PAKETMEMBER');
            
            updateJamSelesai();
            
            // Re-render slots to reflect new member status (lock/unlock)
            if (rawSlots && rawSlots.length > 0) {
                // Clear selections
                selectedSlots = [];
                // document.getElementById('ppDurasi').value = 1;
                renderSlots(rawSlots);
            }
        }"""

view_content = view_content.replace(old_member_change, new_member_change)


with open(view_path, 'w') as f:
    f.write(view_content)
