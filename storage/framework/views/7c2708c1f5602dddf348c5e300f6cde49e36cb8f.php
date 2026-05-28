<?php $__env->startSection('content'); ?>
<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Manajemen Bengkel</li>
                <li class="breadcrumb-item active" aria-current="page">Master Kendaraan</li>
            </ol>
        </nav>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-title">
                    <h3 class="card-label">Data Kendaraan</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKendaraan" onclick="resetForm()">
                        <i class="fas fa-plus"></i> Tambah Kendaraan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableData">
                        <thead>
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Merek</th>
                                <th>Jenis</th>
                                <th>Tipe</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalKendaraan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKendaraan">
                <div class="modal-body">
                    <input type="hidden" id="edit_mode" value="0">
                    <input type="hidden" id="KodeKendaraan" name="KodeKendaraan">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Plat Nomor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="PlatNomor" id="PlatNomor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Merek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="Merek" id="Merek" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kendaraan</label>
                            <select class="form-control" name="JenisKendaraan" id="JenisKendaraan">
                                <option value="Mobil">Mobil</option>
                                <option value="Motor">Motor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tipe</label>
                            <input type="text" class="form-control" name="Tipe" id="Tipe">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tahun</label>
                            <input type="number" class="form-control" name="Tahun" id="Tahun">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Warna</label>
                            <input type="text" class="form-control" name="Warna" id="Warna">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No Mesin</label>
                            <input type="text" class="form-control" name="NoMesin" id="NoMesin">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No Rangka</label>
                            <input type="text" class="form-control" name="NoRangka" id="NoRangka">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Nama di STNK</label>
                            <input type="text" class="form-control" name="NamaSTNK" id="NamaSTNK">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Email Pelanggan (Untuk Sinkronisasi)</label>
                            <input type="email" class="form-control" name="Email" id="Email" placeholder="contoh@email.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    var table;
    jQuery(document).ready(function() {
        table = jQuery('#tableData').DataTable({
            processing: true,
            ajax: {
                url: "<?php echo e(route('kendaraan.getData')); ?>",
                type: "POST",
                data: { _token: "<?php echo e(csrf_token()); ?>" }
            },
            columns: [
                { data: 'PlatNomor' },
                { data: 'Merek' },
                { data: 'JenisKendaraan' },
                { data: 'Tipe' },
                { data: 'Tahun' },
                { data: 'Warna' },
                { data: 'KodeKendaraan', render: function(data, type, row) {
                    let r = btoa(unescape(encodeURIComponent(JSON.stringify(row))));
                    return `<button class="btn btn-sm btn-warning" onclick="editData('${r}')"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteData('${data}')"><i class="fas fa-trash"></i> Hapus</button>`;
                }}
            ]
        });

        jQuery('#formKendaraan').on('submit', function(e) {
            e.preventDefault();
            let isEdit = jQuery('#edit_mode').val() == '1';
            let url = isEdit ? "<?php echo e(url('kendaraan/update')); ?>/" + jQuery('#KodeKendaraan').val() : "<?php echo e(route('kendaraan.store')); ?>";
            
            jQuery.ajax({
                url: url,
                type: "POST",
                data: jQuery(this).serialize() + "&_token=<?php echo e(csrf_token()); ?>",
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Berhasil', res.message, 'success');
                        jQuery('#modalKendaraan').modal('hide');
                        table.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function(err) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                }
            });
        });
    });

    function resetForm() {
        jQuery('#formKendaraan')[0].reset();
        jQuery('#edit_mode').val('0');
        jQuery('#KodeKendaraan').val('');
        jQuery('#modalTitle').text('Tambah Kendaraan');
    }

    function editData(rowBase64) {
        resetForm();
        let row = JSON.parse(decodeURIComponent(escape(atob(rowBase64))));
        
        jQuery('#KodeKendaraan').val(row.KodeKendaraan);
        jQuery('#PlatNomor').val(row.PlatNomor);
        jQuery('#Merek').val(row.Merek);
        jQuery('#JenisKendaraan').val(row.JenisKendaraan || 'Mobil');
        jQuery('#Tipe').val(row.Tipe);
        jQuery('#Tahun').val(row.Tahun);
        jQuery('#Warna').val(row.Warna);
        jQuery('#NoMesin').val(row.NoMesin);
        jQuery('#NoRangka').val(row.NoRangka);
        jQuery('#NamaSTNK').val(row.NamaSTNK);
        jQuery('#Email').val(row.Email);
        
        jQuery('#edit_mode').val('1');
        jQuery('#modalTitle').text('Edit Kendaraan');
        jQuery('#modalKendaraan').modal('show');
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data kendaraan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url: "<?php echo e(route('kendaraan.destroy')); ?>",
                    type: "POST",
                    data: { id: id, _token: "<?php echo e(csrf_token()); ?>" },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Terhapus!', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/MasterData/Kendaraan/index.blade.php ENDPATH**/ ?>