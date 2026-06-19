@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">Admin</li>
				<li class="breadcrumb-item active" aria-current="page">Offline Licenses</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
		<div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center  border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">Daftar Offline License (POS)</h3>
								</div>
							    <div class="icons d-flex">
									<button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" onclick="openAddModal()">Generate Lisensi Baru</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
								<div class="table-responsive">
									<table id="licenseTable" class="display" style="width:100%">
										<thead>
											<tr>
												<th>No</th>
												<th>Client Name</th>
												<th>License Key</th>
												<th>Berlaku Sampai</th>
                                                <th>Maks. Perangkat</th>
												<th>Status</th>
												<th class="text-end">Action</th>
											</tr>
										</thead>
										<tbody>
											@if (count($licenses) > 0)
                                                @php $no = 1; @endphp
												@foreach($licenses as $v)
												<tr>
													<td>{{ $no++ }}</td>
                                                    <td>{{ $v->client_name }}</td>
													<td style="font-family: monospace; font-weight: bold;">{{ $v->license_key }}</td>
													<td>{{ date('d M Y', strtotime($v->valid_until)) }}</td>
                                                    <td>
                                                        @php
                                                            $registeredDevices = \App\Models\OfflineLicenseDevice::where('offline_license_id', $v->id)->count();
                                                        @endphp
                                                        <span class="badge bg-info text-white">{{ $registeredDevices }} / {{ $v->max_devices }}</span>
                                                    </td>
													<td>
														<span class="badge {{ $v->status == 'active' ? 'bg-success' : 'bg-danger' }}">
															{{ strtoupper($v->status) }}
														</span>
													</td>
													<td class="text-end">
                                                        <form action="{{ route('admin.offline-licenses.toggle', $v->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm {{ $v->status == 'active' ? 'btn-warning' : 'btn-success' }}">
                                                                {{ $v->status == 'active' ? 'Blokir' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.offline-licenses.delete', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus lisensi ini secara permanen?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                        </form>
													</td>
												</tr>
												@endforeach
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
	</div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Buat Lisensi Baru (Bulk)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.offline-licenses.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="client_name" class="form-label">Nama Client / Toko / Cabang</label>
              <input type="text" class="form-control" id="client_name" name="client_name" required placeholder="Contoh: Toko Sejahtera">
            </div>
            <div class="mb-3">
              <label for="jumlah_lisensi" class="form-label">Jumlah Lisensi yang Dibuat</label>
              <input type="number" class="form-control" id="jumlah_lisensi" name="jumlah_lisensi" required min="1" max="100" value="1">
              <small class="form-text text-muted">Sistem akan otomatis men-generate Serial Number sebanyak angka ini.</small>
            </div>
            <div class="mb-3">
              <label for="max_devices" class="form-label">Maksimal Perangkat (Per Lisensi)</label>
              <input type="number" class="form-control" id="max_devices" name="max_devices" required min="1" value="1">
              <small class="form-text text-muted">Berapa komputer yang boleh memakai 1 Serial Number yang sama.</small>
            </div>
            <div class="mb-3">
              <label for="valid_until" class="form-label">Masa Berlaku Sampai</label>
              <input type="date" class="form-control" id="valid_until" name="valid_until" required value="{{ date('Y-m-d', strtotime('+1 year')) }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Generate Sekarang</button>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
	jQuery(document).ready(function() {
		jQuery('#licenseTable').DataTable({
			"pagingType": "simple_numbers",
            "order": [[ 0, "asc" ]]
		});
	});

    function openAddModal() {
        $('#client_name').val('');
        $('#jumlah_lisensi').val('1');
        $('#max_devices').val('1');
        $('#valid_until').val('{{ date('Y-m-d', strtotime('+1 year')) }}');
        
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('addModal')).show();
        } else {
            $('#addModal').modal('show');
        }
    }
</script>
@endpush
