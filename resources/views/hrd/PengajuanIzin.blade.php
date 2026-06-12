@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Pengajuan Izin & Cuti</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				
				<!-- Form Pengajuan -->
				<div class="card card-custom gutter-b bg-white border-0 mt-4">
					<div class="card-header align-items-center border-bottom-dark px-0">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body px-4">Buat Pengajuan Baru</h3>
						</div>
					</div>
					<div class="card-body px-4">
						<form id="formPengajuan">
							@csrf
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Jenis Pengajuan <span class="text-danger">*</span></label>
										<select name="JenisPengajuan" class="form-control" required>
											<option value="">-- Pilih Jenis --</option>
											<option value="Sakit">Sakit</option>
											<option value="Izin">Izin (Keperluan Pribadi)</option>
											<option value="Cuti Tahunan">Cuti Tahunan</option>
										</select>
									</div>
									<div class="form-group">
										<label>Tanggal Mulai <span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="TanggalMulai" required>
									</div>
									<div class="form-group">
										<label>Tanggal Selesai <span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="TanggalSelesai" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Keterangan / Alasan <span class="text-danger">*</span></label>
										<textarea name="Keterangan" class="form-control" rows="4" required></textarea>
									</div>
									<div class="form-group">
										<label>Bukti Dokumen (Foto Surat Dokter/Lampiran)</label>
										<input type="file" id="BuktiFile" class="form-control" accept="image/*">
										<input type="hidden" name="BuktiDokumen" id="BuktiBase64">
										<small class="text-muted">Max ukuran: 2MB</small>
									</div>
									<button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!-- History Table -->
				<div class="card card-custom gutter-b bg-white border-0 mt-4">
					<div class="card-header align-items-center border-0 px-4 pt-4">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body">Riwayat Pengajuan Saya</h3>
						</div>
					</div>
					<div class="card-body px-4">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="tableRiwayat">
								<thead>
									<tr>
										<th>Tgl Diajukan</th>
										<th>Jenis</th>
										<th>Mulai - Selesai</th>
										<th>Keterangan</th>
										<th>Bukti</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pengajuan as $p)
									<tr>
										<td>{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y H:i') }}</td>
										<td>{{ $p->JenisPengajuan }}</td>
										<td>{{ $p->TanggalMulai }} s/d {{ $p->TanggalSelesai }}</td>
										<td>{{ $p->Keterangan }}</td>
										<td>
											@if($p->BuktiDokumen)
												<button type="button" class="btn btn-sm btn-info" onclick="viewPhoto('{{ $p->BuktiDokumen }}')">Lihat Foto</button>
											@else
												-
											@endif
										</td>
										<td>
											@if($p->StatusApproval == 'Pending')
												<span class="badge badge-warning">Menunggu Approval</span>
											@elseif($p->StatusApproval == 'Disetujui')
												<span class="badge badge-success">Disetujui</span>
											@else
												<span class="badge badge-danger">Ditolak</span>
											@endif
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

<!-- Modal Photo -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhotoSrc" src="" style="width: 100%; border-radius: 10px;">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
	jQuery(document).ready(function() {
		jQuery('#tableRiwayat').DataTable({
			"ordering": false
		});

		// Konversi file ke base64 saat user memilih file
		jQuery('#BuktiFile').on('change', function(e) {
			let file = e.target.files[0];
			if(!file) return;
			
			if(file.size > 2 * 1024 * 1024) {
				Swal.fire('Error', 'Ukuran foto melebihi batas 2MB.', 'error');
				jQuery('#BuktiFile').val('');
				return;
			}

			let reader = new FileReader();
			reader.onload = function(event) {
				jQuery('#BuktiBase64').val(event.target.result);
			};
			reader.readAsDataURL(file);
		});

		// Submit form
		jQuery('#formPengajuan').on('submit', function(e) {
			e.preventDefault();
			
			let formData = jQuery(this).serialize();

			Swal.fire({
				title: 'Kirim Pengajuan?',
				text: "Pastikan data dan dokumen sudah benar.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Kirim'
			}).then((result) => {
				if (result.isConfirmed) {
					jQuery.ajax({
						url: '{{ route("pengajuan-izin-store") }}',
						type: 'POST',
						data: formData,
						success: function(res) {
							if(res.success) {
								Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
							} else {
								Swal.fire('Gagal!', res.message, 'error');
							}
						},
						error: function(err) {
							Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
						}
					});
				}
			});
		});
	});

	function viewPhoto(src) {
		jQuery('#modalPhotoSrc').attr('src', src);
		jQuery('#photoModal').modal('show');
	}
</script>
@endpush
