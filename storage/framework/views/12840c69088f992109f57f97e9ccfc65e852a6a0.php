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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Pendaftaran Pasien</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalPasien" onclick="tambahPasien()">
                                        <i class="fas fa-plus"></i> Tambah Pasien
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
                                    <table class="table table-bordered table-hover" id="tablePasien">
                                        <thead>
                                            <tr>
                                                <th>No RM</th>
                                                <th>NIK</th>
                                                <th>Nama Pasien</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Gender</th>
                                                <th>No HP</th>
                                                <th>Gol. Darah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($p->NoRM); ?></td>
                                                <td><?php echo e($p->NIK); ?></td>
                                                <td><?php echo e($p->NamaPasien); ?></td>
                                                <td><?php echo e($p->TanggalLahir ? date('d-m-Y', strtotime($p->TanggalLahir)) : '-'); ?></td>
                                                <td><?php echo e($p->JenisKelamin == 'L' ? 'Laki-Laki' : ($p->JenisKelamin == 'P' ? 'Perempuan' : '-')); ?></td>
                                                <td><?php echo e($p->NoHP); ?></td>
                                                <td><?php echo e($p->GolonganDarah); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editPasien(<?php echo e(json_encode($p)); ?>)"><i class="fas fa-edit"></i></button>
                                                    <a href="<?php echo e(route('klinik-patients.destroy', $p->id)); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
