<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Login | DSMS POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-red: #d00904;
            --primary-blue: #0056b3;
            --secondary-blue: #1d8cf8;
            --accent-white: #ffffff;
            --bg-light: #f8f9fa;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            background-color: #ffffff;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
        }

        /* LEFT SIDE - SHOWCASE */
        .showcase-side {
            position: relative;
            background-color: #020617;
            overflow: hidden;
        }

        .carousel-item {
            height: 100vh;
            background: linear-gradient(135deg, #d01818 0%, #0072ff 100%); /* Background gradient merah-biru */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Mengubah dari cover menjadi contain agar gambar tidak terpotong */
            filter: saturate(1.2);
        }

        .carousel-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 86, 179, 0.7) 0%, rgba(208, 9, 4, 0.7) 100%);
            z-index: 1;
        }

        /* Fixed Content Over Left Side */
        .showcase-content {
            position: absolute;
            bottom: 10%;
            left: 0;
            width: 100%;
            padding: 0 50px;
            z-index: 2;
            color: white;
            text-align: left;
        }

        .showcase-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .showcase-subtitle {
            font-size: 1.1rem;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Demo Buttons Container */
        .demo-buttons-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .demo-title {
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #ffd700;
        }

        .btn-demo-auto {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border: none;
            font-weight: 600;
            padding: 12px 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .btn-demo-auto:hover {
            transform: translateY(-5px);
            background: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .btn-demo-auto i {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .btn-demo-auto.resto i { color: #fd7e14; }
        .btn-demo-auto.retail i { color: #198754; }
        .btn-demo-auto.hiburan i { color: #0dcaf0; }


        /* RIGHT SIDE - LOGIN FORM */
        .login-side {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            padding: 40px;
            position: relative;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-logo {
            width: 100px;
            margin-bottom: 15px;
        }

        .brand-name {
            font-weight: 800;
            color: var(--primary-blue);
            font-size: 1.8rem;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .brand-tagline {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
            border-color: #dee2e6;
        }

        .form-control {
            background-color: #f8f9fa;
            border-left: none;
            padding: 12px;
            border-color: #dee2e6;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
            border-color: var(--primary-blue);
        }

        .input-group:focus-within .input-group-text {
            background-color: #fff;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .btn-login {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 14px;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(0, 86, 179, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            background: #004494;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 86, 179, 0.3);
            color: white;
        }

        .btn-register {
            background: transparent;
            border: 1px solid var(--primary-red);
            color: var(--primary-red);
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .btn-register:hover {
            background: var(--primary-red);
            color: white;
        }

        .footer-links {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        /* Branding Strips */
        .top-strip {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-red), var(--primary-blue), var(--primary-red));
            z-index: 10;
        }

        /* Mobile adjustments */
        @media (max-width: 991.98px) {
            .split-layout {
                flex-direction: column-reverse;
            }
            .showcase-side {
                min-height: 100vh;
            }
            .login-side {
                min-height: 100vh;
            }
            .showcase-content {
                padding: 0 20px;
                bottom: 5%;
            }
            .showcase-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="top-strip"></div>

    <div class="container-fluid p-0">
        <div class="row g-0 split-layout">
            
            <!-- LEFT SIDE: PRESENTATION & DEMO -->
            <div class="col-lg-7 col-md-12 showcase-side">
                <div class="carousel-overlay"></div>
                
                <!-- Background Slides -->
                <div id="loginCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('images/misc/bg-login3.jpg') }}" alt="Slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/misc/bg-login2.webp') }}" onerror="this.src='{{ asset('images/misc/bg-login.png') }}'" alt="Slide 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/misc/bg-login1.png') }}" onerror="this.src='{{ asset('images/misc/bg-login.png') }}'" alt="Slide 3">
                        </div>
                    </div>
                </div>

                <!-- Fixed Content Over Slides -->
                <div class="showcase-content">
                    <h1 class="showcase-title">Satu Aplikasi,<br>Beragam Solusi Bisnis</h1>
                    <p class="showcase-subtitle">Pilih jenis usaha Anda dan nikmati kemudahan manajemen Point of Sales, Inventori, hingga Booking secara terpadu.</p>
                    
                    <div class="demo-buttons-container">
                        <div class="demo-title"><i class="bi bi-stars"></i> Coba Akun Demo Sekarang</div>
                        <div class="row g-3">
                            <div class="col-4">
                                <button type="button" class="btn btn-demo-auto resto w-100" data-email="demoresto@pos.dstechsmart.com" data-pass="12345678">
                                    <i class="bi bi-cup-hot"></i>
                                    <span style="font-size: 0.75rem;">Resto / F&B</span>
                                </button>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-demo-auto retail w-100" data-email="demoretail@pos.dstechsmart.com" data-pass="12345678">
                                    <i class="bi bi-cart3"></i>
                                    <span style="font-size: 0.75rem;">Retail / Shop</span>
                                </button>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-demo-auto hiburan w-100" data-email="gor.servicepos@gmail.com" data-pass="12345678">
                                    <i class="bi bi-controller"></i>
                                    <span style="font-size: 0.7rem;">Hiburan / Sewa</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: LOGIN FORM -->
            <div class="col-lg-5 col-md-12 login-side">
                <div class="login-form-container">
                    
                    <div class="brand-header">
                        <img src="{{ asset('images/misc/LogoFront.png') }}" alt="logo" class="brand-logo">
                        <div class="brand-name">DSMS POS</div>
                        <div class="brand-tagline">Welcome back! Please enter your details.</div>
                    </div>

                    <form method="post" action="{{ route('action-login') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Email / Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Password</label>
                                <a href="{{ route('forgotpassword') }}" class="text-decoration-none fw-semibold" style="font-size: 0.85rem; color: var(--primary-blue);">Lupa Password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember" style="font-size: 0.9rem;">Ingat Saya</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-login">MASUK <i class="bi bi-box-arrow-in-right ms-1"></i></button>
                            <a href="{{ route('daftar') }}" class="btn btn-register">DAFTAR AKUN BARU</a>
                        </div>
                    </form>

                    <div class="footer-links">
                        <p class="mb-0 text-muted">Butuh bantuan? <a href="#">Hubungi Support</a></p>
                        <p class="mt-4 mb-0 text-muted" style="font-size: 0.75rem;">&copy; 2026 DStech Smart. All rights reserved.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('sweetalert::alert')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto login logic for Demo Buttons
            $('.btn-demo-auto').on('click', function() {
                var email = $(this).data('email');
                var pass = $(this).data('pass');
                
                // Fill inputs
                $('input[name="email"]').val(email);
                $('input[name="password"]').val(pass);
                
                // Show loading state
                var $btn = $('.btn-login');
                $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>MENYIAPKAN DEMO...');
                $btn.prop('disabled', true);
                
                // Smooth submit delay
                setTimeout(function() {
                    $('form').submit();
                }, 400);
            });
        });
    </script>
</body>
</html>
