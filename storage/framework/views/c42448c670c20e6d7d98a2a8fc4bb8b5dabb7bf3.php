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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Jadwal Dokter</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalDokter" onclick="tambahDokter()">
                                        <i class="fas fa-plus"></i> Tambah Dokter
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
                                    <table class="table table-bordered table-hover" id="tableDokter">
                                        <thead>
                                            <tr>
                                                <th>Nama Dokter</th>
                                                <th>Poli</th>
                                                <th>Spesialisasi</th>
                                                <th>No HP</th>
                                                <th>Jadwal Praktek</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($d->NamaDokter); ?></td>
                                                <td><?php echo e($d->NamaPoli ?? '-'); ?></td>
                                                <td><?php echo e($d->Spesialisasi); ?></td>
                                                <td><?php echo e($d->NoHP); ?></td>
                                                <td><?php echo e($d->JadwalPraktik); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalDokter" 
                                                            onclick="editDokter(<?php echo e($d->id); ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="<?php echo e(route('klinik-doctors.destroy', $d->id)); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
<div class="modal fade" id="modalDokter" tabindex="-1" aria-labelledby="modalDokterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formDokter" method="POST" action="<?php echo e(route('klinik-doctors.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDokterLabel">Tambah Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" name="NamaDokter" id="NamaDokter" class="form-control" required placeholder="Contoh: dr. Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label>Poli</label>
                        <select name="PoliID" id="PoliID" class="form-control">
                            <option value="">- Pilih Poli (Opsional) -</option>
                            <?php $__currentLoopData = $polis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pl->id); ?>"><?php echo e($pl->NamaPoli); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Spesialisasi</label>
                        <input type="text" name="Spesialisasi" id="Spesialisasi" class="form-control" placeholder="Contoh: Dokter Umum, Dokter Gigi">
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="NoHP" id="NoHP" class="form-control" placeholder="Contoh: 0812xxxx">
                    </div>
                    <div class="mb-3">
                        <label>Jadwal Praktek</label>
                        <textarea name="JadwalPraktik" id="JadwalPraktik" class="form-control" rows="3" placeholder="Contoh: Senin - Jumat, 08:00 - 15:00"></textarea>
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
    var doctorsData = {};
    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        doctorsData[<?php echo e($d->id); ?>] = {
            id: <?php echo e($d->id); ?>,
            NamaDokter: <?php echo json_encode($d->NamaDokter); ?>,
            PoliID: <?php echo json_encode($d->PoliID); ?>,
            Spesialisasi: <?php echo json_encode($d->Spesialisasi); ?>,
            NoHP: <?php echo json_encode($d->NoHP); ?>,
            JadwalPraktik: <?php echo json_encode($d->JadwalPraktik); ?>

        };
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    $(document).ready(function() {
        $('#tableDokter').DataTable();
    });

    function tambahDokter() {
        $('#modalDokterLabel').text('Tambah Dokter');
        $('#formDokter').attr('action', '<?php echo e(route('klinik-doctors.store')); ?>');
        $('#NamaDokter').val('');
        $('#PoliID').val('');
        $('#Spesialisasi').val('');
        $('#NoHP').val('');
        $('#JadwalPraktik').val('');
        
        // Remove _method input if exists
        $('#formDokter').find('input[name="_method"]').remove();
    }

    function editDokter(id) {
        var d = doctorsData[id];
        if(!d) return;

        $('#modalDokterLabel').text('Edit Dokter');
        $('#formDokter').attr('action', '<?php echo e(url('klinik-doctors/update')); ?>/' + d.id);
        
        $('#NamaDokter').val(d.NamaDokter);
        $('#PoliID').val(d.PoliID);
        $('#Spesialisasi').val(d.Spesialisasi);
        $('#NoHP').val(d.NoHP);
        $('#JadwalPraktik').val(d.JadwalPraktik);

        // Remove _method input if exists
        $('#formDokter').find('input[name="_method"]').remove();
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/doctors/index.blade.php ENDPATH**/ ?>