<div class="modal fade" id="modalPasien" tabindex="-1" aria-labelledby="modalPasienLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formPasien" method="POST" action="<?php echo e(route('klinik-patients.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPasienLabel">Tambah Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NIK</label>
                            <input type="text" name="NIK" id="NIK" class="form-control" placeholder="Masukkan NIK">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="NamaPasien" id="NamaPasien" class="form-control" required placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="TanggalLahir" id="TanggalLahir" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="JenisKelamin" id="JenisKelamin" class="form-control">
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No HP</label>
                            <input type="text" name="NoHP" id="NoHP" class="form-control" placeholder="08xxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Golongan Darah</label>
                            <select name="GolonganDarah" id="GolonganDarah" class="form-control">
                                <option value="">- Pilih Golongan Darah -</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Alamat</label>
                            <textarea name="Alamat" id="Alamat" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Riwayat Alergi (Jika Ada)</label>
                            <textarea name="RiwayatAlergi" id="RiwayatAlergi" class="form-control" rows="2" placeholder="Cth: Alergi Penisilin, Seafood"></textarea>
                        </div>
                        <div class="col-md-12 mt-2 mb-2">
                            <h6 class="font-weight-bold text-primary border-bottom pb-2"><i class="fas fa-shield-alt"></i> Data Asuransi & BPJS (Opsional)</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>No Kartu BPJS</label>
                            <div class="input-group">
                                <input type="text" name="NoKartuBPJS" id="NoKartuBPJS" class="form-control" placeholder="13 Digit Nomor BPJS">
                                <button class="btn btn-outline-primary" type="button" id="btnCekBpjs"><i class="fas fa-search"></i> Cek Kartu</button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Kelas Rawat BPJS</label>
                            <input type="text" name="KelasRawatBPJS" id="KelasRawatBPJS" class="form-control" placeholder="Otomatis terisi" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Faskes Tingkat 1 BPJS</label>
                            <input type="text" name="FaskesBPJS" id="FaskesBPJS" class="form-control" placeholder="Otomatis terisi" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Status BPJS</label>
                            <input type="text" name="StatusBPJS" id="StatusBPJS" class="form-control" placeholder="Otomatis terisi" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Provider Asuransi Lain</label>
                            <select name="ProviderAsuransiLain" id="ProviderAsuransiLain" class="form-control">
                                <option value="">-- Pilih Asuransi --</option>
                                <?php $__currentLoopData = $asuransis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asuransi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($asuransi->NamaAsuransi); ?>"><?php echo e($asuransi->NamaAsuransi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>No Kartu Asuransi Lain</label>
                            <input type="text" name="NoKartuAsuransiLain" id="NoKartuAsuransiLain" class="form-control" placeholder="Nomor Polis / Kartu">
                        </div>
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
        $('#tablePasien').DataTable();
    });

    function tambahPasien() {
        $('#modalPasienLabel').text('Tambah Pasien');
        $('#formPasien').attr('action', '<?php echo e(route('klinik-patients.store')); ?>');
        $('#NIK').val('');
        $('#NamaPasien').val('');
        $('#TanggalLahir').val('');
        $('#JenisKelamin').val('');
        $('#NoHP').val('');
        $('#GolonganDarah').val('');
        $('#Alamat').val('');
        $('#RiwayatAlergi').val('');
        $('#NoKartuBPJS').val('');
        $('#ProviderAsuransiLain').val('');
        $('#NoKartuAsuransiLain').val('');
        
        // Remove _method input if exists
        $('#formPasien').find('input[name="_method"]').remove();
    }

    function editPasien(data) {
        $('#modalPasienLabel').text('Edit Pasien');
        // Because update route is POST with {id} parameter
        $('#formPasien').attr('action', '<?php echo e(url('klinik-patients/update')); ?>/' + data.id);
        
        $('#NIK').val(data.NIK);
        $('#NamaPasien').val(data.NamaPasien);
        if(data.TanggalLahir) {
            $('#TanggalLahir').val(data.TanggalLahir.split(' ')[0]); // Get YYYY-MM-DD
        } else {
            $('#TanggalLahir').val('');
        }
        $('#JenisKelamin').val(data.JenisKelamin);
        $('#NoHP').val(data.NoHP);
        $('#GolonganDarah').val(data.GolonganDarah);
        $('#Alamat').val(data.Alamat);
        $('#RiwayatAlergi').val(data.RiwayatAlergi);
        $('#NoKartuBPJS').val(data.NoKartuBPJS);
        $('#KelasRawatBPJS').val(data.KelasRawatBPJS);
        $('#FaskesBPJS').val(data.FaskesBPJS);
        $('#StatusBPJS').val(data.StatusBPJS);
        $('#ProviderAsuransiLain').val(data.ProviderAsuransiLain);
        $('#NoKartuAsuransiLain').val(data.NoKartuAsuransiLain);

        // Remove _method input if exists
        $('#formPasien').find('input[name="_method"]').remove();
        
        $('#modalPasien').modal('show');
    }

    $(document).on('click', '#btnCekBpjs', function() {
        var noKartu = $('#NoKartuBPJS').val();
        if(!noKartu) {
            Swal.fire("Peringatan", "Masukkan Nomor Kartu BPJS terlebih dahulu!", "warning");
            return;
        }

        $(this).html('<i class="fas fa-spinner fa-spin"></i> Mengecek...');
        $(this).prop('disabled', true);

        $.ajax({
            url: "<?php echo e(route('klinik-bpjs.cek')); ?>",
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                noKartu: noKartu
            },
            success: function(res) {
                $('#btnCekBpjs').html('<i class="fas fa-search"></i> Cek Kartu');
                $('#btnCekBpjs').prop('disabled', false);

                if(res.success) {
                    var info = `
                        <table class="table table-sm text-left">
                            <tr><th>Nama</th><td>: ${res.data.nama}</td></tr>
                            <tr><th>Status</th><td>: ${res.data.statusPeserta.keterangan}</td></tr>
                            <tr><th>Kelas Rawat</th><td>: ${res.data.kelasRawat.keterangan}</td></tr>
                            <tr><th>Jenis Peserta</th><td>: ${res.data.jenisPeserta.keterangan}</td></tr>
                            <tr><th>Faskes Umum</th><td>: ${res.data.provUmum.nmProvider}</td></tr>
                        </table>
                    `;
                    Swal.fire({
                        title: "Data BPJS Ditemukan",
                        html: info,
                        icon: "success"
                    });
                    
                    // Auto fill nama pasien jika masih kosong
                    if($('#NamaPasien').val() == '') {
                        $('#NamaPasien').val(res.data.nama);
                    }
                    // Auto fill BPJS fields
                    $('#KelasRawatBPJS').val(res.data.kelasRawat.keterangan);
                    $('#FaskesBPJS').val(res.data.provUmum.nmProvider);
                    $('#StatusBPJS').val(res.data.statusPeserta.keterangan);
                } else {
                    Swal.fire("Gagal", res.message, "error");
                }
            },
            error: function() {
                $('#btnCekBpjs').html('<i class="fas fa-search"></i> Cek Kartu');
                $('#btnCekBpjs').prop('disabled', false);
                Swal.fire("Error", "Terjadi kesalahan koneksi ke server BPJS.", "error");
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\resources\views/klinik/patients/index.blade.php ENDPATH**/ ?>