<?php $__env->startSection('content'); ?>
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">
                Data Booking Bengkel
            </h5>
        </div>
    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-custom mb-3">
            <div class="card-body">
                <form action="<?php echo e(route('admin.booking.bengkel')); ?>" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label>Tanggal Awal</label>
                            <input type="date" class="form-control" name="StartDate" value="<?php echo e($startDate); ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" name="EndDate" value="<?php echo e($endDate); ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="StatusBooking" class="form-control">
                                <option value="Semua" <?php echo e($status == 'Semua' ? 'selected' : ''); ?>>Semua Status</option>
                                <option value="0" <?php echo e($status == '0' ? 'selected' : ''); ?>>Pending</option>
                                <option value="1" <?php echo e($status == '1' ? 'selected' : ''); ?>>Dikonfirmasi</option>
                                <option value="2" <?php echo e($status == '2' ? 'selected' : ''); ?>>Selesai (PKB)</option>
                                <option value="3" <?php echo e($status == '3' ? 'selected' : ''); ?>>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Cari Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-custom">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bookingTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal Booking</th>
                                <th>Jam</th>
                                <th>Plat Nomor</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(date('d-m-Y', strtotime($b->TglBooking))); ?></td>
                                <td><?php echo e(date('H:i', strtotime($b->JamBooking))); ?></td>
                                <td><span class="badge badge-dark"><?php echo e($b->PlatNomor); ?></span></td>
                                <td><?php echo e($b->NamaPelanggan); ?></td>
                                <td>
                                    <a href="https://wa.me/<?php echo e(preg_replace('/^08/', '628', $b->NoHP)); ?>" target="_blank" class="text-success font-weight-bold">
                                        <i class="fab fa-whatsapp text-success"></i> <?php echo e($b->NoHP); ?>

                                    </a>
                                </td>
                                <td><?php echo e($b->Keluhan); ?></td>
                                <td>
                                    <?php if($b->StatusBooking == 0): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif($b->StatusBooking == 1): ?>
                                        <span class="badge badge-info">Dikonfirmasi</span>
                                    <?php elseif($b->StatusBooking == 2): ?>
                                        <span class="badge badge-success">Selesai (PKB)</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($b->StatusBooking == 0): ?>
                                        <button class="btn btn-sm btn-info btn-confirm" data-id="<?php echo e($b->id); ?>" data-status="1">Konfirmasi</button>
                                        <button class="btn btn-sm btn-danger btn-confirm" data-id="<?php echo e($b->id); ?>" data-status="3">Batal</button>
                                    <?php elseif($b->StatusBooking == 1): ?>
                                        <button class="btn btn-sm btn-success btn-confirm" data-id="<?php echo e($b->id); ?>" data-status="2" title="Tandai masuk antrean PKB">Proses PKB</button>
                                    <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    jQuery(document).ready(function() {
        jQuery('#bookingTable').DataTable({
            responsive: true,
            order: [[0, 'desc'], [1, 'asc']]
        });

        jQuery('.btn-confirm').click(function() {
            let id = jQuery(this).data('id');
            let status = jQuery(this).data('status');
            let actionText = jQuery(this).text();

            Swal.fire({
                title: 'Ubah Status',
                text: `Anda yakin ingin merubah status menjadi ${actionText}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    jQuery.ajax({
                        url: "<?php echo e(route('admin-booking.update')); ?>",
                        type: 'POST',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            id: id,
                            StatusBooking: status
                        },
                        success: function(res) {
                            if(res.success) {
                                Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/MasterData/BookingBengkel/admin_index.blade.php ENDPATH**/ ?>