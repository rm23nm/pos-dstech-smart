@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Laporan Absensi</li>
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
							<h3 class="card-label mb-0 font-weight-bold text-body">Rekapitulasi Absensi Karyawan</h3>
						</div>
					</div>
					<div class="card-body px-4">
						
						<!-- Filter Form -->
						<form method="GET" action="{{ route('laporan-absensi') }}" class="mb-5">
							<div class="row align-items-center">
								<div class="col-md-3">
									<label>Tanggal Awal</label>
									<input type="date" class="form-control" name="TglAwal" value="{{ $TglAwal }}">
								</div>
								<div class="col-md-3">
									<label>Tanggal Akhir</label>
									<input type="date" class="form-control" name="TglAkhir" value="{{ $TglAkhir }}">
								</div>
								<div class="col-md-2 mt-7">
									<button type="submit" class="btn btn-primary w-100">Filter</button>
								</div>
							</div>
						</form>

						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="tableLaporan">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Nama Karyawan</th>
										<th>Shift</th>
										<th>Jam Masuk</th>
										<th>Jam Pulang</th>
										<th>Status</th>
										<th>Lokasi Masuk</th>
										<th>Lokasi Pulang</th>
										<th>Foto Masuk</th>
										<th>Foto Pulang</th>
									</tr>
								</thead>
								<tbody>
									@foreach($laporan as $r)
									<tr>
										<td>{{ $r->Tanggal }}</td>
										<td>{{ $r->NamaKaryawan }}</td>
										<td>{{ $r->NamaShift }}</td>
										<td>{{ $r->JamMasuk }}</td>
										<td>{{ $r->JamPulang }}</td>
										<td>
											@if($r->StatusKehadiran == 'Tepat Waktu')
												<span class="badge badge-success">{{ $r->StatusKehadiran }}</span>
											@else
												<span class="badge badge-danger">{{ $r->StatusKehadiran }}</span>
											@endif
										</td>
										<td>
											@if($r->LatitudeMasuk && $r->LongitudeMasuk)
												<a href="https://maps.google.com/?q={{ $r->LatitudeMasuk }},{{ $r->LongitudeMasuk }}" target="_blank" class="btn btn-sm btn-light-primary"><i class="fas fa-map-marker-alt"></i> Peta</a>
											@else
												-
											@endif
										</td>
										<td>
											@if($r->LatitudePulang && $r->LongitudePulang)
												<a href="https://maps.google.com/?q={{ $r->LatitudePulang }},{{ $r->LongitudePulang }}" target="_blank" class="btn btn-sm btn-light-danger"><i class="fas fa-map-marker-alt"></i> Peta</a>
											@else
												-
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
		jQuery('#tableLaporan').DataTable();
	});

	function viewPhoto(src) {
		jQuery('#modalPhotoSrc').attr('src', src);
		jQuery('#photoModal').modal('show');
	}
</script>
@endpush
