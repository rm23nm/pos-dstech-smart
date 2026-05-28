
<?php $__env->startSection('content'); ?>
<?php if(request()->has('popup')): ?>
<style>
#tc_header, #tc_aside, #tc_header_mobile, .header-mobile, #tc_footer, .card-header, .card-body {
    display: none !important;
}
#tc_wrapper { padding-top: 0 !important; padding-left: 0 !important; }
#addModal {
    display: block !important;
    position: static !important;
    opacity: 1 !important;
    background: transparent !important;
    padding: 0 !important;
}
.modal-dialog { max-width: 100% !important; margin: 0 !important; }
.modal-content { border: none !important; box-shadow: none !important; }
.modal-header .btn-close { display: none !important; }
</style>
<?php endif; ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Pendaftaran Servis (Service Advisor)
                        <span class="d-block text-muted pt-2 font-size-sm">Daftar kendaraan masuk (Menunggu/Dikerjakan)</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?php echo e(route('fpenjualan-custdisplay')); ?>" target="_blank" class="btn btn-info text-white me-2">
                        <i class="bi bi-display"></i> Layar Konsumen
                    </a>
                    <button type="button" class="btn btn-primary" onclick="openAddModal()">
                        <i class="bi bi-plus-circle"></i> Tambah Pendaftaran
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-checkable" id="dataTable">
                    <thead>
                        <tr>
                            <th>No PKB</th>
                            <th>Plat Nomor</th>
                            <th>Pelanggan</th>
                            <th>Mekanik</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add (2 Columns) -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pendaftaran Servis Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <!-- Kolom 1: Data Kendaraan & Pelanggan -->
                        <div class="col-md-5 border-end pr-4">
                            <h6 class="mb-3 text-primary">Data Registrasi</h6>
                            
                            <div class="form-group mb-3">
                                <label>Kendaraan (Plat Nomor) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2" name="PlatNomor" id="PlatNomor" style="width: 85%;" required>
                                        <option value="">-- Cari Plat Nomor --</option>
                                        <?php $__currentLoopData = $kendaraans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($k->PlatNomor); ?>" data-pelanggan="<?php echo e($k->KodePelanggan); ?>"><?php echo e($k->PlatNomor); ?> (<?php echo e($k->Merek); ?> <?php echo e($k->Tipe); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" onclick="showAddKendaraan()" style="width: 15%;"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Pelanggan (Opsional)</label>
                                <div class="input-group">
                                    <select class="form-control select2" name="KodePelanggan" id="KodePelanggan" style="width: 85%;">
                                        <option value="">-- Cari Pelanggan --</option>
                                        <?php $__currentLoopData = $pelanggans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($p->KodePelanggan); ?>"><?php echo e($p->NamaPelanggan); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="hidden" name="NamaPelanggan" id="NamaPelanggan">
                                    <button class="btn btn-outline-primary" type="button" onclick="showAddPelanggan()" style="width: 15%;"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Pilih Mekanik (Opsional, Mekanik dapat mengambil alih di Dashboard)</label>
                                <select class="form-control select2" name="KodeMekanik" id="KodeMekanik" style="width: 100%;">
                                    <option value="">-- Kosongkan (Belum ditentukan) --</option>
                                    <?php $__currentLoopData = $mekaniks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($m->KodeMekanik); ?>"><?php echo e($m->NamaMekanik); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Estimasi Waktu Pengerjaan <span class="text-danger">*</span></label>
                                <select class="form-control" name="EstimasiWaktu" required>
                                    <option value="15">15 Menit</option>
                                    <option value="30" selected>30 Menit</option>
                                    <option value="45">45 Menit</option>
                                    <option value="60">1 Jam</option>
                                    <option value="90">1.5 Jam</option>
                                    <option value="120">2 Jam</option>
                                    <option value="180">3 Jam</option>
                                    <option value="240">4 Jam</option>
                                    <option value="300">5 Jam</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Keluhan / Permintaan</label>
                                <div id="keluhanContainer">
                                    <div class="input-group mb-2 keluhan-row">
                                        <input type="text" class="form-control" name="Keluhan[]" placeholder="Contoh: Ganti Oli" required>
                                        <button class="btn btn-outline-danger" type="button" onclick="removeKeluhanRow(this)"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addKeluhanRow()"><i class="bi bi-plus"></i> Tambah Baris</button>
                            </div>
                        </div>
                        
                        <!-- Kolom 2: Estimasi Sparepart -->
                        <div class="col-md-7 pl-4">
                            <h6 class="mb-3 text-primary">Kebutuhan Material / Sparepart (Opsional)</h6>
                            <div class="row mb-3">
                                <div class="col-md-12 mb-2">
                                    <label>Filter Kategori (Opsional)</label>
                                    <select class="form-control select2" id="tempKategori" style="width:100%">
                                        <option value="">-- Semua Kategori --</option>
                                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($kat->KodeJenis); ?>"><?php echo e($kat->NamaJenis); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Cari Sparepart</label>
                                    <select class="form-control select2" id="tempKodeItem" style="width:100%">
                                        <option value="">-- Pilih Item --</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Qty</label>
                                    <input type="number" class="form-control" id="tempQty" value="1" min="1">
                                </div>
                                <div class="col-md-3">
                                    <label>Harga</label>
                                    <input type="number" class="form-control" id="tempHarga" readonly>
                                    <input type="hidden" id="tempNamaItem">
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-icon" onclick="addTempItem()"><i class="bi bi-check-lg"></i></button>
                                </div>
                            </div>
                            
                            <table class="table table-bordered table-sm" id="tempItemTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Item</th>
                                        <th width="10%">Qty</th>
                                        <th width="20%">Harga</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="10%">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRow"><td colspan="5" class="text-center text-muted">Belum ada item ditambahkan</td></tr>
                                </tbody>
                            </table>
                            <div class="text-end fw-bold mt-2">
                                Total Estimasi: Rp <span id="tempTotalLabel">0</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="savePKB()">Simpan & Masukkan Antrean</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kendaraan -->
