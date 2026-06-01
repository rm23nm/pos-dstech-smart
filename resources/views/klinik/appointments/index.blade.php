@extends('parts.header')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-4">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 px-4">
                        <div class="card card-custom gutter-b bg-transparent shadow-none border-0">
                            <div class="card-header align-items-center border-bottom-dark px-0">
                                <div class="card-title mb-0">
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Kunjungan / Antrean</h3>
                                </div>
                                <div class="icons d-flex align-items-center">
                                    <div class="me-3 mb-1">
                                        <select class="form-select form-select-sm" id="LoketIDPanggil" title="Pilih Loket Anda">
                                            @foreach($lokets as $l)
                                                <option value="{{ $l->id }}">{{ $l->NamaLoket }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-warning rounded-pill font-weight-bold me-2 mb-1" id="btnPanggilKiosk">
                                        <i class="fas fa-bullhorn"></i> Panggil Antrean Kiosk
                                    </button>
                                    <button class="btn btn-danger rounded-pill font-weight-bold me-2 mb-1" id="btnUlangiPanggil" title="Panggil ulang nomor yang sama tanpa pindah ke nomor berikutnya">
                                        <i class="fas fa-redo"></i> Ulangi Panggilan
                                    </button>
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalAppointment" onclick="tambahAppointment()">
                                        <i class="fas fa-plus"></i> Pendaftaran Kunjungan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableAppointment">
                                        <thead>
                                            <tr>
                                                <th>No Antrean</th>
                                                <th>Tanggal</th>
                                                <th>No RM</th>
                                                <th>Nama Pasien</th>
                                                <th>Poli</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $a)
                                            <tr>
                                                <td><span class="badge bg-primary fs-6">{{ $a->NoAntrean }}</span></td>
                                                <td>{{ date('d-m-Y', strtotime($a->TanggalDaftar)) }}</td>
                                                <td>{{ $a->NoRM }}</td>
                                                <td>{{ $a->NamaPasien }}</td>
                                                <td>{{ $a->NamaPoli }}</td>
                                                <td>{{ $a->NamaDokter }}</td>
                                                <td>
                                                    @if($a->Status == 'Menunggu')
                                                        <span class="badge bg-warning text-dark">{{ $a->Status }}</span>
                                                    @elseif($a->Status == 'Diperiksa')
                                                        <span class="badge bg-info text-dark">{{ $a->Status }}</span>
                                                    @elseif($a->Status == 'Selesai')
                                                        <span class="badge bg-success text-white">{{ $a->Status }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $a->Status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-success" onclick="panggilPoli({{ $a->id }})" title="Panggil ke Poli"><i class="fas fa-volume-up"></i></button>
                                                    @if($a->Status == 'Diperiksa')
                                                        <button class="btn btn-sm btn-danger" onclick="ulangiPanggilPoli({{ $a->id }})" title="Ulangi Panggilan"><i class="fas fa-redo"></i></button>
                                                    @endif
                                                    <button class="btn btn-sm btn-info" onclick="editStatus({{ $a->id }}, '{{ $a->Status }}')" title="Ubah Status"><i class="fas fa-tasks"></i></button>
                                                    <a href="{{ route('klinik-appointments.destroy', $a->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus antrean ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pendaftaran Pasien Cepat -->
        <div class="modal fade" id="modalQuickPatient" tabindex="-1" aria-labelledby="modalQuickPatientLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="formQuickPatient">
                        @csrf
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="modalQuickPatientLabel"><i class="fas fa-user-plus text-primary"></i> Pendaftaran Pasien Cepat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>NIK</label>
                                    <input type="text" name="NIK" id="QuickNIK" class="form-control" placeholder="16 Digit NIK">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Nama Pasien <span class="text-danger">*</span></label>
                                    <input type="text" name="NamaPasien" id="QuickNamaPasien" class="form-control" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="TanggalLahir" id="QuickTanggalLahir" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Jenis Kelamin</label>
                                    <select name="JenisKelamin" id="QuickJenisKelamin" class="form-control">
                                        <option value="">- Pilih Jenis Kelamin -</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>No HP</label>
                                    <input type="text" name="NoHP" id="QuickNoHP" class="form-control" placeholder="08xxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Alamat</label>
                                    <textarea name="Alamat" id="QuickAlamat" class="form-control" rows="1" placeholder="Alamat Singkat"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSaveQuickPatient">Simpan Pasien</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pendaftaran Kunjungan -->
<div class="modal fade" id="modalAppointment" tabindex="-1" aria-labelledby="modalAppointmentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAppointment" method="POST" action="{{ route('klinik-appointments.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAppointmentLabel">Pendaftaran Kunjungan Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tanggal Pendaftaran <span class="text-danger">*</span></label>
                        <input type="date" name="TanggalDaftar" id="TanggalDaftar" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Pasien <span class="text-danger">*</span></label>
                        <select name="PatientID" id="PatientID" class="form-control select2" required style="width: 100%;">
                            <option value="">- Pilih Pasien -</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->NoRM }} - {{ $p->NamaPasien }} @if($p->NIK) [NIK: {{ $p->NIK }}] @endif</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pasien belum ada? <a href="#" data-bs-toggle="modal" data-bs-target="#modalQuickPatient">Daftarkan di sini</a>.</small>
                    </div>
                    <div class="mb-3">
                        <label>Tipe Pembayaran (Kunjungan) <span class="text-danger">*</span></label>
                        <select name="TipeKunjungan" id="TipeKunjungan" class="form-control" required>
                            <option value="Umum">Umum / Pribadi</option>
                            <option value="BPJS">BPJS Kesehatan</option>
                            <option value="Asuransi">Asuransi SwastaLainnya</option>
                        </select>
                    </div>
                    <div id="bpjs-fields" style="display: none;">
                        <div class="mb-3">
                            <label>No Kartu BPJS <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="NoKartuBPJS_Kunjungan" id="NoKartuBPJS_Kunjungan" class="form-control" placeholder="Terisi otomatis jika pasien punya BPJS">
                                <button class="btn btn-outline-primary" type="button" id="btnCekBpjsKunjungan"><i class="fas fa-search"></i> Cek & Update Kartu</button>
                            </div>
                            <small class="text-muted" id="bpjs-status-text"></small>
                        </div>
                        <div class="mb-3">
                            <label>No SEP (Surat Eligibilitas Peserta) <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="NoSEP" id="NoSEP" class="form-control" placeholder="Kosongkan jika belum cetak SEP">
                        </div>
                        <div class="mb-3">
                            <label>No Rujukan <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="NoRujukan" id="NoRujukan" class="form-control" placeholder="Nomor Rujukan FKTP/FKRTL">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Poli <span class="text-danger">*</span></label>
                        <select name="PoliID" id="PoliID" class="form-control select2" required style="width: 100%;">
                            <option value="">- Pilih Poli -</option>
                            @foreach($polis as $pl)
                                <option value="{{ $pl->id }}">{{ $pl->NamaPoli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Dokter <span class="text-danger">*</span></label>
                        <select name="DoctorID" id="DoctorID" class="form-control select2" required style="width: 100%;">
                            <option value="">- Pilih Dokter -</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}">{{ $d->NamaDokter }} ({{ $d->Spesialisasi }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Catatan Keluhan (Opsional)</label>
                        <textarea name="CatatanPendaftaran" id="CatatanPendaftaran" class="form-control" rows="2" placeholder="Cth: Demam sejak 2 hari"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Status -->
<div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="modalStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="formStatus" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStatusLabel">Ubah Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="Status" id="Status" class="form-control" required>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diperiksa">Diperiksa</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Batal">Batal</option>
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

@push('scripts')

<script>
window.$ = window.jQuery;
    $(document).ready(function() {
        // Move event delegation to the top to ensure it's bound even if DataTables fails
        $(document).on('click', '#btnPanggilKiosk', function() {
            var loketId = $('#LoketIDPanggil').val();
            if(!loketId) {
                Swal.fire("Info", "Pilih Loket terlebih dahulu!", "warning");
                return;
            }

            var btn = $(this);
            var originalText = btn.html();
            btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memanggil...');
            
            $.ajax({
                url: "{{ route('klinik-kiosk.panggil') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    loket_id: loketId
                },
                success: function(response) {
                    btn.attr('disabled', false).html(originalText);
                    if(response.success) {
                        Swal.fire("Berhasil", "Memanggil nomor antrean: " + response.nomor, "success");
                    } else {
                        Swal.fire("Info", response.message, "info");
                    }
                },
                error: function(xhr) {
                    btn.attr('disabled', false).html(originalText);
                    console.log(xhr.responseText);
                    Swal.fire("Error", "Gagal memanggil antrean. Cek console.", "error");
                }
            });
        });

        // Tombol Ulangi Panggilan
        $(document).on('click', '#btnUlangiPanggil', function() {
            var loketId = $('#LoketIDPanggil').val();
            if(!loketId) {
                Swal.fire("Info", "Pilih Loket terlebih dahulu!", "warning");
                return;
            }

            var btn = $(this);
            var originalText = btn.html();
            btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengulang...');
            
            $.ajax({
                url: "{{ route('klinik-kiosk.ulangi') }}",
                type: "POST",
                data: { 
                    _token: "{{ csrf_token() }}",
                    loket_id: loketId
                },
                success: function(response) {
                    btn.attr('disabled', false).html(originalText);
                    if(response.success) {
                        Swal.fire({
                            title: 'Panggilan Diulang!',
                            text: 'Memanggil ulang nomor: ' + response.nomor,
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Info', response.message, 'info');
                    }
                },
                error: function() {
                    btn.attr('disabled', false).html(originalText);
                    Swal.fire('Error', 'Gagal mengulang panggilan.', 'error');
                }
            });
        });

        try {
            $('#tableAppointment').DataTable({
                "order": [[ 0, "desc" ]] // Urutkan No Antrean terbaru
            });
        } catch(e) {
            console.error("DataTable failed to initialize", e);
        }
        // Inisialisasi Select2 saat modal terbuka agar tidak error/hilang
        $('#modalAppointment').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#modalAppointment'),
                width: '100%'
            });
        });

        // Toggle BPJS Fields
        $('#TipeKunjungan').on('change', function() {
            if($(this).val() === 'BPJS') {
                $('#bpjs-fields').slideDown();
            } else {
                $('#bpjs-fields').slideUp();
                $('#NoKartuBPJS_Kunjungan').val('');
                $('#NoSEP').val('');
                $('#NoRujukan').val('');
            }
        });

        // Ambil Data BPJS saat Pasien Dipilih
        $('#PatientID').on('change', function() {
            var patientId = $(this).val();
            $('#bpjs-status-text').html('');
            $('#NoKartuBPJS_Kunjungan').val('');

            if (patientId) {
                $.ajax({
                    url: "{{ url('klinik-appointments/get-patient-bpjs') }}/" + patientId,
                    type: "GET",
                    success: function(data) {
                        if (data && data.NoKartuBPJS) {
                            $('#NoKartuBPJS_Kunjungan').val(data.NoKartuBPJS);
                            
                            var statusTeks = 'Telah tersambung dengan BPJS';
                            if(data.StatusBPJS) statusTeks += ` (Status: ${data.StatusBPJS})`;
                            if(data.FaskesBPJS) statusTeks += ` - Faskes: ${data.FaskesBPJS}`;
                            
                            $('#bpjs-status-text').html(`<span class="text-success"><i class="fas fa-check-circle"></i> ${statusTeks}</span>`);
                        } else {
                            $('#bpjs-status-text').html('<span class="text-warning"><i class="fas fa-exclamation-circle"></i> Pasien ini belum memiliki data kartu BPJS.</span>');
                        }
                    }
                });
            }
        });

        // Tombol Cek & Update Kartu BPJS di Form Kunjungan
        $(document).on('click', '#btnCekBpjsKunjungan', function() {
            var noKartu = $('#NoKartuBPJS_Kunjungan').val();
            var patientId = $('#PatientID').val();

            if(!patientId) {
                Swal.fire("Peringatan", "Pilih Pasien terlebih dahulu!", "warning");
                return;
            }

            if(!noKartu) {
                Swal.fire("Peringatan", "Masukkan Nomor Kartu BPJS terlebih dahulu!", "warning");
                return;
            }

            var btn = $(this);
            var originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> Mengecek...');
            btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('klinik-bpjs.cek') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    noKartu: noKartu,
                    patient_id: patientId
                },
                success: function(res) {
                    btn.html(originalText);
                    btn.prop('disabled', false);

                    if(res.success) {
                        var statusTeks = 'Telah tersambung dengan BPJS';
                        if(res.data.statusPeserta.keterangan) statusTeks += ` (Status: ${res.data.statusPeserta.keterangan})`;
                        if(res.data.provUmum.nmProvider) statusTeks += ` - Faskes: ${res.data.provUmum.nmProvider}`;
                        
                        $('#bpjs-status-text').html(`<span class="text-success"><i class="fas fa-check-circle"></i> ${statusTeks}</span>`);
                        
                        Swal.fire("Data BPJS Disimpan", "Data Kartu BPJS, Kelas Rawat, dan Faskes telah diperbarui ke profil Pasien secara otomatis.", "success");
                    } else {
                        $('#bpjs-status-text').html(`<span class="text-danger"><i class="fas fa-times-circle"></i> ${res.message}</span>`);
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function() {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    Swal.fire("Error", "Terjadi kesalahan koneksi ke server BPJS.", "error");
                }
            });
        });

        // Filter Dokter berdasarkan Poli
        $('#PoliID').on('change', function() {
            var poliId = $(this).val();
            var doctorSelect = $('#DoctorID');
            
            doctorSelect.empty();
            doctorSelect.append('<option value="">- Pilih Dokter -</option>');
            
            if (poliId) {
                $.ajax({
                    url: "{{ url('klinik-appointments/doctors') }}/" + poliId,
                    type: "GET",
                    success: function(data) {
                        $.each(data, function(key, doctor) {
                            var spec = doctor.Spesialisasi ? doctor.Spesialisasi : '';
                            var specText = spec ? ' (' + spec + ')' : '';
                            doctorSelect.append('<option value="'+ doctor.id +'">'+ doctor.NamaDokter + specText +'</option>');
                        });
                        doctorSelect.trigger('change');
                    }
                });
            } else {
                doctorSelect.trigger('change');
            }
        });
    });

    function tambahAppointment() {
        $('#modalAppointmentLabel').text('Pendaftaran Kunjungan Pasien');
        $('#formAppointment').attr('action', '{{ route('klinik-appointments.store') }}');
        $('#PatientID').val('').trigger('change');
        $('#PoliID').val('').trigger('change');
        $('#DoctorID').val('').trigger('change');
        $('#TipeKunjungan').val('Umum').trigger('change');
        $('#CatatanPendaftaran').val('');
        $('#TanggalDaftar').val('{{ date('Y-m-d') }}');
    }

    function editStatus(id, currentStatus) {
        $('#formStatus').attr('action', '{{ url('klinik-appointments/update') }}/' + id);
        $('#Status').val(currentStatus);
        $('#modalStatus').modal('show');
    }

    function panggilPoli(id) {
        $.ajax({
            url: "{{ route('klinik-appointments.panggil') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire('Berhasil', 'Pasien dengan antrean ' + res.nomor + ' berhasil dipanggil ke Poli.', 'success').then((result) => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            }
        });
    }

    function ulangiPanggilPoli(id) {
        $.ajax({
            url: "{{ route('klinik-appointments.ulangi') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Panggilan Diulang!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            }
        });
    }

    // Handler form pendaftaran pasien cepat
    $(document).on('submit', '#formQuickPatient', function(e) {
        e.preventDefault();
        var btn = $('#btnSaveQuickPatient');
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: "{{ route('klinik-patients.store') }}",
            type: "POST",
            data: $(this).serialize() + '&is_ajax=1',
            success: function(res) {
                btn.html(originalText).prop('disabled', false);
                if(res.success) {
                    // Tutup modal dan reset form
                    $('#modalQuickPatient').modal('hide');
                    $('#formQuickPatient')[0].reset();
                    
                    // Tambahkan opsi baru ke dropdown pasien dan langsung select
                    var newOption = new Option(res.data.NoRM + ' - ' + res.data.NamaPasien, res.data.id, true, true);
                    $('#PatientID').append(newOption).trigger('change');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pasien baru berhasil ditambahkan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan pasien', 'error');
                }
            },
            error: function(xhr) {
                btn.html(originalText).prop('disabled', false);
                var err = 'Terjadi kesalahan sistem.';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    err = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                Swal.fire('Gagal', err, 'error');
            }
        });
    });
</script>
@endpush
@endsection
