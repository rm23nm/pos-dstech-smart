@extends('parts.header')

@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Stok Unit Kendaraan (Dealer)</li>
			</ol>
		</nav>
	</div>
</div>

<div class="d-flex flex-column-fluid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 px-4">
				<div class="card card-custom gutter-b bg-white border-0">
					<div class="card-header border-bottom-dark px-4">
						<div class="card-title mb-0">
							<h3 class="card-label mb-0 font-weight-bold text-body">Data Stok Unit Kendaraan
							</h3>
						</div>
                        <div class="card-toolbar">
                            <a href="{{ route('dealer.inventory.create') }}" class="btn btn-primary font-weight-bolder">
                                <i class="fas fa-plus"></i> Tambah Unit Masuk
                            </a>
                        </div>
					</div>
					<div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

						<table class="table table-bordered table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Model/Tipe</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Warna / Tahun</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventory as $index => $inv)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $inv->item->NamaItem ?? $inv->KodeItem }}</td>
                                    <td>{{ $inv->NoRangka }}</td>
                                    <td>{{ $inv->NoMesin }}</td>
                                    <td>{{ $inv->Warna }} / {{ $inv->Tahun }}</td>
                                    <td>
                                        @if($inv->Status == 0)
                                            <span class="badge badge-success">Ready</span>
                                        @elseif($inv->Status == 1)
                                            <span class="badge badge-secondary">Terjual</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($inv->Status == 0)
                                            <a href="{{ route('dealer.inventory.edit', $inv->id) }}" class="btn btn-sm btn-warning">Edit</a>
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
@endsection
