@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Approval Cuti & Izin</li>
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

				<div class="card card-custom gutter-b bg-white border-0 mt-4">
					<div class="card-header align-items-center border-0 px-4 pt-4">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body">Daftar Pengajuan Karyawan</h3>
						</div>
					</div>
					<div class="card-body px-4">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="tableApproval">
								<thead>
									<tr>
										<th>Tgl Diajukan</th>
										<th>Karyawan</th>
										<th>Jenis</th>
										<th>Mulai - Selesai</th>
										<th>Keterangan</th>
										<th>Bukti</th>
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pengajuan as $p)
									<tr>
										<td>{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y H:i') }}</td>
										<td>{{ $p->NamaKaryawan }}</td>
										<td>{{ $p->JenisPengajuan }}</td>
										<td>{{ $p->TanggalMulai }} s/d {{ $p->TanggalSelesai }}</td>
										<td>{{ $p->Keterangan }}</td>
										<td>
											@if($p->BuktiDokumen)
												<button type="button" class="btn btn-sm btn-info" onclick="viewPhoto('{{ $p->BuktiDokumen }}')">Lihat</button>
											@else
												-
											@endif
										</td>
										<td>
											@if($p->StatusApproval == 'Pending')
												<span class="badge badge-warning">Menunggu</span>
											@elseif($p->StatusApproval == 'Disetujui')
												<span class="badge badge-success">Disetujui</span>
											@else
												<span class="badge badge-danger">Ditolak</span>
											@endif
										</td>
										<td>
											@if($p->StatusApproval == 'Pending')
												<button type="button" class="btn btn-sm btn-success mr-2" onclick="prosesApproval({{ $p->id }}, 'Disetujui')"><i class="fas fa-check"></i> Terima</button>
												<button type="button" class="btn btn-sm btn-danger" onclick="prosesApproval({{ $p->id }}, 'Ditolak')"><i class="fas fa-times"></i> Tolak</button>
											@else
												<span class="text-muted"><i class="fas fa-lock"></i> Selesai</span>
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
		jQuery('#tableApproval').DataTable({
			"ordering": false
		});
	});

	function viewPhoto(src) {
		jQuery('#modalPhotoSrc').attr('src', src);
		jQuery('#photoModal').modal('show');
	}

	function prosesApproval(id, status) {
		Swal.fire({
			title: 'Konfirmasi Approval',
			text: "Apakah Anda yakin ingin memberikan status: " + status + " ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, Lanjutkan'
		}).then((result) => {
			if (result.isConfirmed) {
				jQuery.ajax({
					url: '{{ route("approval-izin-proses") }}',
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						id: id,
						status: status
					},
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
	}
</script>
@endpush
