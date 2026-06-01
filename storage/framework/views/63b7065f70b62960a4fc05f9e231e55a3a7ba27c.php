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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Master Poli</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalPoli" onclick="tambahPoli()">
                                        <i class="fas fa-plus"></i> Tambah Poli
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
                                    <table class="table table-bordered table-hover" id="tablePoli">
                                        <thead>
                                            <tr>
                                                <th>Kode Poli</th>
                                                <th>Nama Poli</th>
                                                <th>Shift / Jam</th>
                                                <th>Dokter Tugas</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $polis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($p->KodePoli); ?></td>
                                                <td><?php echo e($p->NamaPoli); ?></td>
                                                <td><?php echo e($p->Shift); ?> <br><small class="text-muted"><?php echo e($p->JamMulai ? date('H:i', strtotime($p->JamMulai)) : ''); ?> - <?php echo e($p->JamSelesai ? date('H:i', strtotime($p->JamSelesai)) : ''); ?></small></td>
                                                <td><?php echo e($p->NamaDokter ?? '-'); ?></td>
                                                <td><?php echo e($p->Deskripsi); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editPoli(this)" data-bs-toggle="modal" data-bs-target="#modalPoli"
                                                        data-id="<?php echo e($p->id); ?>"
                                                        data-kodepoli="<?php echo e($p->KodePoli); ?>"
                                                        data-namapoli="<?php echo e($p->NamaPoli); ?>"
                                                        data-shift="<?php echo e($p->Shift); ?>"
                                                        data-jammulai="<?php echo e($p->JamMulai); ?>"
                                                        data-jamselesai="<?php echo e($p->JamSelesai); ?>"
                                                        data-doctorid="<?php echo e($p->DoctorID); ?>"
                                                        data-videourl="<?php echo e($p->VideoURL); ?>"
                                                        data-deskripsi="<?php echo e($p->Deskripsi); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="<?php echo e(route('klinik-poli.destroy', $p->id)); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
<div class="modal fade" id="modalPoli" tabindex="-1" aria-labelledby="modalPoliLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPoli" method="POST" action="<?php echo e(route('klinik-poli.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPoliLabel">Tambah Poli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Poli <span class="text-danger">*</span></label>
                        <input type="text" name="KodePoli" id="KodePoli" class="form-control" required placeholder="Contoh: UMUM, GIGI">
                    </div>
                    <div class="mb-3">
                        <label>Nama Poli <span class="text-danger">*</span></label>
                        <input type="text" name="NamaPoli" id="NamaPoli" class="form-control" required placeholder="Contoh: Poli Umum">
                    </div>
                    <div class="mb-3">
                        <label>Nama Shift / Keterangan</label>
                        <input type="text" name="Shift" id="Shift" class="form-control" placeholder="Contoh: Pagi, Malam, Ruang 1">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Jam Mulai</label>
                            <input type="time" name="JamMulai" id="JamMulai" class="form-control">
                        </div>
                        <div class="col-6">
                            <label>Jam Selesai</label>
                            <input type="time" name="JamSelesai" id="JamSelesai" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Dokter Bertugas</label>
                        <select name="DoctorID" id="DoctorID" class="form-control">
                            <option value="">-- Pilih Dokter --</option>
                            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->NamaDokter); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Video Promo / Youtube URL</label>
                        <input type="text" name="VideoURL" id="VideoURL" class="form-control" placeholder="Contoh: https://www.youtube.com/embed/... atau link .mp4">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="Deskripsi" id="Deskripsi" class="form-control" rows="3" placeholder="Deskripsi/Keterangan Poli"></textarea>
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
        $('#tablePoli').DataTable();
    });

    function tambahPoli() {
        $('#modalPoliLabel').text('Tambah Poli');
        $('#formPoli').attr('action', '<?php echo e(route('klinik-poli.store')); ?>');
        $('#KodePoli').val('');
        $('#NamaPoli').val('');
        $('#Shift').val('');
        $('#JamMulai').val('');
        $('#JamSelesai').val('');
        $('#DoctorID').val('');
        $('#VideoURL').val('');
        $('#Deskripsi').val('');

        // Remove _method input if exists
        $('#formPoli').find('input[name="_method"]').remove();
    }

    function editPoli(btn) {
        var $btn = $(btn);
        $('#modalPoliLabel').text('Edit Poli');
        $('#formPoli').attr('action', '<?php echo e(url('klinik-poli/update')); ?>/' + $btn.data('id'));

        $('#KodePoli').val($btn.data('kodepoli'));
        $('#NamaPoli').val($btn.data('namapoli'));
        $('#Shift').val($btn.data('shift'));
        $('#JamMulai').val($btn.data('jammulai'));
        $('#JamSelesai').val($btn.data('jamselesai'));
        $('#DoctorID').val($btn.data('doctorid'));
        $('#VideoURL').val($btn.data('videourl'));
        $('#Deskripsi').val($btn.data('deskripsi'));

        // Remove _method input if exists
        $('#formPoli').find('input[name="_method"]').remove();
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/poli/index.blade.php ENDPATH**/ ?>