<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Area - {{ $company->NamaCompany ?? 'Bengkel' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; color: #0d6efd !important; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 12px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #edf2f9; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('booking-bengkel.index', $kodePartner) }}">
                <i class="fas fa-tools me-2"></i> {{ $company->NamaCompany ?? 'Bengkel Smart' }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <a href="{{ route('booking-bengkel.index', $kodePartner) }}" class="btn btn-outline-secondary me-2 mt-2 mt-lg-0">Kembali ke Booking</a>
                <a href="{{ route('booking-bengkel.logout', $kodePartner) }}" class="btn btn-danger fw-bold mt-2 mt-lg-0"><i class="fas fa-sign-out-alt me-1"></i> Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Sidebar Profile -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $pelanggan->NamaPelanggan }}</h4>
                        <p class="text-muted mb-3">{{ $pelanggan->NoTlp1 }} | {{ $pelanggan->Email }}</p>
                        
                        <div class="d-flex justify-content-between text-start mt-4 border-top pt-3">
                            <span class="text-muted"><i class="fas fa-id-card me-2"></i>ID Pelanggan</span>
                            <span class="fw-bold">{{ $pelanggan->KodePelanggan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Booking -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="fas fa-calendar-alt me-2"></i>Jadwal Booking Anda</h5>
                    </div>
                    <div class="card-body p-0">
                        @if(isset($bookings) && count($bookings) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Tanggal & Waktu</th>
                                            <th>Kendaraan</th>
                                            <th>Keluhan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $book)
                                            <tr>
                                                <td class="ps-4 fw-bold">
                                                    {{ \Carbon\Carbon::parse($book->TglBooking)->format('d M Y') }}<br>
                                                    <small class="text-muted">{{ $book->JamBooking }}</small>
                                                </td>
                                                <td>{{ $book->PlatNomor }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($book->Keluhan, 30) }}</td>
                                                <td>
                                                    @if($book->StatusBooking == 0)
                                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                                    @elseif($book->StatusBooking == 1)
                                                        <span class="badge bg-info text-white">Dikonfirmasi</span>
                                                    @elseif($book->StatusBooking == 2)
                                                        <span class="badge bg-primary">Proses Servis</span>
                                                    @elseif($book->StatusBooking == 3)
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <span class="badge bg-danger">Batal</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">Anda belum memiliki jadwal booking yang aktif.</p>
                                <a href="{{ route('booking-bengkel.index', $kodePartner) }}" class="btn btn-sm btn-outline-primary mt-2">Buat Booking Baru</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- History Servis -->
                <div class="card h-100">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-history me-2"></i>Riwayat Servis (Selesai)</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($history && count($history) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">No Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Total (Rp)</th>
                                            <th>Status</th>
                                            <th class="text-end pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history as $trx)
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary">{{ $trx->NoTransaksi ?? $trx->id }}</td>
                                                <td>{{ isset($trx->TglTransaksi) ? \Carbon\Carbon::parse($trx->TglTransaksi)->format('d M Y, H:i') : '-' }}</td>
                                                <td>{{ isset($trx->TotalTransaksi) ? number_format($trx->TotalTransaksi, 0, ',', '.') : '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success rounded-pill">Selesai</span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <button class="btn btn-sm btn-outline-primary btn-detail-history" data-no="{{ $trx->NoTransaksi }}">
                                                        <i class="fas fa-search me-1"></i> Detail
                                                    </button>
                                                    <a href="{{ route('booking-bengkel.print-faktur', ['kodePartner' => $kodePartner, 'noTransaksi' => $trx->NoTransaksi]) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-print me-1"></i> Cetak
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="text-muted mb-3" style="font-size: 48px;">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="fw-bold">Belum Ada Riwayat</h5>
                                <p class="text-muted">Kendaraan Anda belum memiliki riwayat servis yang diselesaikan di bengkel ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $company->NamaCompany ?? 'Bengkel Smart' }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal Detail History -->
    <div class="modal fade" id="modalDetailHistory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-invoice me-2"></i>Detail Transaksi <span id="detailNoTrans" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loadingDetail" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Mengambil data rincian...</p>
                    </div>
                    <div id="contentDetail" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Item / Jasa</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga (Rp)</th>
                                        <th class="text-end">Subtotal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody id="detailItemsBody">
                                    <!-- Populated by JS -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end text-muted">Total</th>
                                        <th class="text-end fw-bold text-primary fs-5" id="detailGrandTotal">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-detail-history').click(function() {
                const noTransaksi = $(this).data('no');
                $('#detailNoTrans').text('#' + noTransaksi);
                $('#loadingDetail').removeClass('d-none');
                $('#contentDetail').addClass('d-none');
                $('#detailItemsBody').empty();
                $('#detailGrandTotal').text('0');
                
                const modal = new bootstrap.Modal(document.getElementById('modalDetailHistory'));
                modal.show();

                $.ajax({
                    url: "{{ route('booking-bengkel.history-detail', $kodePartner) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        NoTransaksi: noTransaksi
                    },
                    success: function(res) {
                        $('#loadingDetail').addClass('d-none');
                        if(res.success && res.details.length > 0) {
                            $('#contentDetail').removeClass('d-none');
                            let html = '';
                            res.details.forEach(item => {
                                let harga = parseInt(item.HargaNet) || parseInt(item.Harga) || 0;
                                let qty = parseFloat(item.Qty) || 0;
                                let total = harga * qty;
                                html += `<tr>
                                    <td>${item.NamaItem || item.Keterangan || item.KodeItem}</td>
                                    <td class="text-center">${qty}</td>
                                    <td class="text-end">${harga.toLocaleString('id-ID')}</td>
                                    <td class="text-end fw-bold">${total.toLocaleString('id-ID')}</td>
                                </tr>`;
                            });
                            $('#detailItemsBody').html(html);
                            $('#detailGrandTotal').text(parseInt(res.header.TotalTransaksi).toLocaleString('id-ID'));
                        } else {
                            $('#detailItemsBody').html('<tr><td colspan="4" class="text-center text-muted">Data rincian tidak tersedia</td></tr>');
                            $('#contentDetail').removeClass('d-none');
                        }
                    },
                    error: function() {
                        $('#loadingDetail').addClass('d-none');
                        $('#detailItemsBody').html('<tr><td colspan="4" class="text-center text-danger">Gagal mengambil data</td></tr>');
                        $('#contentDetail').removeClass('d-none');
                    }
                });
            });
        });
    </script>
</body>
</html>
