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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Rekam Medis (EMR) - Antrean Hari Ini</h3>
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
                                    <table class="table table-bordered table-hover" id="tableEMR">
                                        <thead>
                                            <tr>
                                                <th>No Antrean</th>
                                                <th>No RM</th>
                                                <th>Nama Pasien</th>
                                                <th>Poli</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><span class="badge bg-primary fs-6"><?php echo e($a->NoAntrean); ?></span></td>
                                                <td><?php echo e($a->NoRM); ?></td>
                                                <td>
                                                    <?php echo e($a->NamaPasien); ?>

                                                    <?php if($a->TanggalLahir): ?>
                                                    <br><small class="text-muted"><?php echo e(date_diff(date_create($a->TanggalLahir), date_create('now'))->y); ?> Tahun</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($a->NamaPoli); ?></td>
                                                <td><?php echo e($a->NamaDokter); ?></td>
                                                <td>
                                                    <?php if($a->Status == 'Menunggu'): ?>
                                                        <span class="badge bg-warning text-dark"><?php echo e($a->Status); ?></span>
                                                    <?php elseif($a->Status == 'Diperiksa'): ?>
                                                        <span class="badge bg-info text-dark"><?php echo e($a->Status); ?></span>
                                                    <?php elseif($a->Status == 'Selesai'): ?>
                                                        <span class="badge bg-success text-white"><?php echo e($a->Status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex" style="gap: 5px;">
                                                        <button class="btn btn-sm btn-info text-white panggil-btn" data-id="<?php echo e($a->id); ?>">
                                                            <i class="fas fa-bullhorn"></i> Panggil
                                                        </button>
                                                        <a href="<?php echo e(route('klinik-emr.create', $a->id)); ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-stethoscope"></i> Periksa / EMR
                                                        </a>
                                                    </div>
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

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#tableEMR').DataTable({
            "order": [[ 0, "asc" ]] // Urutkan No Antrean terkecil di atas
        });
    });
</script>
<?php $__env->stopPush(); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.panggil-btn').click(function(e) {
            e.preventDefault();
            var appointmentId = $(this).data('id');
            var btn = $(this);
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memanggil...');

            $.ajax({
                url: '<?php echo e(route('klinik-appointments.panggil')); ?>',
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    id: appointmentId
                },
                success: function(response) {
                    if(response.success) {
                        btn.html('<i class="fas fa-bullhorn"></i> Panggil Ulang');
                        btn.removeClass('btn-info').addClass('btn-warning');
                        // Optional: update the row status to Diperiksa automatically or just reload
                        setTimeout(function(){ location.reload(); }, 1500);
                    } else {
                        alert(response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-bullhorn"></i> Panggil');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem.');
                    btn.prop('disabled', false).html('<i class="fas fa-bullhorn"></i> Panggil');
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/emr/index.blade.php ENDPATH**/ ?>