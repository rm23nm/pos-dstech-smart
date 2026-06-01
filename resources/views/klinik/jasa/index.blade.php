@extends('parts.header')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-4">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 px-4">
                        <div class="card card-custom gutter-b bg-transparent shadow-none border-0">
                            <div class="card-header align-items-center border-bottom-dark px-0">
                                <div class="card-title mb-0">
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Jasa Medis & Tindakan</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalJasa" onclick="tambahJasa()">
                                        <i class="fas fa-plus"></i> Tambah Jasa Medis
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 px-4">
                        <div class="card card-custom gutter-b bg-white border-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableJasa">
                                        <thead>
                                            <tr>
                                                <th>Kode Jasa</th>
                                                <th>Nama Tindakan / Jasa</th>
                                                <th>Harga (Rp)</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jasas as $j)
                                            <tr>
                                                <td>{{ $j->KodeJasa }}</td>
                                                <td>{{ $j->NamaJasa }}</td>
                                                <td>{{ number_format($j->Harga, 0, ',', '.') }}</td>
                                                <td>{{ $j->Deskripsi }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editJasa({{ json_encode($j) }})"><i class="fas fa-edit"></i></button>
                                                    <a href="{{ route('klinik-jasa.destroy', $j->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalJasa" tabindex="-1" aria-labelledby="modalJasaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formJasa" method="POST" action="{{ route('klinik-jasa.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJasaLabel">Tambah Jasa Medis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="KodeJasa" id="KodeJasa" class="form-control" required placeholder="Contoh: JS-001">
                    </div>
                    <div class="mb-3">
                        <label>Nama Tindakan / Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="NamaJasa" id="NamaJasa" class="form-control" required placeholder="Contoh: Konsultasi Dokter Umum">
                    </div>
                    <div class="mb-3">
                        <label>Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="Harga" id="Harga" class="form-control" required placeholder="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="Deskripsi" id="Deskripsi" class="form-control" rows="3" placeholder="Keterangan tindakan"></textarea>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableJasa').DataTable();
    });

    function tambahJasa() {
        $('#modalJasaLabel').text('Tambah Jasa Medis');
        $('#formJasa').attr('action', '{{ route('klinik-jasa.store') }}');
        $('#KodeJasa').val('');
        $('#NamaJasa').val('');
        $('#Harga').val('');
        $('#Deskripsi').val('');
        
        // Remove _method input if exists
        $('#formJasa').find('input[name="_method"]').remove();
    }

    function editJasa(data) {
        $('#modalJasaLabel').text('Edit Jasa Medis');
        $('#formJasa').attr('action', '{{ url('klinik-jasa/update') }}/' + data.id);
        
        $('#KodeJasa').val(data.KodeJasa);
        $('#NamaJasa').val(data.NamaJasa);
        $('#Harga').val(data.Harga);
        $('#Deskripsi').val(data.Deskripsi);

        // Remove _method input if exists
        $('#formJasa').find('input[name="_method"]').remove();
        
        $('#modalJasa').modal('show');
    }
</script>
@endpush
@endsection