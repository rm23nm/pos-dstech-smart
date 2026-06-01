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
                                    <h3 class="card-label mb-0 font-weight-bold text-body">Rekam Medis (EMR) - Antrean Hari Ini</h3>
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
                                    <table class="table table-bordered table-hover" id="tableEMR">
                                        <thead>
                                            <tr>
                                                <th>No Antrean</th>
                                                <th>No RM</th>
                                                <th>Nama Pasien</th>
                                                <th>Poli</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $a)
                                            <tr>
                                                <td><span class="badge bg-primary fs-6">{{ $a->NoAntrean }}</span></td>
                                                <td>{{ $a->NoRM }}</td>
                                                <td>
                                                    {{ $a->NamaPasien }}
                                                    @if($a->TanggalLahir)
                                                    <br><small class="text-muted">{{ date_diff(date_create($a->TanggalLahir), date_create('now'))->y }} Tahun</small>
                                                    @endif
                                                </td>
                                                <td>{{ $a->NamaPoli }}</td>
                                                <td>{{ $a->NamaDokter }}</td>
                                                <td>
                                                    @if($a->Status == 'Menunggu')
                                                        <span class="badge bg-warning text-dark">{{ $a->Status }}</span>
                                                    @elseif($a->Status == 'Diperiksa')
                                                        <span class="badge bg-info text-dark">{{ $a->Status }}</span>
                                                    @elseif($a->Status == 'Selesai')
                                                        <span class="badge bg-success text-white">{{ $a->Status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex" style="gap: 5px;">
                                                        <button class="btn btn-sm btn-info text-white panggil-btn" data-id="{{ $a->id }}">
                                                            <i class="fas fa-bullhorn"></i> Panggil
                                                        </button>
                                                        <a href="{{ route('klinik-emr.create', $a->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-stethoscope"></i> Periksa / EMR
                                                        </a>
                                                    </div>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableEMR').DataTable({
            "order": [[ 0, "asc" ]] // Urutkan No Antrean terkecil di atas
        });
    });
</script>
@endpush
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.panggil-btn').click(function(e) {
            e.preventDefault();
            var appointmentId = $(this).data('id');
            var btn = $(this);
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memanggil...');

            $.ajax({
                url: '{{ route('klinik-appointments.panggil') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: appointmentId
                },
                success: function(response) {
                    if(response.success) {
                        btn.html('<i class="fas fa-bullhorn"></i> Panggil Ulang');
                        btn.removeClass('btn-info').addClass('btn-warning');
                        // Optional: update the row status to Diperiksa automatically or just reload
                        setTimeout(function(){ location.reload(); }, 1500);
                    } else {
                        alert(response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-bullhorn"></i> Panggil');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem.');
                    btn.prop('disabled', false).html('<i class="fas fa-bullhorn"></i> Panggil');
                }
            });
        });
    });
</script>
@endsection