<div class="modal fade" id="kendaraanModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kendaraan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="kendaraanForm">
                    <?php echo csrf_field(); ?>
                      <div class="form-group mb-2">
                          <label>Plat Nomor <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" name="PlatNomor" required>
                      </div>
                      <div class="form-group mb-2">
                          <label>Jenis Kendaraan <span class="text-danger">*</span></label>
                          <select class="form-control" name="JenisKendaraan" required>
                              <option value="Mobil">Mobil</option>
                              <option value="Motor">Motor</option>
                          </select>
                      </div>
                    <div class="form-group mb-2">
                        <label>Merek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="Merek" placeholder="Cth: Honda" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Tipe</label>
                        <input type="text" class="form-control" name="Tipe" placeholder="Cth: Beat">
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label>Tahun</label>
                            <input type="number" class="form-control" name="Tahun">
                        </div>
                        <div class="col-6">
                            <label>Warna</label>
                            <input type="text" class="form-control" name="Warna">
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-muted">Detail STNK (Opsional)</h6>
                    <div class="form-group mb-2">
                        <label>Nama di STNK</label>
                        <input type="text" class="form-control" name="NamaSTNK">
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label>No Mesin</label>
                            <input type="text" class="form-control" name="NoMesin">
                        </div>
                        <div class="col-6">
                            <label>No Rangka</label>
                            <input type="text" class="form-control" name="NoRangka">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitKendaraan()">Simpan Kendaraan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="pelangganModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pelangganForm">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-2">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="NamaPelanggan" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>No HP</label>
                        <input type="text" class="form-control" name="NoHP">
                    </div>
                    <div class="form-group mb-2">
                        <label>Alamat</label>
                        <textarea class="form-control" name="Alamat" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitPelanggan()">Simpan Pelanggan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Items (View Only / Update) -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rincian Sparepart & Jasa (<span id="modalNoPKB"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="detailForm" class="mb-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="detailNoPKB" name="NoPKB">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Pilih Jasa / Sparepart</label>
                            <select class="form-control select2" id="KodeItem" name="KodeItem" style="width:100%">
                                <option value="">-- Pilih Item --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Qty</label>
                            <input type="number" class="form-control" id="Qty" name="Qty" value="1" min="1">
                        </div>
                        <div class="col-md-3">
                            <label>Harga</label>
                            <input type="number" class="form-control" id="Harga" name="Harga" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-primary d-block w-100" onclick="addDetail()">Tambah</button>
                        </div>
                    </div>
                </form>
                
                <table class="table table-bordered" id="detailTable">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Status Gudang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    var table;
    var spareparts = [];
    window.allItemsData = [];

    function syncCustomerDisplay() {
        var plat = jQuery('#PlatNomor option:selected').text();
        if(jQuery('#PlatNomor').val() == '') plat = '';
        var pel = jQuery('#NamaPelanggan').val();
        
        var data = {
            platNomor: plat,
            pelanggan: pel,
            items: spareparts
        };
        localStorage.setItem('SADisplayData', JSON.stringify(data));
    }
    
    jQuery(document).ready(function() {
        <?php if(request()->has('popup')): ?>
        // Automatically initialize the modal contents
        spareparts = [];
        renderTempTable();
        <?php endif; ?>
        jQuery('#PlatNomor').select2({ dropdownParent: jQuery('#addModal') });
        jQuery('#KodePelanggan').select2({ dropdownParent: jQuery('#addModal') });
        jQuery('#KodeMekanik').select2({ dropdownParent: jQuery('#addModal') });
        
        // Auto-select Pelanggan when PlatNomor is selected
        jQuery('#PlatNomor').on('select2:select', function (e) {
            var data = e.params.data;
            var pel = jQuery(this).find('option:selected').data('pelanggan');
            if (pel) {
                jQuery('#KodePelanggan').val(pel).trigger('change');
            }
        });

        jQuery('#PlatNomor').on('change', function() { syncCustomerDisplay(); });

        jQuery('#KodePelanggan').change(function() {
            var name = jQuery(this).find('option:selected').text();
            if(jQuery(this).val() == '') name = '';
            jQuery('#NamaPelanggan').val(name);
            syncCustomerDisplay();
        });

        // Fetch Items
        jQuery.ajax({
            url: "<?php echo e(route('service-advisor.items')); ?>",
            type: "GET",
            success: function(data) {
                window.allItemsData = data;
                renderItemOptions();
            }
        });

        jQuery('#tempKategori').select2({ dropdownParent: jQuery('#addModal') });
        jQuery('#tempKategori').change(function() {
            renderItemOptions();
        });

        function renderItemOptions() {
            var html = '<option value="">-- Pilih Item --</option>';
            var selectedKategori = jQuery('#tempKategori').val();
            
            window.allItemsData.forEach(function(item) {
                if (selectedKategori && item.KodeJenisItem != selectedKategori) return;
                html += `<option value="${item.KodeItem}" data-harga="${item.HargaJual}" data-nama="${item.NamaItem}">${item.NamaItem}</option>`;
            });
            
            jQuery('#tempKodeItem').html(html);
            jQuery('#tempKodeItem').select2({ dropdownParent: jQuery('#addModal') });
            
            jQuery('#KodeItem').html(html);
            jQuery('#KodeItem').select2({ dropdownParent: jQuery('#detailModal') });
        }

        jQuery('#tempKodeItem').change(function() {
            var selected = jQuery(this).find(':selected');
            jQuery('#tempHarga').val(selected.data('harga'));
            jQuery('#tempNamaItem').val(selected.data('nama'));
        });

        jQuery('#KodeItem').change(function() {
            var harga = jQuery(this).find(':selected').data('harga');
            jQuery('#Harga').val(harga);
        });

        table = jQuery('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "<?php echo e(route('service-advisor.getData')); ?>",
                type: "POST",
                data: function (d) {
                    d._token = "<?php echo e(csrf_token()); ?>";
                }
            },
            columns: [
                { data: 'NoPKB' },
                { data: 'PlatNomor' },
                { data: 'NamaPelanggan' },
                { data: 'NamaMekanik' },
                { 
                    data: 'Keluhan',
                    render: function(data) {
                        try {
                            let parsed = JSON.parse(data);
                            if(Array.isArray(parsed)) {
                                return parsed.map(item => '&bull; ' + item.text).join('<br>');
                            }
                        } catch(e) { }
                        return data;
                    }
                },
                { 
                    data: 'StatusServis',
                    render: function(data) {
                        if(data == 0) return '<span class="badge bg-danger">Menunggu</span>';
                        if(data == 1) return '<span class="badge bg-warning text-dark">Dikerjakan</span>';
                        if(data == 2) return '<span class="badge bg-success">Selesai</span>';
                        if(data == 3) return '<span class="badge bg-dark">Batal</span>';
                        return data;
                    }
                },
                { 
                    data: 'NoPKB',
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-info me-1" onclick="openDetail('${data}')"><i class="bi bi-list"></i> Item</button>
                                <button class="btn btn-sm btn-danger" onclick="batalServis(${row.id})"><i class="bi bi-trash"></i> Batal</button>`;
                    }
                }
            ]
        });
    });

    function openAddModal() {
        jQuery('#addForm')[0].reset();
        jQuery('#PlatNomor').val('').trigger('change');
        jQuery('#KodePelanggan').val('').trigger('change');
        jQuery('#KodeMekanik').val('').trigger('change');
        spareparts = [];
        renderTempTable();
        jQuery('#addModal').modal('show');
    }

    function addTempItem() {
        var kode = jQuery('#tempKodeItem').val();
        var nama = jQuery('#tempNamaItem').val();
        var qty = jQuery('#tempQty').val();
        var harga = jQuery('#tempHarga').val();

        if(!kode || !qty) return Swal.fire('Oops', 'Pilih item dan kuantitas terlebih dahulu', 'warning');

        spareparts.push({
            KodeItem: kode,
            NamaItem: nama,
            Qty: parseFloat(qty),
            Harga: parseFloat(harga)
        });

        jQuery('#tempKodeItem').val('').trigger('change');
        jQuery('#tempQty').val(1);
        jQuery('#tempHarga').val('');
        jQuery('#tempNamaItem').val('');

        renderTempTable();
    }

    function removeTempItem(index) {
        spareparts.splice(index, 1);
        renderTempTable();
    }

    function renderTempTable() {
        var tbody = '';
        var total = 0;
        
        if (spareparts.length === 0) {
            tbody = '<tr id="emptyRow"><td colspan="5" class="text-center text-muted">Belum ada item ditambahkan</td></tr>';
        } else {
            spareparts.forEach(function(item, index) {
                var subtotal = item.Qty * item.Harga;
                total += subtotal;
                tbody += `<tr>
                    <td>${item.NamaItem}</td>
                    <td>${item.Qty}</td>
                    <td>${item.Harga.toLocaleString()}</td>
                    <td>${subtotal.toLocaleString()}</td>
                    <td><button type="button" class="btn btn-sm btn-danger py-0 px-2" onclick="removeTempItem(${index})"><i class="bi bi-trash"></i></button></td>
                </tr>`;
            });
        }
        jQuery('#tempItemTable tbody').html(tbody);
        jQuery('#tempTotalLabel').text(total.toLocaleString());
        syncCustomerDisplay();
    }

    function addKeluhanRow() {
        const row = `
            <div class="input-group mb-2 keluhan-row">
                <input type="text" class="form-control" name="Keluhan[]" placeholder="Keluhan / Permintaan" required>
                <button class="btn btn-outline-danger" type="button" onclick="removeKeluhanRow(this)"><i class="bi bi-trash"></i></button>
            </div>
        `;
        jQuery('#keluhanContainer').append(row);
    }

    function removeKeluhanRow(btn) {
        if (jQuery('.keluhan-row').length > 1) {
            jQuery(btn).closest('.keluhan-row').remove();
        } else {
            Swal.fire('Oops', 'Minimal harus ada 1 keluhan/permintaan', 'warning');
        }
    }

    function savePKB() {
        if(!jQuery('#PlatNomor').val()) {
            Swal.fire('Oops!', 'Plat Nomor harus diisi!', 'warning');
            return;
        }
        
        var payload = jQuery('#addForm').serializeArray();
        spareparts.forEach(function(item, index) {
            payload.push({name: `spareparts[${index}][KodeItem]`, value: item.KodeItem});
            payload.push({name: `spareparts[${index}][NamaItem]`, value: item.NamaItem});
            payload.push({name: `spareparts[${index}][Qty]`, value: item.Qty});
            payload.push({name: `spareparts[${index}][Harga]`, value: item.Harga});
        });

        jQuery.ajax({
            url: "<?php echo e(route('service-advisor.store')); ?>",
            type: 'POST',
            data: payload,
            success: function(res) {
                if(res.success) {
                    Swal.fire('Berhasil!', res.message, 'success');
                    <?php if(request()->has('popup')): ?>
                    setTimeout(() => { window.parent.postMessage('advisorSuccess', '*'); }, 1500);
                    <?php else: ?>
                    jQuery('#addModal').modal('hide');
                    <?php endif; ?>
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan data.';
                Swal.fire('Gagal!', msg, 'error');
            }
        });
    }

    function showAddKendaraan() { jQuery('#kendaraanModal').modal('show'); }
    function submitKendaraan() {
        if(!jQuery('input[name="PlatNomor"]', '#kendaraanForm').val()) return Swal.fire('Oops', 'Plat Nomor wajib diisi', 'warning');
        jQuery.ajax({
            url: "<?php echo e(route('service-advisor.kendaraan.store')); ?>",
            type: "POST",
            data: jQuery('#kendaraanForm').serialize(),
            success: function(res) {
                if(res.success) {
                    var newOption = new Option(res.data.PlatNomor, res.data.PlatNomor, true, true);
                    jQuery('#PlatNomor').append(newOption).trigger('change');
                    jQuery('#kendaraanModal').modal('hide');
                    jQuery('#kendaraanForm')[0].reset();
                    Swal.fire('Berhasil', 'Kendaraan ditambahkan', 'success');
                }
            }
        });
    }

    function showAddPelanggan() { jQuery('#pelangganModal').modal('show'); }
    function submitPelanggan() {
        if(!jQuery('input[name="NamaPelanggan"]', '#pelangganForm').val()) return Swal.fire('Oops', 'Nama Pelanggan wajib diisi', 'warning');
        jQuery.ajax({
            url: "<?php echo e(route('service-advisor.pelanggan.store')); ?>",
            type: "POST",
            data: jQuery('#pelangganForm').serialize(),
            success: function(res) {
                if(res.success) {
                    var newOption = new Option(res.data.NamaPelanggan, res.data.KodePelanggan, true, true);
                    jQuery('#KodePelanggan').append(newOption).trigger('change');
                    jQuery('#pelangganModal').modal('hide');
                    jQuery('#pelangganForm')[0].reset();
                    Swal.fire('Berhasil', 'Pelanggan ditambahkan', 'success');
                }
            }
        });
    }

    function batalServis(id) {
        Swal.fire({
            title: 'Batalkan Servis?',
            text: "Kendaraan akan dihapus dari antrean berjalan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: "<?php echo e(url('service-advisor/batal')); ?>/" + id,
                    type: 'POST',
                    data: { _token: "<?php echo e(csrf_token()); ?>" },
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Dibatalkan!', res.message, 'success');
                            table.ajax.reload();
                        }
                    }
                });
            }
        });
    }

    function openDetail(noPkb) {
        jQuery('#modalNoPKB').text(noPkb);
        jQuery('#detailNoPKB').val(noPkb);
        jQuery('#detailModal').modal('show');
        loadDetail(noPkb);
    }

    function loadDetail(noPkb) {
        jQuery.ajax({
            url: "<?php echo e(url('service-advisor/details')); ?>/" + noPkb,
            type: "GET",
            success: function(res) {
                var tbody = '';
                var total = 0;
                res.data.forEach(function(d) {
                    total += parseFloat(d.Subtotal);
                    var stLabel = d.StatusGudang == 1 ? '<span class="badge bg-success">Diserahkan</span>' : (d.StatusGudang == 2 ? '<span class="badge bg-dark">Indent/Kosong</span>' : '<span class="badge bg-warning text-dark">Menunggu</span>');
                    tbody += `<tr>
                        <td>${d.NamaItem}</td>
                        <td>${d.Qty}</td>
                        <td>${parseFloat(d.Harga).toLocaleString()}</td>
                        <td>${parseFloat(d.Subtotal).toLocaleString()}</td>
                        <td>${stLabel}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteDetail(${d.id}, '${noPkb}')"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
                });
                tbody += `<tr><th colspan="3" class="text-end">Total Estimasi</th><th colspan="3">${total.toLocaleString()}</th></tr>`;
                jQuery('#detailTable tbody').html(tbody);
            }
        });
    }

    function addDetail() {
        if(!jQuery('#KodeItem').val()) return Swal.fire('Oops', 'Pilih item dulu', 'warning');
        if(!jQuery('#Qty').val()) return Swal.fire('Oops', 'Isi Qty', 'warning');

        var formData = jQuery('#detailForm').serialize();
        jQuery.ajax({
            url: "<?php echo e(route('service-advisor.details.store')); ?>",
            type: "POST",
            data: formData,
            success: function(res) {
                if(res.success) {
                    jQuery('#KodeItem').val('').trigger('change');
                    jQuery('#Qty').val(1);
                    jQuery('#Harga').val('');
                    loadDetail(jQuery('#detailNoPKB').val());
                }
            }
        });
    }

    function deleteDetail(id, noPkb) {
        jQuery.ajax({
            url: "<?php echo e(url('service-advisor/details/delete')); ?>/" + id,
            type: "POST",
            data: { _token: "<?php echo e(csrf_token()); ?>" },
            success: function(res) {
                if(res.success) {
                    loadDetail(noPkb);
                }
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/MasterData/ServiceAdvisor/index.blade.php ENDPATH**/ ?>