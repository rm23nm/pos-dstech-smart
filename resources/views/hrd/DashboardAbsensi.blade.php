@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Dashboard Analytics HR</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row mt-4">
			<div class="col-12 px-4">
				<div class="row">
					<!-- Card 1: Kehadiran -->
					<div class="col-xl-3 col-md-6 mb-4">
						<div class="card bg-primary text-white h-100 py-2 border-0">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-uppercase mb-1">Hadir (Hari Ini)</div>
										<div class="h5 mb-0 font-weight-bold">{{ $totalHadir }} Karyawan</div>
									</div>
									<div class="col-auto">
										<i class="fas fa-users fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Card 2: Terlambat -->
					<div class="col-xl-3 col-md-6 mb-4">
						<div class="card bg-warning text-white h-100 py-2 border-0">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-uppercase mb-1">Terlambat (Hari Ini)</div>
										<div class="h5 mb-0 font-weight-bold">{{ $totalTelatHariIni }} Karyawan</div>
									</div>
									<div class="col-auto">
										<i class="fas fa-clock fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Card 3: Cuti/Izin -->
					<div class="col-xl-3 col-md-6 mb-4">
						<div class="card bg-info text-white h-100 py-2 border-0">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-uppercase mb-1">Cuti / Izin (Bulan Ini)</div>
										<div class="h5 mb-0 font-weight-bold">{{ $totalIzinCuti }} Karyawan</div>
									</div>
									<div class="col-auto">
										<i class="fas fa-calendar-times fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Card 4: Denda Telat -->
					<div class="col-xl-3 col-md-6 mb-4">
						<div class="card bg-danger text-white h-100 py-2 border-0">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-uppercase mb-1">Total Denda (Bulan Ini)</div>
										<div class="h5 mb-0 font-weight-bold">Rp {{ number_format($totalDendaBulanIni, 0, ',', '.') }}</div>
									</div>
									<div class="col-auto">
										<i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-12">
						<div class="card card-custom bg-white border-0">
							<div class="card-header align-items-center border-0 px-4 pt-4">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">Ringkasan Kinerja Bulan Ini</h3>
								</div>
							</div>
							<div class="card-body px-4 text-center">
								<p class="text-muted">Total bonus lembur yang harus dibayarkan perusahaan bulan ini: <strong class="text-success">Rp {{ number_format($totalLemburBulanIni, 0, ',', '.') }}</strong></p>
								<hr>
								<p>Statistik visual akan ditampilkan di sini pada versi pengembangan berikutnya menggunakan Chart.js.</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
	jQuery(document).ready(function() {
		// Placeholder for Chart JS
	});
</script>
@endpush
