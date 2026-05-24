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
            background-color: #020617;
            overflow: hidden;
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(208, 9, 4, 0.8) 0%, rgba(0, 86, 179, 0.8) 100%);
            position: relative;
            overflow: hidden;
        }

        /* Restore Old Background Image */
        .login-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('images/misc/bg-login3.jpg') }}");
            background-size: cover;
            background-position: center;
            opacity: 0.8;
            z-index: 0;
            filter: saturate(1.2);
        }

        /* Decorative Background Elements */

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            z-index: 1;
            border: 1px solid rgba(255,255,255,0.2);
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 120px;
            margin-bottom: 15px;
        }

        .brand-name {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .brand-tagline {
            color: #666;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: var(--secondary-blue);
        }

        .form-control {
            border-left: none;
            padding: 12px;
            border-radius: 0 10px 10px 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--secondary-blue);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--secondary-blue);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-blue), #002f5c);
            border: none;
            color: white;
            padding: 14px;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 10px;
            box-shadow: 0 8px 15px rgba(0, 86, 179, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 86, 179, 0.3);
            color: white;
        }

        .btn-register {
            background: transparent;
            border: 2px solid var(--primary-red);
            color: var(--primary-red);
            padding: 10px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: var(--primary-red);
            color: white;
        }

        .footer-links {
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: var(--secondary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        /* Branding Strips */
        .top-strip {
            position: absolute;
            top: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-red), var(--primary-blue), var(--primary-red));
            z-index: 2;
        }

        .bottom-banner {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(208, 9, 4, 0.8);
            color: white;
            text-align: center;
            padding: 12px;
            font-size: 0.85rem;
            backdrop-filter: blur(5px);
            font-weight: 600;
            letter-spacing: 1px;
            z-index: 2;
        }
    </style>
</head>
<body>

    <div class="top-strip"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-header">
                <img src="{{ asset('images/misc/LogoFront.png') }}" alt="logo" class="brand-logo">
                <div class="brand-name">DSMS POS</div>
                <div class="brand-tagline">Management System POS</div>
            </div>

            <form method="post" action="{{ route('action-login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email / Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">Password</label>
                        <a href="{{ route('forgotpassword') }}" class="text-decoration-none" style="font-size: 0.8rem; color: var(--secondary-blue);">Lupa Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="········" required>
                    </div>
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label ms-2" for="remember" style="font-size: 0.9rem;">Ingat Saya</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">MASUK</button>
                    <a href="{{ route('daftar') }}" class="btn btn-register">DAFTAR AKUN BARU</a>
                </div>
            </form>

            <!-- Interactive Demo Accounts Section -->
            <div class="mt-4 pt-3 border-top border-light-subtle">
                <div class="text-center mb-2">
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        <i class="bi bi-stars me-1"></i> COBA AKUN DEMO PREMIUM
                    </span>
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 font-weight-bold btn-demo-auto px-1" data-email="demoresto@pos.dstechsmart.com" data-pass="12345678" style="font-size: 0.7rem; border-radius: 8px;">
                            <i class="bi bi-cup-hot d-block mb-1"></i> Resto / FnB
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-success btn-sm w-100 py-2 font-weight-bold btn-demo-auto px-1" data-email="demoretail@pos.dstechsmart.com" data-pass="12345678" style="font-size: 0.7rem; border-radius: 8px;">
                            <i class="bi bi-cart d-block mb-1"></i> Retail / Shop
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-warning btn-sm w-100 py-2 font-weight-bold btn-demo-auto px-1" data-email="gor.service@gmail.com" data-pass="12345678" style="font-size: 0.65rem; border-radius: 8px;">
                            <i class="bi bi-controller d-block mb-1"></i> Hiburan/Rental/Hotel
                        </button>
                    </div>
                </div>
            </div>

            <div class="footer-links">
                <p class="mb-0 text-muted">Butuh bantuan? <a href="#">Hubungi Support</a></p>
            </div>
        </div>
    </div>

    <div class="bottom-banner">
        THE BEST SMART SYSTEM MANAGEMENT PROGRAM APPLICATION - ONE APPLICATION FOR ALL YOUR BUSINESS
    </div>

    @include('sweetalert::alert')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-demo-auto').on('click', function() {
                var email = $(this).data('email');
                var pass = $(this).data('pass');
                
                // Fill inputs
                $('input[name="email"]').val(email);
                $('input[name="password"]').val(pass);
                
                // Show automatic submission indicator on the login button
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