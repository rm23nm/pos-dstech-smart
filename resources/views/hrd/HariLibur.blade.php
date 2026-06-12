@extends('parts.header')
	
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Pengaturan Hari Libur</li>
			</ol>
		</nav>
	</div>
</div>

<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row mt-4">
			<div class="col-12 px-4">
				
				<div class="card card-custom gutter-b bg-white border-0">
					<div class="card-header border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
						<h3 class="card-title mb-0 font-weight-bold text-body">Daftar Hari Libur Tahun {{ $tahun }}</h3>
						<div>
							<button class="btn btn-primary" data-toggle="modal" data-target="#modalAddLibur">
								<i class="fas fa-plus"></i> Tambah Hari Libur
							</button>
						</div>
					</div>
					<div class="card-body px-4">
						<form method="GET" action="{{ route('hari-libur') }}" class="form-inline mb-4">
							<label class="mr-2">Tahun:</label>
							<select name="tahun" class="form-control mr-4" onchange="this.form.submit()">
								@for($i=date('Y')+1; $i>=date('Y')-3; $i--)
									<option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</form>

						<div class="table-responsive">
							<table class="table table-bordered table-striped text-center">
								<thead class="thead-light">
									<tr>
										<th>No</th>
										<th>Tanggal Libur</th>
										<th>Keterangan</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									@if(count($libur) > 0)
										@foreach($libur as $index => $item)
										<tr>
											<td>{{ $index + 1 }}</td>
											<td class="font-weight-bold text-danger">{{ \Carbon\Carbon::parse($item->Tanggal)->translatedFormat('d F Y') }}</td>
											<td>{{ $item->Keterangan }}</td>
											<td>
												<button class="btn btn-sm btn-danger btn-hapus" data-id="{{ $item->id }}">
													<i class="fas fa-trash"></i> Hapus
												</button>
											</td>
										</tr>
										@endforeach
									@else
										<tr>
											<td colspan="4" class="text-muted">Belum ada data hari libur di tahun ini.</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal Tambah Libur -->
<div class="modal fade" id="modalAddLibur" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Hari Libur</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<form id="formAddLibur">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label>Tanggal Libur <span class="text-danger">*</span></label>
						<input type="date" class="form-control" name="Tanggal" required>
					</div>
					<div class="form-group">
						<label>Keterangan <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="Keterangan" placeholder="Contoh: Idul Fitri, Cuti Bersama, dll" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	$('#formAddLibur').submit(function(e) {
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');
		btn.attr('disabled', true).text('Menyimpan...');

		$.ajax({
			url: "{{ route('hari-libur-store') }}",
			type: "POST",
			data: $(this).serialize(),
			success: function(res) {
				if(res.success) {
					Swal.fire('Berhasil!', res.message, 'success').then(() => {
						location.reload();
					});
				} else {
					Swal.fire('Gagal', res.message, 'error');
					btn.attr('disabled', false).text('Simpan');
				}
			},
			error: function() {
				Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
				btn.attr('disabled', false).text('Simpan');
			}
		});
	});

	$('.btn-hapus').click(function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Hapus Hari Libur?',
			text: "Data yang dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, Hapus!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ route('hari-libur-destroy') }}",
					type: "POST",
					data: {
						_token: "{{ csrf_token() }}",
						id: id
					},
					success: function(res) {
						if(res.success) {
							Swal.fire('Dihapus!', res.message, 'success').then(() => {
								location.reload();
							});
						} else {
							Swal.fire('Gagal', res.message, 'error');
						}
					}
				});
			}
		});
	});
});
</script>
@endpush
