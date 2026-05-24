@extends('parts.header')
	
@section('content')

<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item active" aria-current="page">
					<a href="{{route('gatedevices')}}">Manajemen Perangkat Gate</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Input Perangkat Gate</li>
			</ol>
		</nav>
	</div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				<div class="row">
					<div class="col-lg-12 col-xl-12 px-4">
						<div class="card card-custom gutter-b bg-transparent shadow-none border-0" >
							<div class="card-header align-items-center  border-bottom-dark px-0">
								<div class="card-title mb-0">
									<h3 class="card-label mb-0 font-weight-bold text-body">
										@if (count($device) > 0)
                                    		Edit Perangkat Gate
	                                	@else
	                                    	Tambah Perangkat Gate
	                                	@endif
									</h3>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12  px-4">
						<div class="card card-custom gutter-b bg-white border-0" >
							<div class="card-body" >
								@if (count($device) > 0)
                            		<form action="{{route('gatedevices-edit')}}" method="post">
                            	@else
                                	<form action="{{route('gatedevices-store')}}" method="post">
                            	@endif
                            		@csrf
	                            	<div class="form-group row">
	                            		<div class="col-md-6">
	                            			<label  class="text-body">Device ID</label>
	                            			<fieldset class="form-group mb-3">
	                            				<input type="text" class="form-control" id="DeviceID" name="DeviceID" placeholder="Contoh: ESP-GATE-01" value="{{ count($device) > 0 ? $device[0]->DeviceID : '' }}" required="">
                                                <input type="hidden" class="form-control" id="id" name="id" value="{{ count($device) > 0 ? $device[0]->id : '' }}">
	                            			</fieldset>
	                            		</div>

                                        <div class="col-md-6">
	                            			<label  class="text-body">Nama Gate</label>
	                            			<fieldset class="form-group mb-3">
	                            				<input type="text" class="form-control" id="DeviceName" name="DeviceName" placeholder="Contoh: Pintu Masuk Timur" value="{{ count($device) > 0 ? $device[0]->DeviceName : '' }}" required="">
	                            			</fieldset>
	                            		</div>

                                        <div class="col-md-6">
	                            			<label  class="text-body">Status</label>
	                            			<fieldset class="form-group mb-3">
                                                <select name="Status" id="Status" class="js-example-basic-single js-states form-control bg-transparent" required>
                                                    <option value="1" {{ count($device) > 0 && $device[0]->Status == 1 ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ count($device) > 0 && $device[0]->Status == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                                </select>
	                            			</fieldset>
	                            		</div>

                                        <div class="col-md-12">
	                            			<label  class="text-body">Tiket & Paket Member yang Diizinkan (Pilih Akses Gate)</label>
	                            			<fieldset class="form-group mb-3">
                                                <select name="AllowedTickets[]" id="AllowedTickets" class="form-control" multiple="multiple" required>
                                                    @foreach($tickets as $tiket)
                                                        <option value="{{ $tiket->KodeItem }}" {{ in_array($tiket->KodeItem, $allowedTickets) ? 'selected' : '' }}>
                                                            {{ $tiket->NamaItem }} ({{ $tiket->KodeItem }})
                                                        </option>
                                                    @endforeach
                                                </select>
	                            			</fieldset>
	                            		</div>

	                            		<div class="col-md-12">
	                            			<button type="submit" class="btn btn-success text-white font-weight-bold me-1 mb-1">Simpan</button>
	                            		</div>
	                            	</div>

                            	</form>
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
<script type="text/javascript">
	$(function () {
		$(document).ready(function () {
			$('#Status').select2();
            $('#AllowedTickets').select2({
                placeholder: "Pilih tiket atau paket member",
                allowClear: true
            });
		})
	})
</script>
@endpush
