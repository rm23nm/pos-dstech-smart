@extends('parts.header')
	
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Master Gaji Karyawan</li>
			</ol>
		</nav>
	</div>
</div>

<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row mt-4">
			<div class="col-12 px-4">
				<div class="card card-custom gutter-b bg-white border-0">
					<div class="card-header align-items-center border-0 px-4 pt-4">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body">Setting Gaji Pokok & Akun Beban</h3>
						</div>
					</div>
					<div class="card-body px-4">
						<form id="formMasterGaji">
							@csrf
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead class="thead-light">
										<tr>
											<th>Nama Karyawan</th>
											<th>Gaji Pokok (Rp)</th>
											<th>Tarif Lembur/Jam (Rp)</th>
											<th>Tarif Denda/Menit (Rp)</th>
											<th>Kode Akun Beban (Jurnal)</th>
											<th>Komponen Gaji</th>
										</tr>
									</thead>
									<tbody>
										@foreach($users as $u)
										<tr>
											<td>{{ $u->name }}<br><small class="text-muted">{{ $u->email }}</small></td>
											<td>
												<input type="text" class="form-control input-format-angka mb-1" name="gaji[{{ $u->id }}][GajiPokok]" value="{{ number_format($u->GajiPokok ?? 0, 0, '', ',') }}" style="min-width:120px;">
											</td>
											<td>
												<input type="text" class="form-control input-format-angka mb-1" name="gaji[{{ $u->id }}][TarifLemburPerJam]" value="{{ number_format($u->TarifLemburPerJam ?? 0, 0, '', ',') }}" style="min-width:120px;">
											</td>
											<td>
												<input type="text" class="form-control input-format-angka mb-1" name="gaji[{{ $u->id }}][TarifDendaPerMenit]" value="{{ number_format($u->TarifDendaPerMenit ?? 0, 0, '', ',') }}" style="min-width:120px;">
											</td>
											<td>
												<select class="form-control mb-1" name="gaji[{{ $u->id }}][KodeAkunGaji]" style="min-width:180px;">
													<option value="">-- Pilih Akun Beban Gaji --</option>
													@foreach($rekeningBeban as $rek)
														<option value="{{ $rek->KodeRekening }}" {{ ($u->KodeAkunGaji == $rek->KodeRekening) ? 'selected' : '' }}>{{ $rek->KodeRekening }} - {{ $rek->NamaRekening }}</option>
													@endforeach
												</select>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-sm btn-info btn-komponen" data-id="{{ $u->id }}" data-name="{{ $u->name }}">
													<i class="fas fa-list"></i> Tunjangan & Potongan
												</button>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="mt-3 text-right">
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Master Gaji</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Komponen Gaji -->
<div class="modal fade" id="modalKomponen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tunjangan & Potongan: <span id="komponenUserName" class="font-weight-bold text-primary"></span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form id="formKomponen">
                @csrf
                <input type="hidden" name="user_id" id="komponenUserId">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tableKomponen">
                            <thead class="thead-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama (Makan/Transport/BPJS)</th>
                                    <th>Sifat</th>
                                    <th>Nominal (Rp)</th>
                                    <th width="10%"><button type="button" class="btn btn-sm btn-success w-100" id="btnAddKomponen"><i class="fas fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Komponen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
	jQuery(document).ready(function() {
		// Format input angka
		jQuery('.input-format-angka').on('keyup', function() {
			var val = jQuery(this).val().replace(/,/g, '');
			if(val) {
				jQuery(this).val(val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			}
		});

		jQuery('#formMasterGaji').on('submit', function(e) {
			e.preventDefault();
			let formData = jQuery(this).serialize();
			
			Swal.fire({
				title: 'Simpan Master Gaji?',
				text: "Data Gaji Pokok karyawan akan diupdate.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Simpan'
			}).then((result) => {
				if (result.isConfirmed) {
					jQuery.ajax({
						url: '{{ route("master-gaji-store") }}',
						type: 'POST',
						data: formData,
						success: function(res) {
							if(res.success) {
								Swal.fire('Berhasil!', res.message, 'success');
							} else {
								Swal.fire('Error', res.message, 'error');
							}
						},
						error: function(err) {
							Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
						}
					});
				}
			});
		});

		// Komponen Gaji
		jQuery('.btn-komponen').on('click', function() {
			let uid = jQuery(this).data('id');
			let uname = jQuery(this).data('name');
			
			jQuery('#komponenUserId').val(uid);
			jQuery('#komponenUserName').text(uname);
			jQuery('#tableKomponen tbody').empty();

			Swal.showLoading();
			jQuery.ajax({
				url: '{{ url("master-gaji/komponen") }}/' + uid,
				type: 'GET',
				success: function(res) {
					Swal.close();
					if(res && res.length > 0) {
						res.forEach(function(item) {
							addKomponenRow(item.Jenis, item.NamaKomponen, item.Sifat, item.Nominal);
						});
					} else {
						// add default 1 row
						addKomponenRow('Tunjangan', '', 'Harian', 0);
					}
					jQuery('#modalKomponen').modal('show');
				},
				error: function() {
					Swal.fire('Error', 'Gagal memuat komponen', 'error');
				}
			});
		});

		jQuery('#btnAddKomponen').on('click', function() {
			addKomponenRow('Tunjangan', '', 'Harian', 0);
		});

		function addKomponenRow(jenis, nama, sifat, nominal) {
			let html = `<tr>
				<td>
					<select name="komponen[${rowIdx}][Jenis]" class="form-control form-control-sm">
						<option value="Tunjangan" ${jenis == 'Tunjangan' ? 'selected' : ''}>Tunjangan (+)</option>
						<option value="Potongan" ${jenis == 'Potongan' ? 'selected' : ''}>Potongan (-)</option>
					</select>
				</td>
				<td>
					<input type="text" name="komponen[${rowIdx}][NamaKomponen]" class="form-control form-control-sm" value="${nama}" placeholder="Cth: Uang Makan" required>
				</td>
				<td>
					<select name="komponen[${rowIdx}][Sifat]" class="form-control form-control-sm">
						<option value="Harian" ${sifat == 'Harian' ? 'selected' : ''}>Harian (x Jml Hadir)</option>
						<option value="Tetap" ${sifat == 'Tetap' ? 'selected' : ''}>Tetap (Per Bulan)</option>
					</select>
				</td>
				<td>
					<input type="text" name="komponen[${rowIdx}][Nominal]" class="form-control form-control-sm input-format-angka" value="${formatAngka(nominal)}" required>
				</td>
				<td>
					<button type="button" class="btn btn-sm btn-danger btnRemoveKomponen"><i class="fas fa-trash"></i></button>
				</td>
			</tr>`;
			jQuery('#tableKomponen tbody').append(html);
			rowIdx++;
			rebindFormatAngka();
		}

		let rowIdx = 0;

		jQuery(document).on('click', '.btnRemoveKomponen', function() {
			jQuery(this).closest('tr').remove();
		});

		function formatAngka(angka) {
			return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}

		function rebindFormatAngka() {
			jQuery('.input-format-angka').off('keyup').on('keyup', function() {
				var val = jQuery(this).val().replace(/,/g, '');
				if(val) {
					jQuery(this).val(val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				}
			});
		}

		jQuery('#formKomponen').on('submit', function(e) {
			e.preventDefault();
			
			// Validasi manual agar muncul alert yang lebih jelas
			let isValid = true;
			jQuery('#formKomponen input[required]').each(function() {
				if (jQuery(this).val().trim() === '') {
					isValid = false;
					jQuery(this).addClass('is-invalid');
				} else {
					jQuery(this).removeClass('is-invalid');
				}
			});

			if (!isValid) {
				Swal.fire('Peringatan', 'Silakan lengkapi semua nama dan nominal komponen sebelum menyimpan.', 'warning');
				return;
			}

			let formData = jQuery(this).serialize();
			jQuery.ajax({
				url: '{{ route("master-gaji-komponen-store") }}',
				type: 'POST',
				data: formData,
				success: function(res) {
					if(res.success) {
						Swal.fire('Berhasil!', res.message, 'success');
						jQuery('#modalKomponen').modal('hide');
					} else {
						Swal.fire('Error', res.message, 'error');
					}
				},
				error: function() {
					Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
				}
			});
		});
	});
</script>
@endpush
