<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Login | DSMS POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            font-family: 'Poppins', sans-serif;
            background-color: var(--primary-red);
            overflow: hidden;
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(208, 9, 4, 0.85) 0%, rgba(0, 86, 179, 0.85) 100%);
            position: relative;
        }

        /* Decorative Background Elements */
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
            opacity: 0.7;
            z-index: 0;
            filter: saturate(1.2);
        }

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
            background: linear-gradient(45deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(29, 140, 248, 0.4);
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
</body>
</html>