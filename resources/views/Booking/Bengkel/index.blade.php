<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Servis Bengkel - {{ $company->NamaPartner }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            max-height: 50px;
        }
        .hero-section {
            position: relative;
            background: url('{{ !empty($company->BannerBooking) ? $company->BannerBooking : "https://images.unsplash.com/photo-1486262715619-6708146fb92f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" }}') no-repeat center center;
            background-size: cover;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .booking-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: #fff;
            padding: 30px;
            margin-top: -50px; /* pull up over hero */
            position: relative;
            z-index: 10;
        }
        .btn-booking {
            background: {{ !empty($company->DefaultLandingPageColor) ? $company->DefaultLandingPageColor : '#0d6efd' }};
            color: white;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            width: 100%;
            border: none;
        }
        .btn-booking:hover {
            filter: brightness(0.9);
            color: white;
        }
        .info-section {
            padding-top: 40px;
        }
        .info-icon {
            color: {{ !empty($company->DefaultLandingPageColor) ? $company->DefaultLandingPageColor : '#0d6efd' }};
            font-size: 1.5rem;
            margin-right: 15px;
        }
        footer {
            background: #212529;
            color: #adb5bd;
            padding: 30px 0;
            margin-top: 50px;
        }
        .about-text p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                @if(!empty($company->icon))
                    <img src="{{ $company->icon }}" alt="Logo" class="me-3 rounded">
                @else
                    <i class="fas fa-tools text-primary fa-2x me-3"></i>
                @endif
                <span class="fw-bold fs-4">{{ $company->NamaPartner }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                @if(Auth::guard('pelanggan')->check())
                    <a href="{{ route('booking-bengkel.dashboard', $kodePartner) }}" class="btn btn-outline-primary fw-bold me-2 mt-2 mt-lg-0"><i class="fas fa-user-circle me-1"></i> Hai, {{ Auth::guard('pelanggan')->user()->NamaPelanggan }}</a>
                    <a href="{{ route('booking-bengkel.logout', $kodePartner) }}" class="btn btn-danger fw-bold mt-2 mt-lg-0"><i class="fas fa-sign-out-alt"></i></a>
                @else
                    <button class="btn btn-outline-primary fw-bold me-2 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                    <button class="btn btn-primary fw-bold mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar Member</button>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="display-4 fw-bold mb-3">{!! !empty($company->HeadlineBanner) ? strip_tags($company->HeadlineBanner) : 'Booking Servis Berkala' !!}</h1>
            <p class="lead mb-4">{!! !empty($company->SubHeadlineBanner) ? strip_tags($company->SubHeadlineBanner) : 'Layanan profesional, cepat, dan terpercaya untuk kendaraan Anda.' !!}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <div class="row">
            <!-- Left Column: Info & About -->
            <div class="col-lg-5 info-section pe-lg-5 mb-4">
                <h3 class="fw-bold mb-4">Tentang Kami</h3>
                <div class="text-muted mb-5 about-text">
                    @if(!empty($company->AboutUs))
                        {!! $company->AboutUs !!}
                    @elseif(!empty($company->TentangKami))
                        {!! $company->TentangKami !!}
                    @else
                        <p>Kami adalah bengkel terpercaya yang melayani berbagai macam perbaikan dan perawatan kendaraan Anda. Dengan mekanik berpengalaman dan peralatan modern, kami memastikan kendaraan Anda selalu dalam kondisi prima.</p>
                    @endif
                </div>


            </div>

            <!-- Right Column: Booking Form -->
            <div class="col-lg-7">
                <div class="booking-card">
                    <h4 class="fw-bold mb-4 text-center">Formulir Booking Online</h4>
                    
                    <div id="alert-container"></div>

                    <form id="bookingForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Plat Nomor Kendaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="PlatNomor" placeholder="Misal: B 1234 ABC" required style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="NoHP" placeholder="Misal: 08123456789" required value="{{ Auth::guard('pelanggan')->check() ? Auth::guard('pelanggan')->user()->NoTlp1 : '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="NamaPelanggan" placeholder="Nama Anda" required value="{{ Auth::guard('pelanggan')->check() ? Auth::guard('pelanggan')->user()->NamaPelanggan : '' }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Kedatangan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="TglBooking" id="TglBooking" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jam Kedatangan <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="JamBooking" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keluhan / Catatan Tambahan</label>
                            <textarea class="form-control" name="Keluhan" rows="3" placeholder="Jelaskan kendala pada kendaraan Anda..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Service Advisor (Opsional)</label>
                            <select name="KodeAdvisor" class="form-select">
                                <option value="">-- Bebas (Akan Disesuaikan Bengkel) --</option>
                                @if(isset($advisors))
                                    @foreach($advisors as $adv)
                                        <option value="{{ $adv->KodeMekanik }}">{{ $adv->NamaMekanik }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btnSubmit">
                            <i class="fas fa-calendar-check me-2"></i> Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Banners Carousel -->
    @if(!empty($company->Banner1) || !empty($company->Banner2) || !empty($company->Banner3))
    <div class="container mb-5 mt-5">
        <h3 class="fw-bold mb-4 text-center">Promo & Layanan Utama Kami</h3>
        <div id="promoCarousel" class="carousel slide shadow rounded overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @php $activeCount = 0; @endphp
                @for($i=1; $i<=3; $i++)
                    @if(!empty($company->{'Banner'.$i}))
                        <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="{{ $activeCount }}" class="{{ $activeCount == 0 ? 'active' : '' }}" aria-current="{{ $activeCount == 0 ? 'true' : 'false' }}"></button>
                        @php $activeCount++; @endphp
                    @endif
                @endfor
            </div>
            <div class="carousel-inner">
                @php $isFirst = true; @endphp
                @for($i=1; $i<=3; $i++)
                    @if(!empty($company->{'Banner'.$i}))
                        <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                            <img src="{{ $company->{'Banner'.$i} }}" class="d-block w-100" alt="Promo {{ $i }}" style="height: 400px; object-fit: cover;">
                            <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                                <h5 class="fw-bold">{{ $company->{'BannerHeader'.$i} ?? '' }}</h5>
                                <p class="mb-0">{{ $company->{'BannerText'.$i} ?? '' }}</p>
                            </div>
                        </div>
                        @php $isFirst = false; @endphp
                    @endif
                @endfor
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Login Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="login-alert"></div>
                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">No WhatsApp / Email</label>
                            <input type="text" class="form-control" name="login_identifier" required placeholder="Contoh: 08123456789">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold" id="btnLogin">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Daftar Member Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="register-alert"></div>
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="NamaPelanggan" required placeholder="Sesuai KTP/STNK">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No WhatsApp Aktif</label>
                            <input type="text" class="form-control" name="NoTlp1" required placeholder="Contoh: 08123456789">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" minlength="6" required placeholder="Minimal 6 karakter">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold" id="btnRegister">Daftar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 pt-5 pb-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 text-center text-md-start">
                    <h5 class="fw-bold mb-3">{{ $company->NamaPartner }}</h5>
                    <p class="text-muted"><small>
                        @if(!empty($company->TermAndConditionBookingOnline))
                            {!! strip_tags($company->TermAndConditionBookingOnline) !!}
                        @else
                            Layanan profesional, cepat, dan terpercaya untuk kendaraan Anda.
                        @endif
                    </small></p>
                </div>
                <div class="col-md-4 mb-4 text-center text-md-start">
                    <h5 class="fw-bold mb-3">Hubungi Kami</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ !empty($company->AlamatTagihan) ? $company->AlamatTagihan : 'Jl. Contoh Alamat Bengkel No. 123, Kota' }}</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2 text-primary"></i> {{ !empty($company->NoTlp) ? $company->NoTlp : '' }} {{ !empty($company->NoHP) ? ' / '.$company->NoHP : '' }}</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> {{ !empty($company->Email) ? $company->Email : '-' }}</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4 text-center text-md-start">
                    <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <a href="#" class="text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; text-decoration: none;"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; text-decoration: none;"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-2 mb-3">
            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <p class="mb-0 text-muted"><small>&copy; {{ date('Y') }} {{ $company->NamaPartner }}. All rights reserved.</small></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set min date to today
            var today = new Date().toISOString().split('T')[0];
            $('#TglBooking').attr('min', today);

            // Handle Booking Form
            $('#bookingForm').submit(function(e) {
                e.preventDefault();
                
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
                
                $.ajax({
                    url: "{{ route('booking-bengkel.store', $kodePartner) }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.success) {
                            $('#alert-container').html(`
                                <div class="alert alert-success">
                                    <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i> Booking Berhasil!</h5>
                                    <p class="mb-0">${res.message}</p>
                                </div>
                            `);
                            $('#bookingForm').slideUp();
                            $('html, body').animate({
                                scrollTop: $("#alert-container").offset().top - 100
                            }, 500);
                        } else {
                            $('#alert-container').html(`
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i> ${res.message}
                                </div>
                            `);
                            $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-calendar-check me-2"></i> Konfirmasi Booking');
                        }
                    },
                    error: function(err) {
                        $('#alert-container').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i> Terjadi kesalahan sistem. Silakan coba lagi.
                            </div>
                        `);
                        $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-calendar-check me-2"></i> Konfirmasi Booking');
                    }
                });
            });

            // Handle Login Form
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                $('#btnLogin').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: "{{ route('booking-bengkel.login', $kodePartner) }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.success) {
                            window.location.reload();
                        } else {
                            $('#login-alert').html(`<div class="alert alert-danger p-2"><small>${res.message}</small></div>`);
                            $('#btnLogin').prop('disabled', false).html('Masuk');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan sistem.';
                        if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('<br>');
                        } else if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        $('#login-alert').html(`<div class="alert alert-danger p-2"><small>${msg}</small></div>`);
                        $('#btnLogin').prop('disabled', false).html('Masuk');
                    }
                });
            });

            // Handle Register Form
            $('#registerForm').submit(function(e) {
                e.preventDefault();
                $('#btnRegister').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: "{{ route('booking-bengkel.register', $kodePartner) }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.success) {
                            window.location.reload();
                        } else {
                            $('#register-alert').html(`<div class="alert alert-danger p-2"><small>${res.message}</small></div>`);
                            $('#btnRegister').prop('disabled', false).html('Daftar Sekarang');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan sistem.';
                        if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('<br>');
                        } else if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        $('#register-alert').html(`<div class="alert alert-danger p-2"><small>${msg}</small></div>`);
                        $('#btnRegister').prop('disabled', false).html('Daftar Sekarang');
                    }
                });
            });

            // Handle Auto-fill when typing PlatNomor or NoHP
            $('#PlatNomor, #NoHP').on('blur', function() {
                let nomor = $(this).val();
                if (nomor.length > 3) {
                    $.ajax({
                        url: "{{ route('booking-bengkel.checkCustomer', $kodePartner) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            nomor: nomor
                        },
                        success: function(res) {
                            if(res.success && res.data) {
                                if(res.data.NamaPelanggan) $('#NamaPelanggan').val(res.data.NamaPelanggan);
                                if(res.data.NoTlp1 && !$('#NoHP').val()) $('#NoHP').val(res.data.NoTlp1);
                                if(res.data.PlatNomor && !$('#PlatNomor').val()) $('#PlatNomor').val(res.data.PlatNomor);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
