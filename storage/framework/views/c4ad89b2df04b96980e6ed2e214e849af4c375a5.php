<?php $__env->startSection('content'); ?>

<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Master Data / Asuransi Swasta</li>
            </ol>
        </nav>
    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="card card-custom gutter-b bg-white border-0">
            <div class="card-header align-items-center border-0">
                <div class="card-title mb-0">
                    <h3 class="card-label mb-0 font-weight-bold text-body">Daftar Asuransi Swasta
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAsuransiModal">
                        <i class="fas fa-plus"></i> Tambah Asuransi
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">Kelola daftar asuransi swasta yang bekerjasama dengan klinik. Daftar ini akan muncul saat pendaftaran pasien baru.</p>

                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Asuransi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $asuransis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($a->NamaAsuransi); ?></td>
                                <td>
                                    <?php if($a->isActive): ?>
                                        <span class="badge bg-success text-white">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAsuransiModal<?php echo e($a->id); ?>"><i class="fas fa-edit"></i></button>
                                    <form action="<?php echo e(route('klinik-asuransi.delete')); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus asuransi ini?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($a->id); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editAsuransiModal<?php echo e($a->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?php echo e(route('klinik-asuransi.update')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($a->id); ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Asuransi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Asuransi</label>
                                                    <input type="text" class="form-control" name="NamaAsuransi" value="<?php echo e($a->NamaAsuransi); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select class="form-control" name="isActive">
                                                        <option value="1" <?php echo e($a->isActive ? 'selected' : ''); ?>>Aktif</option>
                                                        <option value="0" <?php echo e(!$a->isActive ? 'selected' : ''); ?>>Tidak Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(count($asuransis) == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data asuransi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addAsuransiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('klinik-asuransi.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Asuransi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Asuransi (Misal: Prudential, Admedika)</label>
                        <input type="text" class="form-control" name="NamaAsuransi" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" name="isActive">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/asuransi/index.blade.php ENDPATH**/ ?>