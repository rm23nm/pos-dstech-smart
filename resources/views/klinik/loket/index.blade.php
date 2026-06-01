@extends('parts.header')
@section('content')

<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white mb-0 px-0 py-2">
                <li class="breadcrumb-item active" aria-current="page">Pengaturan Display & Loket Pendaftaran</li>
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

        <div class="card card-custom gutter-b bg-white border-0">
            <div class="card-header align-items-center border-0">
                <div class="card-title mb-0">
                    <h3 class="card-label mb-0 font-weight-bold text-body">Daftar Loket Fisik Kiosk
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoketModal">
                        <i class="fas fa-plus"></i> Tambah Loket
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">Data loket di bawah ini akan digambar sebagai kotak-kotak loket pada layar TV Kiosk Pendaftaran. Anda juga perlu memilih loket ini saat akan memanggil pasien.</p>
                
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Loket</th>
                            <th>Melayani Jalur</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lokets as $index => $l)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $l->NamaLoket }}</td>
                            <td>
                                @if(($l->TipeAntrean ?? 'Semua') == 'Semua')
                                    <span class="badge bg-secondary text-white">Semua Jalur</span>
                                @else
                                    <span class="badge bg-info text-white">{{ $l->TipeAntrean }}</span>
                                @endif
                            </td>
                            <td>
                                @if($l->isActive)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-danger text-white">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editLoketModal{{ $l->id }}"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('klinik-loket.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus loket ini?');">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $l->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editLoketModal{{ $l->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('klinik-loket.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $l->id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Loket</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama Loket</label>
                                                <input type="text" class="form-control" name="NamaLoket" value="{{ $l->NamaLoket }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Melayani Jalur Antrean</label>
                                                <select class="form-control" name="TipeAntrean">
                                                    <option value="Semua" {{ ($l->TipeAntrean ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua Jalur</option>
                                                    <option value="Umum" {{ ($l->TipeAntrean ?? '') == 'Umum' ? 'selected' : '' }}>Jalur Umum</option>
                                                    <option value="BPJS" {{ ($l->TipeAntrean ?? '') == 'BPJS' ? 'selected' : '' }}>Jalur BPJS</option>
                                                    <option value="Asuransi" {{ ($l->TipeAntrean ?? '') == 'Asuransi' ? 'selected' : '' }}>Jalur Asuransi Swasta</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select class="form-control" name="isActive">
                                                    <option value="1" {{ $l->isActive ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ !$l->isActive ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card card-custom gutter-b bg-white border-0 mt-5">
            <div class="card-header align-items-center border-0">
                <div class="card-title mb-0">
                    <h3 class="card-label mb-0 font-weight-bold text-body">Link Layar TV Kiosk
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <p>Gunakan link di bawah ini untuk ditampilkan di Layar TV Pendaftaran Anda. Video Promosi dan Background Kiosk dapat diatur di <a href="{{ route('companysetting') }}">Pengaturan Perusahaan</a>.</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{ route('klinik-display') }}" readonly id="displayUrl">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyUrl()">Copy</button>
                    <a href="{{ route('klinik-display') }}" target="_blank" class="btn btn-primary">Buka Layar TV</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="addLoketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('klinik-loket.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Loket Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Loket (Contoh: LOKET 1, LOKET UMUM)</label>
                        <input type="text" class="form-control" name="NamaLoket" required>
                    </div>
                    <div class="mb-3">
                        <label>Melayani Jalur Antrean</label>
                        <select class="form-control" name="TipeAntrean">
                            <option value="Semua">Semua Jalur</option>
                            <option value="Umum">Jalur Umum</option>
                            <option value="BPJS">Jalur BPJS</option>
                            <option value="Asuransi">Jalur Asuransi Swasta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" name="isActive">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function copyUrl() {
        var copyText = document.getElementById("displayUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Copied the text: " + copyText.value);
    }
</script>
@endpush
