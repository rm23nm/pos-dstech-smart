@extends('parts.header')
	
@section('content')
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Riwayat Akses Gate</h5>
        </div>
    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label">Log Riwayat Pintu Masuk
                    <span class="d-block text-muted pt-2 font-size-sm">Data realtime riwayat akses pengunjung melalui Smart Gate Tripod</span></h3>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Waktu Akses</th>
                                <th>Tipe Tiket/Kartu</th>
                                <th>ID / Barcode</th>
                                <th>Status Akses</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>
                                    @if($log->access_type == 'TIKET')
                                        <span class="badge badge-info">TIKET REGULER</span>
                                    @elseif($log->access_type == 'MEMBER')
                                        <span class="badge badge-primary">MEMBER/RFID</span>
                                    @else
                                        <span class="badge badge-secondary">TIDAK DIKENAL</span>
                                    @endif
                                </td>
                                <td><strong>{{ $log->identifier }}</strong></td>
                                <td>
                                    @if($log->status == 'GRANTED')
                                        <span class="badge badge-success">DIIZINKAN MASUK</span>
                                    @else
                                        <span class="badge badge-danger">AKSES DITOLAK</span>
                                    @endif
                                </td>
                                <td>{{ $log->message }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat akses gate.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
