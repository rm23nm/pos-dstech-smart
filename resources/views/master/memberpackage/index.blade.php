@extends('parts.header')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">Daftar Paket Membership</h3>
								</div>
							    <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalPaket" onclick="tambahPaket()">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </button>
								</div>
							</div>
						</div>
					</div>
				</div>

                <div class="row">
					<div class="col-12 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                <table class="table table-bordered table-hover" id="tableData">
                    <thead>
                        <tr>
                            <th>Kode Paket</th>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Berlaku Untuk</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Masa Aktif (Hari)</th>
                            <th>Batas Kunjungan</th>
                            <th>Waktu per Kunjungan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $p)
                        <tr>
                            <td>{{ $p->KodePaket }}</td>
                            <td>{{ $p->NamaPaket }}</td>
                            <td><span class="badge bg-secondary">{{ $p->KategoriPaket }}</span></td>
                            <td>
                                @php
                                    $kl = collect($kelompokLampu)->where('KodeKelompok', $p->KelompokLampu)->first();
                                @endphp
                                {{ $kl ? $kl->NamaKelompok : 'Semua Area' }}
                            </td>
                            <td>
                                @if($p->Tipe == 'DISCOUNT')
                                    <span class="badge bg-info text-white">Diskon Harga</span>
                                @elseif($p->Tipe == 'QUOTA')
                                    <span class="badge bg-warning text-dark">Kuota Pertemuan</span>
                                @else
                                    <span class="badge bg-success text-white">Tanpa Batas (Unlimited)</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($p->Harga, 0, ',', '.') }}</td>
                            <td>{{ $p->ValidDays }} Hari</td>
                            <td>{{ $p->MaxPlay > 0 ? $p->MaxPlay . ' x' : 'Tanpa Batas' }}</td>
                            <td>{{ $p->maxTimePerPlay > 0 ? $p->maxTimePerPlay . ' Jam' : 'Bebas' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editPaket({{ $p->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="hapusPaket({{ $p->id }})"><i class="fas fa-trash"></i></button>
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
</div>

<!-- Modal -->
<div class="modal fade" id="modalPaket" tabindex="-1" aria-labelledby="modalPaketLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPaketLabel">Form Paket Membership</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formPaket">
        @csrf
        <input type="hidden" name="id" id="paket_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kode Paket <span class="text-danger">*</span></label>
                    <input type="text" name="KodePaket" id="KodePaket" class="form-control" required>
                    <small>Akan menjadi Kode Item di POS</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nama Paket <span class="text-danger">*</span></label>
                    <input type="text" name="NamaPaket" id="NamaPaket" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Harga Jual (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="Harga" id="Harga" class="form-control" value="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Masa Aktif Paket (Hari) <span class="text-danger">*</span></label>
                    <input type="number" name="ValidDays" id="ValidDays" class="form-control" value="30" required>
                    <small>Contoh: 30 untuk 1 bulan</small>
                </div>
                
                <div class="col-md-12 mb-3">
                    <hr>
                    <h6>Pengaturan Aturan Member (Rules)</h6>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Peruntukan Paket <span class="text-danger">*</span></label>
                    <select name="KategoriPaket" id="KategoriPaket" class="form-control" onchange="toggleRules()" required>
                        <option value="HIBURAN">Khusus Hiburan (Billiard/Futsal)</option>
                        <option value="RETAIL">Khusus Retail</option>
                        <option value="FNB">Khusus F&B</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Berlaku Untuk Lapangan/Meja</label>
                    <select name="KelompokLampu" id="KelompokLampu" class="form-control">
                        <option value="">Semua (Bebas)</option>
                        @foreach($kelompokLampu as $kl)
                            <option value="{{ $kl->KodeKelompok }}">{{ $kl->NamaKelompok }}</option>
                        @endforeach
                    </select>
                </div>

                
                <div class="col-md-4 mb-3">
                    <label>Tipe Member <span class="text-danger">*</span></label>
                    <select name="Tipe" id="Tipe" class="form-control" onchange="toggleRules()" required>
                        <option value="UNLIMITED">Tanpa Batas (Unlimited)</option>
                        <option value="QUOTA">Batas Kuota Pertemuan</option>
                        <option value="DISCOUNT">Dapat Diskon Harga</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3 rule-quota" style="display:none;">
                    <label>Maksimal Pertemuan (Max Play)</label>
                    <input type="number" name="MaxPlay" id="MaxPlay" class="form-control" value="0">
                    <small>Berapa kali member bisa masuk/main</small>
                </div>
                
                <div class="col-md-4 mb-3 rule-time">
                    <label>Lama Waktu Main (Jam)</label>
                    <input type="number" name="maxTimePerPlay" id="maxTimePerPlay" class="form-control" value="0">
                    <small>Dalam hitungan Jam. Isi 0 jika bebas.</small>
                </div>
                
                <div class="col-md-4 mb-3 rule-discount" style="display:none;">
                    <label>Harga Khusus Member (Rp)</label>
                    <input type="number" name="MemberPrice" id="MemberPrice" class="form-control" value="0">
                    <small>Harga sewa/main untuk member ini</small>
                </div>
                
                <div class="col-md-4 mb-3 rule-retail" style="display:none;">
                    <label>Diskon Belanja (%)</label>
                    <input type="number" name="DiskonPersen" id="DiskonPersen" class="form-control" value="0" step="0.01">
                    <small>Persentase diskon transaksi (Misal: 10)</small>
                </div>
                
                <div class="col-md-4 mb-3 rule-retail" style="display:none;">
                    <label>Maksimal Gratis Ongkir (Rp)</label>
                    <input type="number" name="MaxGratisOngkir" id="MaxGratisOngkir" class="form-control" value="0">
                    <small>Batas potong biaya jasa/ongkir</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="btnSave">Simpan Paket</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleRules() {
        var tipe = $('#Tipe').val();
        var kategori = $('#KategoriPaket').val();
        
        $('.rule-quota, .rule-discount, .rule-time, .rule-retail').hide();
        
        if (kategori === 'RETAIL' || kategori === 'FNB') {
            $('.rule-retail').show();
            // Retail don't use quota or time limits usually, but if needed, un-hide them.
        } else {
            $('.rule-time').show();
            if(tipe == 'QUOTA') {
                $('.rule-quota').show();
            } else if(tipe == 'DISCOUNT') {
                $('.rule-discount').show();
            }
        }
    }

    function tambahPaket() {
        $('#formPaket')[0].reset();
        $('#paket_id').val('');
        $('#KelompokLampu').val('');
        $('#KodePaket').prop('readonly', false);
        toggleRules();
        $('#modalPaketLabel').text('Tambah Paket Membership');
    }

    function editPaket(id) {
        $.get('/master/memberpackage/' + id, function(data) {
            $('#paket_id').val(data.id);
            $('#KodePaket').val(data.KodePaket).prop('readonly', true);
            $('#NamaPaket').val(data.NamaPaket);
            $('#Harga').val(data.Harga);
            $('#ValidDays').val(data.ValidDays);
            $('#KategoriPaket').val(data.KategoriPaket);
            $('#KelompokLampu').val(data.KelompokLampu);
            $('#Tipe').val(data.Tipe);
            $('#MaxPlay').val(data.MaxPlay);
            $('#maxTimePerPlay').val(data.maxTimePerPlay);
            $('#MemberPrice').val(data.MemberPrice);
            $('#DiskonPersen').val(data.DiskonPersen);
            $('#MaxGratisOngkir').val(data.MaxGratisOngkir);
            
            toggleRules();
            $('#modalPaketLabel').text('Edit Paket Membership');
            $('#modalPaket').modal('show');
        });
    }

    $('#formPaket').on('submit', function(e) {
        e.preventDefault();
        $('#btnSave').prop('disabled', true).text('Menyimpan...');
        
        var id = $('#paket_id').val();
        var url = id ? '/master/memberpackage/' + id : '/master/memberpackage';
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(res) {
                $('#btnSave').prop('disabled', false).text('Simpan Paket');
                if(res.success) {
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function(err) {
                $('#btnSave').prop('disabled', false).text('Simpan Paket');
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        });
    });

    function hapusPaket(id) {
        Swal.fire({
            title: 'Hapus Paket?',
            text: "Paket yang dihapus juga akan hilang dari daftar Item POS!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/master/memberpackage/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('Terhapus!', res.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
