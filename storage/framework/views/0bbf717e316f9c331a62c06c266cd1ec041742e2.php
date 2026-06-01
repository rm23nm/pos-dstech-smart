<?php $__env->startSection('content'); ?>

<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Pengaturan Display & Loket Pendaftaran</li>
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
                    <h3 class="card-label mb-0 font-weight-bold text-body">Daftar Loket Fisik Kiosk
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoketModal">
                        <i class="fas fa-plus"></i> Tambah Loket
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">Data loket di bawah ini akan digambar sebagai kotak-kotak loket pada layar TV Kiosk Pendaftaran. Anda juga perlu memilih loket ini saat akan memanggil pasien.</p>
                
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Loket</th>
                            <th>Melayani Jalur</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $lokets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($l->NamaLoket); ?></td>
                            <td>
                                <?php if(($l->TipeAntrean ?? 'Semua') == 'Semua'): ?>
                                    <span class="badge bg-secondary text-white">Semua Jalur</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-white"><?php echo e($l->TipeAntrean); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($l->isActive): ?>
                                    <span class="badge bg-success text-white">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editLoketModal<?php echo e($l->id); ?>"><i class="fas fa-edit"></i></button>
                                <form action="<?php echo e(route('klinik-loket.delete')); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus loket ini?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($l->id); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editLoketModal<?php echo e($l->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?php echo e(route('klinik-loket.update')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($l->id); ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Loket</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama Loket</label>
                                                <input type="text" class="form-control" name="NamaLoket" value="<?php echo e($l->NamaLoket); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Melayani Jalur Antrean</label>
                                                <select class="form-control" name="TipeAntrean">
                                                    <option value="Semua" <?php echo e(($l->TipeAntrean ?? 'Semua') == 'Semua' ? 'selected' : ''); ?>>Semua Jalur</option>
                                                    <option value="Umum" <?php echo e(($l->TipeAntrean ?? '') == 'Umum' ? 'selected' : ''); ?>>Jalur Umum</option>
                                                    <option value="BPJS" <?php echo e(($l->TipeAntrean ?? '') == 'BPJS' ? 'selected' : ''); ?>>Jalur BPJS</option>
                                                    <option value="Asuransi" <?php echo e(($l->TipeAntrean ?? '') == 'Asuransi' ? 'selected' : ''); ?>>Jalur Asuransi Swasta</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select class="form-control" name="isActive">
                                                    <option value="1" <?php echo e($l->isActive ? 'selected' : ''); ?>>Aktif</option>
                                                    <option value="0" <?php echo e(!$l->isActive ? 'selected' : ''); ?>>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card card-custom gutter-b bg-white border-0 mt-5">
            <div class="card-header align-items-center border-0">
                <div class="card-title mb-0">
                    <h3 class="card-label mb-0 font-weight-bold text-body">Link Layar TV Kiosk
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <p>Gunakan link di bawah ini untuk ditampilkan di Layar TV Pendaftaran Anda. Video Promosi dan Background Kiosk dapat diatur di <a href="<?php echo e(route('companysetting')); ?>">Pengaturan Perusahaan</a>.</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="<?php echo e(route('klinik-display')); ?>" readonly id="displayUrl">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyUrl()">Copy</button>
                    <a href="<?php echo e(route('klinik-display')); ?>" target="_blank" class="btn btn-primary">Buka Layar TV</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="addLoketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('klinik-loket.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Loket Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Loket (Contoh: LOKET 1, LOKET UMUM)</label>
                        <input type="text" class="form-control" name="NamaLoket" required>
                    </div>
                    <div class="mb-3">
                        <label>Melayani Jalur Antrean</label>
                        <select class="form-control" name="TipeAntrean">
                            <option value="Semua">Semua Jalur</option>
                            <option value="Umum">Jalur Umum</option>
                            <option value="BPJS">Jalur BPJS</option>
                            <option value="Asuransi">Jalur Asuransi Swasta</option>
                        </select>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function copyUrl() {
        var copyText = document.getElementById("displayUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Copied the text: " + copyText.value);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/loket/index.blade.php ENDPATH**/ ?>