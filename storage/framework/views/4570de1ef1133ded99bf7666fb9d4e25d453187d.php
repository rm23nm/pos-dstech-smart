<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Aktivasi | DSMS POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-activation {
            max-width: 500px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }
    </style>
</head>
<body>
    <div class="card card-activation p-4 p-md-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Aktivasi Lisensi</h3>
            <p class="text-muted">Masukkan Serial Number Anda untuk mulai menggunakan aplikasi.</p>
        </div>
        
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('offline.activate')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="form-label fw-semibold">Serial Number</label>
                <input class="form-control form-control-lg text-center fw-bold" style="letter-spacing: 2px;" type="text" name="license_key" placeholder="DSMS-POS-XXXX" required autocomplete="off" />
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Aktivasi Sekarang</button>
        </form>
    </div>
</body>
</html>
<?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/auth/activation.blade.php ENDPATH**/ ?>