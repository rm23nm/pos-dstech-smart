<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Area - <?php echo e($company->NamaCompany ?? 'Bengkel'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; color: #0d6efd !important; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 12px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #edf2f9; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('booking-bengkel.index', $kodePartner)); ?>">
                <i class="fas fa-tools me-2"></i> <?php echo e($company->NamaCompany ?? 'Bengkel Smart'); ?>

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <a href="<?php echo e(route('booking-bengkel.index', $kodePartner)); ?>" class="btn btn-outline-secondary me-2 mt-2 mt-lg-0">Kembali ke Booking</a>
                <a href="<?php echo e(route('booking-bengkel.logout', $kodePartner)); ?>" class="btn btn-danger fw-bold mt-2 mt-lg-0"><i class="fas fa-sign-out-alt me-1"></i> Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Sidebar Profile -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo e($pelanggan->NamaPelanggan); ?></h4>
                        <p class="text-muted mb-3"><?php echo e($pelanggan->NoTlp1); ?> | <?php echo e($pelanggan->Email); ?></p>
                        
                        <div class="d-flex justify-content-between text-start mt-4 border-top pt-3">
                            <span class="text-muted"><i class="fas fa-id-card me-2"></i>ID Pelanggan</span>
                            <span class="fw-bold"><?php echo e($pelanggan->KodePelanggan); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Servis -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="fas fa-history me-2"></i>Riwayat Servis Kendaraan</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if($history && count($history) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">No Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Total (Rp)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary"><?php echo e($trx->NoTransaksi ?? $trx->id); ?></td>
                                                <td><?php echo e(isset($trx->TglTransaksi) ? \Carbon\Carbon::parse($trx->TglTransaksi)->format('d M Y, H:i') : '-'); ?></td>
                                                <td><?php echo e(isset($trx->TotalTransaksi) ? number_format($trx->TotalTransaksi, 0, ',', '.') : '-'); ?></td>
                                                <td>
                                                    <span class="badge bg-success rounded-pill">Selesai</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted mb-3" style="font-size: 48px;">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="fw-bold">Belum Ada Riwayat</h5>
                                <p class="text-muted">Anda belum memiliki riwayat servis di bengkel ini.</p>
                                <a href="<?php echo e(route('booking-bengkel.index', $kodePartner)); ?>" class="btn btn-primary mt-2">Buat Booking Sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; <?php echo e(date('Y')); ?> <?php echo e($company->NamaCompany ?? 'Bengkel Smart'); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/Booking/Bengkel/dashboard.blade.php ENDPATH**/ ?>