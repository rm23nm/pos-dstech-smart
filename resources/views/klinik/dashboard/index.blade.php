@extends('parts.header')

@section('content')
<style>
    .cycle-wrapper {
        padding: 40px 20px;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .cycle-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        position: relative;
    }

    /* Lines connecting the grid items */
    .cycle-grid::before {
        content: '';
        position: absolute;
        top: 25%;
        left: 16%;
        right: 16%;
        height: 4px;
        background: #e4e6ef;
        z-index: 0;
    }

    .cycle-grid::after {
        content: '';
        position: absolute;
        bottom: 25%;
        left: 16%;
        right: 16%;
        height: 4px;
        background: #e4e6ef;
        z-index: 0;
    }

    /* Vertical line connecting row 1 and row 2 at the right end */
    .vertical-connector {
        position: absolute;
        top: 25%;
        right: 16%;
        bottom: 25%;
        width: 4px;
        background: #e4e6ef;
        z-index: 0;
    }

    @media (max-width: 991px) {
        .cycle-grid {
            grid-template-columns: 1fr;
        }
        .cycle-grid::before, .cycle-grid::after, .vertical-connector {
            display: none;
        }
    }

    .cycle-card {
        background: #fff;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: #3f4254;
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
    }

    .cycle-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(54, 153, 255, 0.15);
        border-color: #3699FF;
        z-index: 10;
    }

    .cycle-icon-box {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .cycle-card:hover .cycle-icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .cycle-step {
        position: absolute;
        top: -15px;
        left: -15px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #181c32;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .cycle-title {
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .cycle-desc {
        font-size: 0.95rem;
        color: #7e8299;
    }

    /* Color variants */
    .text-primary { color: #3699FF !important; }
    .bg-primary { background-color: #3699FF !important; }
    .text-success { color: #1BC5BD !important; }
    .bg-success { background-color: #1BC5BD !important; }
    .text-warning { color: #FFA800 !important; }
    .bg-warning { background-color: #FFA800 !important; }
    .text-danger { color: #F64E60 !important; }
    .bg-danger { background-color: #F64E60 !important; }
    .text-info { color: #8950FC !important; }
    .bg-info { background-color: #8950FC !important; }
    .text-dark { color: #181c32 !important; }
    .bg-dark { background-color: #181c32 !important; }
    .bg-secondary { background-color: #E4E6EF !important; }
</style>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-4">
                <div class="card card-custom bg-white border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="text-center mb-10 mt-5">
                            <h2 class="text-dark font-weight-bolder">Siklus Pelayanan Klinik</h2>
                            <p class="text-muted font-size-lg">Alur kerja operasional klinik mulai dari pasien datang hingga selesai</p>
                        </div>
                        
                        <div class="cycle-wrapper">
                            <div class="cycle-grid">
                                <div class="vertical-connector"></div>

                                <!-- Step 1: Ambil Antrean -->
                                <a href="{{ route('klinik-kiosk') }}" target="_blank" class="cycle-card">
                                    <div class="cycle-step bg-dark">1</div>
                                    <div class="cycle-icon-box bg-secondary text-dark">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="cycle-title">Kiosk Antrean</div>
                                    <div class="cycle-desc">Pasien mengambil nomor antrean awal melalui Kiosk. <br>
                                        <small class="text-primary mt-2 d-block"><i class="fas fa-external-link-alt"></i> Buka Kiosk</small>
                                        <small class="text-info mt-1 d-block" onclick="event.preventDefault(); window.open('{{ route('klinik-display') }}', '_blank');"><i class="fas fa-tv"></i> Buka Layar TV</small>
                                    </div>
                                </a>

                                <!-- Step 2: Pendaftaran -->
                                <a href="{{ route('klinik-appointments') }}" class="cycle-card">
                                    <div class="cycle-step bg-primary">2</div>
                                    <div class="cycle-icon-box bg-primary">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <div class="cycle-title text-primary">Pendaftaran Pasien</div>
                                    <div class="cycle-desc">Admin mendaftarkan antrean kunjungan pasien ke Dokter & Poli tujuan.</div>
                                </a>

                                <!-- Step 3: Pemeriksaan (EMR) -->
                                <div onclick="window.location.href='{{ route('klinik-emr') }}'" class="cycle-card" style="cursor: pointer;">
                                    <div class="cycle-step bg-danger">3</div>
                                    <div class="cycle-icon-box bg-danger">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="cycle-title text-danger">Pemeriksaan & EMR</div>
                                    <div class="cycle-desc">Dokter memanggil pasien, melakukan pemeriksaan fisik, diagnosa, dan meresepkan obat.</div>
                                    <div class="dropdown mt-3" onclick="event.stopPropagation();">
                                        <button class="btn btn-sm btn-outline-danger dropdown-toggle font-weight-bold" type="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-tv"></i> Buka Layar TV Poli
                                        </button>
                                        <ul class="dropdown-menu shadow">
                                            @if(isset($polis) && count($polis) > 0)
                                                @foreach($polis as $p)
                                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="window.open('{{ route('klinik-display-poli', ['poli_id' => $p->id]) }}', '_blank');"><i class="fas fa-desktop text-info mr-2"></i> {{ $p->NamaPoli }}</a></li>
                                                @endforeach
                                            @else
                                                <li><a class="dropdown-item text-muted" href="#">Belum ada Poli</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Step 6: Selesai -->
                                <a href="javascript:void(0);" class="cycle-card" style="border-color: #1BC5BD; background: #f3fcfc;">
                                    <div class="cycle-step bg-success">6</div>
                                    <div class="cycle-icon-box bg-success">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <div class="cycle-title text-success">Selesai</div>
                                    <div class="cycle-desc">Pasien pulang. Seluruh riwayat kunjungan dan transaksi tersimpan di sistem.</div>
                                </a>

                                <!-- Step 5: Pembayaran -->
                                <a href="{{ url('transaksi') }}" class="cycle-card">
                                    <div class="cycle-step bg-warning">5</div>
                                    <div class="cycle-icon-box bg-warning">
                                        <i class="fas fa-cash-register"></i>
                                    </div>
                                    <div class="cycle-title text-warning">Pembayaran (POS)</div>
                                    <div class="cycle-desc">Kasir menarik seluruh tagihan (Jasa Medis & Obat) lalu memproses pembayaran pasien.</div>
                                </a>

                                <!-- Step 4: Farmasi -->
                                <a href="javascript:void(0);" onclick="alert('Bagian Apotek terintegrasi dengan POS Apotek. Resep masuk otomatis untuk diracik.')" class="cycle-card">
                                    <div class="cycle-step bg-info">4</div>
                                    <div class="cycle-icon-box bg-info">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                    <div class="cycle-title text-info">Apotek / Farmasi</div>
                                    <div class="cycle-desc">Bagian Farmasi menyiapkan (meracik) obat pasien berdasarkan resep digital dari Dokter.</div>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
