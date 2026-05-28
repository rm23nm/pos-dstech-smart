@extends('parts.header')

@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb bg-white mb-0 px-0 py-2">
				<li class="breadcrumb-item"><a href="{{ route('dealer.inventory.index') }}">Stok Unit Kendaraan</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ isset($inventory) ? 'Edit Unit' : 'Tambah Unit Masuk' }}</li>
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
							<h3 class="card-label mb-0 font-weight-bold text-body">{{ isset($inventory) ? 'Edit Unit' : 'Tambah Unit Masuk' }}</h3>
						</div>
					</div>
					<div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

						<form action="{{ isset($inventory) ? route('dealer.inventory.update', $inventory->id) : route('dealer.inventory.store') }}" method="POST">
                            @csrf
                            @if(isset($inventory))
                                @method('PUT')
                            @endif
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Pilih Model / Tipe</label>
                                <div class="col-sm-6">
                                    <select name="KodeItem" class="form-control" required>
                                        <option value="">-- Pilih Model Kendaraan --</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->KodeItem }}" {{ (isset($inventory) && $inventory->KodeItem == $item->KodeItem) ? 'selected' : '' }}>
                                                {{ $item->NamaItem }} ({{ $item->KodeItem }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Master Item dengan TypeItem 7 (Unit Kendaraan)</small>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">No Rangka</label>
                                <div class="col-sm-6">
                                    <input type="text" name="NoRangka" class="form-control" value="{{ $inventory->NoRangka ?? old('NoRangka') }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">No Mesin</label>
                                <div class="col-sm-6">
                                    <input type="text" name="NoMesin" class="form-control" value="{{ $inventory->NoMesin ?? old('NoMesin') }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Warna</label>
                                <div class="col-sm-6">
                                    <input type="text" name="Warna" class="form-control" value="{{ $inventory->Warna ?? old('Warna') }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Tahun</label>
                                <div class="col-sm-6">
                                    <input type="number" name="Tahun" class="form-control" value="{{ $inventory->Tahun ?? old('Tahun') }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label">Harga Beli</label>
                                <div class="col-sm-6">
                                    <input type="text" name="HargaBeli" class="form-control" value="{{ $inventory->HargaBeli ?? old('HargaBeli', 0) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary">Simpan Unit</button>
                                    <a href="{{ route('dealer.inventory.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
