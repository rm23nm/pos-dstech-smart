@extends('parts.header')
@section('content')

<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Master Data / Pengaturan BPJS</li>
            </ol>
        </nav>
    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-custom gutter-b bg-white border-0">
                    <div class="card-header align-items-center border-0">
                        <div class="card-title mb-0">
                            <h3 class="card-label mb-0 font-weight-bold text-body">Pengaturan Bridging BPJS Kesehatan</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Silakan masukkan parameter kredensial API BPJS (VClaim / Antrean JKN) yang Bapak dapatkan dari TrustMark BPJS.</p>

                        <form action="{{ route('klinik-bpjs.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label>Cons ID (Consumer ID)</label>
                                <input type="text" class="form-control" name="ConsID" value="{{ $setting->ConsID ?? '' }}" placeholder="Misal: 12345">
                            </div>
                            <div class="mb-3">
                                <label>Secret Key</label>
                                <input type="text" class="form-control" name="SecretKey" value="{{ $setting->SecretKey ?? '' }}" placeholder="Misal: 5fD31...xyz">
                            </div>
                            <div class="mb-3">
                                <label>User Key (Opsional / VClaim)</label>
                                <input type="text" class="form-control" name="UserKey" value="{{ $setting->UserKey ?? '' }}" placeholder="Bisa dikosongkan jika tidak ada">
                            </div>
                            <div class="mb-3">
                                <label>Base URL API</label>
                                <input type="url" class="form-control" name="BaseUrl" value="{{ $setting->BaseUrl ?? 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest' }}" placeholder="https://apijkn...">
                            </div>

                            <hr>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="isSandbox" id="isSandbox" {{ ($setting->isSandbox ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label text-warning" for="isSandbox">
                                    <strong>Aktifkan Mode Sandbox (Simulasi)</strong> <br>
                                    <small>Jika aktif, sistem tidak akan terhubung ke BPJS asli. Berguna saat kunci belum turun atau untuk testing aplikasi.</small>
                                </label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="isActive" id="isActive" {{ ($setting->isActive ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Aktifkan Bridging BPJS di Sistem Pendaftaran</label>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save"></i> Simpan Pengaturan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
