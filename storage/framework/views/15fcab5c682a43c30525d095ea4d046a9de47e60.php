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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">
                                        <a href="<?php echo e(route('klinik-emr')); ?>" class="btn btn-sm btn-secondary me-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                                        Form Rekam Medis (EMR)
                                    </h3>
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

                <div class="row">
                    <!-- Kolom Info Pasien -->
                    <div class="col-md-4 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-header">
                                <h4 class="card-title mb-0 pt-4">Informasi Pasien</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-sm">
                                    <tr><th width="40%">No Antrean</th><td>: <span class="badge bg-primary"><?php echo e($appointment->NoAntrean); ?></span></td></tr>
                                    <tr><th>No RM</th><td>: <?php echo e($appointment->NoRM); ?></td></tr>
                                    <tr><th>Nama Pasien</th><td>: <strong><?php echo e($appointment->NamaPasien); ?></strong></td></tr>
                                    <tr><th>Tgl Lahir / Umur</th><td>: <?php echo e($appointment->TanggalLahir ? date('d-m-Y', strtotime($appointment->TanggalLahir)) . ' (' . date_diff(date_create($appointment->TanggalLahir), date_create('now'))->y . ' thn)' : '-'); ?></td></tr>
                                    <tr><th>Jenis Kelamin</th><td>: <?php echo e($appointment->JenisKelamin == 'L' ? 'Laki-Laki' : ($appointment->JenisKelamin == 'P' ? 'Perempuan' : '-')); ?></td></tr>
                                    <tr><th>Gol. Darah</th><td>: <?php echo e($appointment->GolonganDarah ?: '-'); ?></td></tr>
                                    <tr><th>Riwayat Alergi</th><td class="text-danger">: <strong><?php echo e($appointment->RiwayatAlergi ?: 'Tidak Ada'); ?></strong></td></tr>
                                </table>
                                <hr>
                                <table class="table table-borderless table-sm">
                                    <tr><th width="40%">Poli Tujuan</th><td>: <?php echo e($appointment->NamaPoli); ?></td></tr>
                                    <tr><th>Dokter</th><td>: <?php echo e($appointment->NamaDokter); ?></td></tr>
                                    <tr><th>Keluhan Awal</th><td>: <?php echo e($appointment->CatatanPendaftaran ?: '-'); ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Input EMR -->
                    <div class="col-md-8 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                                <form action="<?php echo e(route('klinik-emr.store', $appointment->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Keluhan Utama (Anamnesa)</label>
                                            <textarea name="Keluhan" class="form-control" rows="3"><?php echo e($emr->Keluhan ?? $appointment->CatatanPendaftaran); ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Pemeriksaan Fisik / Objektif</label>
                                            <textarea name="PemeriksaanFisik" class="form-control" rows="3" placeholder="Suhu: ... TD: ... Nadi: ... dll"><?php echo e($emr->PemeriksaanFisik ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Diagnosa (Assessment) <span class="text-danger">*</span></label>
                                            <textarea name="Diagnosa" class="form-control" rows="3" required placeholder="Tuliskan diagnosa penyakit berdasarkan ICD atau keluhan klinis"><?php echo e($emr->Diagnosa ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Tindakan (Plan)</label>
                                            <textarea name="Tindakan" class="form-control" rows="3" placeholder="Tindakan medis yang dilakukan"><?php echo e($emr->Tindakan ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Resep Obat</label>
                                            <textarea name="ResepObat" class="form-control" rows="3" placeholder="Tuliskan resep obat untuk apotek"><?php echo e($emr->ResepObat ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Catatan Tambahan / Edukasi</label>
                                            <textarea name="CatatanTambahan" class="form-control" rows="2"><?php echo e($emr->CatatanTambahan ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Rekam Medis & Selesaikan Antrean</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/emr/create.blade.php ENDPATH**/ ?>