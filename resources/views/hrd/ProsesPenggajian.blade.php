@extends('parts.header')
	
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Proses Penggajian & Posting Kas Keluar</li>
			</ol>
		</nav>
	</div>
</div>

<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row mt-4">
			<div class="col-12 px-4">
				
				<div class="card card-custom gutter-b bg-white border-0">
					<div class="card-header border-0 px-4 pt-4 pb-0">
						<h3 class="card-title mb-0 font-weight-bold text-body">Rekapitulasi Gaji Karyawan</h3>
					</div>
					<div class="card-body px-4">
						
						<!-- Filter Periode -->
						<form method="GET" action="{{ route('proses-penggajian') }}" class="mb-5 pb-4 border-bottom">
							<div class="row align-items-end">
								<div class="col-md-3 mb-3 mb-md-0">
									<label class="font-weight-bold">Periode Bulan:</label>
									<select name="bulan" class="form-control">
										@for($i=1; $i<=12; $i++)
											<option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
										@endfor
									</select>
								</div>
								
								<div class="col-md-3 mb-3 mb-md-0">
									<label class="font-weight-bold">Tahun:</label>
									<select name="tahun" class="form-control">
										@for($i=date('Y'); $i>=date('Y')-3; $i--)
											<option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
										@endfor
									</select>
								</div>

								<div class="col-md-4">
									<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
								</div>
							</div>
						</form>

						<!-- Form Posting Kas -->
						<form id="formPostingGaji">
							@csrf
							<input type="hidden" name="bulan" value="{{ $bulan }}">
							<input type="hidden" name="tahun" value="{{ $tahun }}">
							
							<div class="row mb-4">
								<div class="col-md-6">
									<label class="font-weight-bold">Pilih Akun Kas/Bank (Untuk Pembayaran) <span class="text-danger">*</span></label>
									<select name="AkunPembayar" class="form-control" required>
										<option value="">-- Pilih Akun --</option>
										@foreach($rekeningKasBank as $rek)
											<option value="{{ $rek->KodeRekening }}">{{ $rek->KodeRekening }} - {{ $rek->NamaRekening }}</option>
										@endforeach
									</select>
									<small class="text-muted">Akun ini yang akan dipotong saldonya di sistem Akuntansi Kas Keluar.</small>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table table-bordered table-striped text-center table-sm table-hover align-middle">
									<thead class="thead-light text-nowrap">
										<tr>
											<th width="3%"><input type="checkbox" id="checkAll" checked> Pilih</th>
											<th>Nama Karyawan</th>
											<th width="5%">Hadir</th>
											<th width="5%">Izin</th>
											<th width="5%">Mangkir</th>
											<th width="12%">Gaji Pokok (Rp)</th>
											<th width="12%">Total Lembur (Rp)</th>
											<th width="12%">Total Tunjangan (Rp)</th>
											<th width="12%">Total Denda (Rp)</th>
											<th width="12%">Potongan Lainnya (Rp)</th>
											<th width="14%">Take Home Pay (Rp)</th>
										</tr>
									</thead>
									<tbody>
										@forelse($dataPenggajian as $d)
										<tr class="text-nowrap">
											<td class="align-middle">
												<input type="checkbox" name="pilih[]" value="{{ $d['user_id'] }}" class="checkItem" checked>
												<input type="hidden" name="gaji_bersih[{{ $d['user_id'] }}]" value="{{ $d['GajiBersih'] }}">
												<input type="hidden" name="kode_akun_beban[{{ $d['user_id'] }}]" value="{{ $d['KodeAkunGaji'] }}">
												<input type="hidden" name="nama_karyawan[{{ $d['user_id'] }}]" value="{{ $d['name'] }}">
											</td>
											<td class="text-left align-middle">{{ $d['name'] }}</td>
											<td class="align-middle">{{ $d['Hadir'] }} Hari</td>
											<td class="align-middle">{{ $d['Izin'] }} Hari</td>
											<td class="align-middle {{ $d['Mangkir'] > 0 ? 'text-danger font-weight-bold' : '' }}">{{ $d['Mangkir'] }} Hari</td>
											<td class="text-right align-middle">Rp {{ number_format($d['GajiPokok'], 0, ',', '.') }}</td>
											<td class="text-right text-success align-middle">+ Rp {{ number_format($d['Lembur'], 0, ',', '.') }}</td>
											<td class="text-right text-success align-middle">+ Rp {{ number_format($d['TotalTunjangan'], 0, ',', '.') }}
												@if(!empty($d['DetailKomponen']))
													<div style="font-size: 0.8em; color: gray;">
														@foreach($d['DetailKomponen'] as $dk)
															@if($dk['Jenis'] == 'Tunjangan')
																<br>{{ $dk['Nama'] }}
															@endif
														@endforeach
													</div>
												@endif
											</td>
											<td class="text-right text-danger align-middle">- Rp {{ number_format($d['Denda'] + $d['PotonganMangkir'], 0, ',', '.') }}</td>
											<td class="text-right text-danger align-middle">- Rp {{ number_format($d['TotalPotonganLain'], 0, ',', '.') }}
												@if(!empty($d['DetailKomponen']))
													<div style="font-size: 0.8em; color: gray;" class="text-wrap">
														@foreach($d['DetailKomponen'] as $dk)
															@if($dk['Jenis'] == 'Potongan')
																<br>{{ $dk['Nama'] }}
															@endif
														@endforeach
													</div>
												@endif
											</td>
											<td class="text-right font-weight-bold align-middle" style="font-size:1.1em;">Rp {{ number_format($d['GajiBersih'], 0, ',', '.') }}</td>
										</tr>
										@empty
										<tr>
											<td colspan="11" class="text-center text-muted py-5">
												<i class="fas fa-users-slash fa-3x mb-3 text-light-dark"></i><br>
												Belum ada data Karyawan untuk bulan ini.
											</td>
										</tr>
										@endforelse
									</tbody>
								</table>
							</div>
							<div class="mt-4 text-right">
								<button type="submit" class="btn btn-success btn-lg"><i class="fas fa-file-invoice-dollar"></i> Posting ke Kas Keluar</button>
							</div>
						</form>
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
		jQuery('#checkAll').on('click', function() {
			jQuery('.checkItem').prop('checked', this.checked);
		});

		jQuery('#formPostingGaji').on('submit', function(e) {
			e.preventDefault();
			
			if(jQuery('.checkItem:checked').length === 0) {
				Swal.fire('Peringatan', 'Pilih minimal 1 karyawan untuk diposting.', 'warning');
				return;
			}

			let formData = jQuery(this).serialize();

			Swal.fire({
				title: 'Posting Gaji ke Kas Keluar?',
				text: "Aksi ini akan membuat dokumen Kas Keluar (KOUT) dan Jurnal Akuntansi otomatis secara Real-Time. Pastikan data sudah benar!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Posting Sekarang!'
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({ title: 'Memproses...', allowOutsideClick: false });
					Swal.showLoading();

					jQuery.ajax({
						url: '{{ route("posting-kas-keluar") }}',
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
							Swal.fire('Error', 'Terjadi kesalahan sistem saat memposting jurnal.', 'error');
						}
					});
				}
			});
		});
	});
</script>
@endpush
