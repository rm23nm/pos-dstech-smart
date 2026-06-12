@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Absensi Saya</li>
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
				
				<!-- Webcam Section -->
				<div class="card card-custom gutter-b bg-white border-0">
					<div class="card-header align-items-center border-bottom-dark px-0">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body px-4">Modul Absensi Karyawan</h3>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 text-center">
								<h5>Webcam Anda</h5>
								<video id="webcam" width="100%" height="auto" autoplay playsinline style="border: 2px solid #ccc; border-radius: 8px;"></video>
								<canvas id="canvas" style="display:none;"></canvas>
							</div>
							<div class="col-md-6">
								<div class="form-group mb-4">
									<label class="text-body font-weight-bold">Pilih Shift Saat Ini</label>
									<select id="KodeShift" class="form-control">
										@foreach($shifts as $s)
											<option value="{{ $s->KodeShift }}">{{ $s->NamaShift }} ({{ $s->JamMulai }} - {{ $s->JamSelesai }})</option>
										@endforeach
									</select>
								</div>
								
								<div class="alert alert-info">
									<p><strong>Status Absen Hari Ini ({{ \Carbon\Carbon::now()->format('d-m-Y') }}):</strong></p>
									@if($absenHariIni)
										<ul class="mb-0">
											<li>Jam Masuk: {{ $absenHariIni->JamMasuk ?? '-' }}</li>
											<li>Jam Pulang: {{ $absenHariIni->JamPulang ?? '-' }}</li>
											<li>Status: <span class="badge badge-success">{{ $absenHariIni->StatusKehadiran }}</span></li>
										</ul>
									@else
										<p class="mb-0">Belum ada data absen hari ini.</p>
									@endif
								</div>

								<div class="d-flex mt-5">
									<button id="btnAbsenMasuk" class="btn btn-success btn-lg mr-3" {{ $absenHariIni ? 'disabled' : '' }}>
										<i class="fas fa-sign-in-alt"></i> Absen Masuk
									</button>
									<button id="btnAbsenPulang" class="btn btn-danger btn-lg" {{ (!$absenHariIni || $absenHariIni->JamPulang) ? 'disabled' : '' }}>
										<i class="fas fa-sign-out-alt"></i> Absen Pulang
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- History Table -->
				<div class="card card-custom gutter-b bg-white border-0 mt-4">
					<div class="card-header align-items-center border-0 px-4 pt-4">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body">Riwayat Absensi Anda</h3>
						</div>
					</div>
					<div class="card-body px-4">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="tableRiwayat">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Shift</th>
										<th>Jam Masuk</th>
										<th>Jam Pulang</th>
										<th>Status</th>
										<th>Foto Masuk</th>
										<th>Foto Pulang</th>
									</tr>
								</thead>
								<tbody>
									@foreach($riwayat as $r)
									<tr>
										<td>{{ $r->Tanggal }}</td>
										<td>{{ $r->KodeShift }}</td>
										<td>{{ $r->JamMasuk }}</td>
										<td>{{ $r->JamPulang }}</td>
										<td>
											@if($r->StatusKehadiran == 'Tepat Waktu')
												<span class="badge badge-success">{{ $r->StatusKehadiran }}</span>
											@else
												<span class="badge badge-warning">{{ $r->StatusKehadiran }}</span>
											@endif
										</td>
										<td>
											@if($r->FotoMasuk)
												<img src="{{ $r->FotoMasuk }}" alt="Masuk" width="50" style="border-radius: 5px; cursor: pointer;" onclick="viewPhoto('{{ $r->FotoMasuk }}')">
											@endif
										</td>
										<td>
											@if($r->FotoPulang)
												<img src="{{ $r->FotoPulang }}" alt="Pulang" width="50" style="border-radius: 5px; cursor: pointer;" onclick="viewPhoto('{{ $r->FotoPulang }}')">
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
                <h5 class="modal-title">Foto Absen</h5>
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

		// Setup Webcam
		const video = document.getElementById('webcam');
		const canvas = document.getElementById('canvas');

		if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
				video.srcObject = stream;
				video.play();
			}).catch(function(err) {
				Swal.fire('Error', 'Gagal mengakses kamera: ' + err, 'error');
			});
		} else {
			Swal.fire('Error', 'Browser tidak mendukung akses kamera', 'error');
		}

		function capturePhoto() {
			const context = canvas.getContext('2d');
			// set canvas dimensions to match video
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			context.drawImage(video, 0, 0, canvas.width, canvas.height);
			return canvas.toDataURL('image/jpeg', 0.8);
		}

		let currentLat = null;
		let currentLng = null;

		if ("geolocation" in navigator) {
			navigator.geolocation.getCurrentPosition(function(position) {
				currentLat = position.coords.latitude;
				currentLng = position.coords.longitude;
			}, function(error) {
				console.warn('Geolocation error: ', error);
			}, { enableHighAccuracy: true });
		}

		jQuery('#btnAbsenMasuk').click(function() {
			let photo = capturePhoto();
			let shift = jQuery('#KodeShift').val();
			if(!shift) {
				Swal.fire('Peringatan', 'Silakan pilih Shift terlebih dahulu!', 'warning');
				return;
			}

			Swal.fire({
				title: 'Konfirmasi',
				text: "Apakah Anda yakin ingin Absen Masuk sekarang?",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Absen Masuk!'
			}).then((result) => {
				if (result.isConfirmed) {
					jQuery.ajax({
						url: '{{ route("absensi-masuk") }}',
						type: 'POST',
						data: {
							_token: '{{ csrf_token() }}',
							KodeShift: shift,
							FotoMasuk: photo,
							LatitudeMasuk: currentLat,
							LongitudeMasuk: currentLng
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
		});

		jQuery('#btnAbsenPulang').click(function() {
			let photo = capturePhoto();
			
			Swal.fire({
				title: 'Konfirmasi',
				text: "Apakah Anda yakin ingin Absen Pulang sekarang?",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Absen Pulang!'
			}).then((result) => {
				if (result.isConfirmed) {
					jQuery.ajax({
						url: '{{ route("absensi-pulang") }}',
						type: 'POST',
						data: {
							_token: '{{ csrf_token() }}',
							FotoPulang: photo,
							LatitudePulang: currentLat,
							LongitudePulang: currentLng
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
		});
	});

	function viewPhoto(src) {
		jQuery('#modalPhotoSrc').attr('src', src);
		jQuery('#photoModal').modal('show');
	}
</script>
@endpush
