<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $company->NamaPartner ?? 'FnB Store' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff4757;
            --secondary-color: #2f3542;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            overflow: hidden;
        }

        .bg-image {
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset("images/fnb_login_bg.png") }}');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: blur(2px);
            position: absolute;
            width: 100%;
            z-index: -1;
            transform: scale(1.1);
        }

        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            color: white;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .company-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            padding: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .company-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .btn-premium {
            background: linear-gradient(135deg, #ff4757, #ff6b81);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: white;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
            color: white;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #ff4757;
            color: white;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-text {
            font-size: 0.85rem;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-text a {
            color: #ff4757;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .glass-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    
    <div class="login-container">
        <div class="glass-card">
            <div class="company-logo">
                @if($company && $company->icon)
                    <img src="{{ $company->icon }}" alt="Logo">
                @else
                    <i class="fas fa-utensils fa-2x text-danger"></i>
                @endif
            </div>
            
            <h3 class="fw-bold mb-1">Selamat Datang</h3>
            <p class="mb-4 opacity-75">Silakan login untuk mulai memesan</p>

            @if(session('error'))
                <div class="alert alert-danger border-0 bg-danger text-white small py-2 mb-3" style="border-radius: 10px;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ $id ? route('fnb-store.login.post', ['id' => $id]) : route('fnb-store.login.post.custom') }}" method="POST">
                @csrf
                <div class="text-start">
                    <label class="small fw-bold mb-1 opacity-75">Nomor HP atau Email</label>
                    <input type="text" name="identifier" class="form-control" placeholder="Contoh: 08123456789" required>
                </div>
                
                <button type="submit" class="btn btn-premium w-100">MASUK KE MENU</button>
            </form>

            <div class="footer-text">
                Belum terdaftar? <a href="#">Hubungi Kasir</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
