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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Jadwal Dokter</h3>
                                </div>
                                <div class="icons d-flex">
                                    <button class="btn btn-outline-primary rounded-pill font-weight-bold me-1 mb-1" data-bs-toggle="modal" data-bs-target="#modalDokter" onclick="tambahDokter()">
                                        <i class="fas fa-plus"></i> Tambah Dokter
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
                                    <table class="table table-bordered table-hover" id="tableDokter">
                                        <thead>
                                            <tr>
                                                <th>Nama Dokter</th>
                                                <th>Poli</th>
                                                <th>Spesialisasi</th>
                                                <th>No HP</th>
                                                <th>Jadwal Praktek</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($doctors as $d)
                                            <tr>
                                                <td>{{ $d->NamaDokter }}</td>
                                                <td>{{ $d->NamaPoli ?? '-' }}</td>
                                                <td>{{ $d->Spesialisasi }}</td>
                                                <td>{{ $d->NoHP }}</td>
                                                <td>{{ $d->JadwalPraktik }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalDokter" 
                                                            onclick="editDokter({{ $d->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="{{ route('klinik-doctors.destroy', $d->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
<div class="modal fade" id="modalDokter" tabindex="-1" aria-labelledby="modalDokterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formDokter" method="POST" action="{{ route('klinik-doctors.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDokterLabel">Tambah Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" name="NamaDokter" id="NamaDokter" class="form-control" required placeholder="Contoh: dr. Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label>Poli</label>
                        <select name="PoliID" id="PoliID" class="form-control">
                            <option value="">- Pilih Poli (Opsional) -</option>
                            @foreach($polis as $pl)
                                <option value="{{ $pl->id }}">{{ $pl->NamaPoli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Spesialisasi</label>
                        <input type="text" name="Spesialisasi" id="Spesialisasi" class="form-control" placeholder="Contoh: Dokter Umum, Dokter Gigi">
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="NoHP" id="NoHP" class="form-control" placeholder="Contoh: 0812xxxx">
                    </div>
                    <div class="mb-3">
                        <label>Jadwal Praktek</label>
                        <textarea name="JadwalPraktik" id="JadwalPraktik" class="form-control" rows="3" placeholder="Contoh: Senin - Jumat, 08:00 - 15:00"></textarea>
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
    var doctorsData = {};
    @foreach($doctors as $d)
        doctorsData[{{ $d->id }}] = {
            id: {{ $d->id }},
            NamaDokter: {!! json_encode($d->NamaDokter) !!},
            PoliID: {!! json_encode($d->PoliID) !!},
            Spesialisasi: {!! json_encode($d->Spesialisasi) !!},
            NoHP: {!! json_encode($d->NoHP) !!},
            JadwalPraktik: {!! json_encode($d->JadwalPraktik) !!}
        };
    @endforeach

    $(document).ready(function() {
        $('#tableDokter').DataTable();
    });

    function tambahDokter() {
        $('#modalDokterLabel').text('Tambah Dokter');
        $('#formDokter').attr('action', '{{ route('klinik-doctors.store') }}');
        $('#NamaDokter').val('');
        $('#PoliID').val('');
        $('#Spesialisasi').val('');
        $('#NoHP').val('');
        $('#JadwalPraktik').val('');
        
        // Remove _method input if exists
        $('#formDokter').find('input[name="_method"]').remove();
    }

    function editDokter(id) {
        var d = doctorsData[id];
        if(!d) return;

        $('#modalDokterLabel').text('Edit Dokter');
        $('#formDokter').attr('action', '{{ url('klinik-doctors/update') }}/' + d.id);
        
        $('#NamaDokter').val(d.NamaDokter);
        $('#PoliID').val(d.PoliID);
        $('#Spesialisasi').val(d.Spesialisasi);
        $('#NoHP').val(d.NoHP);
        $('#JadwalPraktik').val(d.JadwalPraktik);

        // Remove _method input if exists
        $('#formDokter').find('input[name="_method"]').remove();
    }
</script>
@endpush
@endsection
