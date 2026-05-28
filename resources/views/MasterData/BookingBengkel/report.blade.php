@extends('parts.header')
@section('content')
<div class="subheader py-2 py-lg-6 subheader-solid">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="col-auto">
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    Laporan Booking Bengkel
                </h5>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-custom mb-5">
            <div class="card-body">
                <form action="{{ route('report.booking.bengkel') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label>Tanggal Awal</label>
                            <input type="date" class="form-control" name="StartDate" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" name="EndDate" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="StatusBooking" class="form-control">
                                <option value="Semua" {{ $status == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="0" {{ $status == '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ $status == '1' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="2" {{ $status == '2' ? 'selected' : '' }}>Selesai (PKB)</option>
                                <option value="3" {{ $status == '3' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Cari Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-custom">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bookingTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal Booking</th>
                                <th>Jam</th>
                                <th>Plat Nomor</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($b->TglBooking)) }}</td>
                                <td>{{ date('H:i', strtotime($b->JamBooking)) }}</td>
                                <td><span class="badge badge-dark">{{ $b->PlatNomor }}</span></td>
                                <td>{{ $b->NamaPelanggan }}</td>
                                <td>
                                    <a href="https://wa.me/{{ preg_replace('/^08/', '628', $b->NoHP) }}" target="_blank" class="text-success font-weight-bold">
                                        <i class="fab fa-whatsapp text-success"></i> {{ $b->NoHP }}
                                    </a>
                                </td>
                                <td>{{ $b->Keluhan }}</td>
                                <td>
                                    @if($b->StatusBooking == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($b->StatusBooking == 1)
                                        <span class="badge badge-info">Dikonfirmasi</span>
                                    @elseif($b->StatusBooking == 2)
                                        <span class="badge badge-success">Selesai (PKB)</span>
                                    @else
                                        <span class="badge badge-danger">Dibatalkan</span>
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
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function() {
        jQuery('#bookingTable').DataTable({
            responsive: true,
            order: [[0, 'desc'], [1, 'asc']]
        });
    });
</script>
@endpush
