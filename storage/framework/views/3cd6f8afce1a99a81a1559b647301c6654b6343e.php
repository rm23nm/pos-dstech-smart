<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-4">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 px-4">
                        <div class="card card-custom gutter-b bg-transparent shadow-none border-0">
                            <div class="card-header align-items-center border-bottom-dark px-0">
                                <div class="card-title mb-0">
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Jasa Medis & Tindakan</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalJasa" onclick="tambahJasa()">
                                        <i class="fas fa-plus"></i> Tambah Jasa Medis
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableJasa">
                                        <thead>
                                            <tr>
                                                <th>Kode Jasa</th>
                                                <th>Nama Tindakan / Jasa</th>
                                                <th>Harga (Rp)</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $jasas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($j->KodeJasa); ?></td>
                                                <td><?php echo e($j->NamaJasa); ?></td>
                                                <td><?php echo e(number_format($j->Harga, 0, ',', '.')); ?></td>
                                                <td><?php echo e($j->Deskripsi); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editJasa(<?php echo e(json_encode($j)); ?>)"><i class="fas fa-edit"></i></button>
                                                    <a href="<?php echo e(route('klinik-jasa.destroy', $j->id)); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalJasa" tabindex="-1" aria-labelledby="modalJasaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formJasa" method="POST" action="<?php echo e(route('klinik-jasa.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJasaLabel">Tambah Jasa Medis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="KodeJasa" id="KodeJasa" class="form-control" required placeholder="Contoh: JS-001">
                    </div>
                    <div class="mb-3">
                        <label>Nama Tindakan / Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="NamaJasa" id="NamaJasa" class="form-control" required placeholder="Contoh: Konsultasi Dokter Umum">
                    </div>
                    <div class="mb-3">
                        <label>Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="Harga" id="Harga" class="form-control" required placeholder="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="Deskripsi" id="Deskripsi" class="form-control" rows="3" placeholder="Keterangan tindakan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#tableJasa').DataTable();
    });

    function tambahJasa() {
        $('#modalJasaLabel').text('Tambah Jasa Medis');
        $('#formJasa').attr('action', '<?php echo e(route('klinik-jasa.store')); ?>');
        $('#KodeJasa').val('');
        $('#NamaJasa').val('');
        $('#Harga').val('');
        $('#Deskripsi').val('');
        
        // Remove _method input if exists
        $('#formJasa').find('input[name="_method"]').remove();
    }

    function editJasa(data) {
        $('#modalJasaLabel').text('Edit Jasa Medis');
        $('#formJasa').attr('action', '<?php echo e(url('klinik-jasa/update')); ?>/' + data.id);
        
        $('#KodeJasa').val(data.KodeJasa);
        $('#NamaJasa').val(data.NamaJasa);
        $('#Harga').val(data.Harga);
        $('#Deskripsi').val(data.Deskripsi);

        // Remove _method input if exists
        $('#formJasa').find('input[name="_method"]').remove();
        
        $('#modalJasa').modal('show');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/jasa/index.blade.php ENDPATH**/ ?>