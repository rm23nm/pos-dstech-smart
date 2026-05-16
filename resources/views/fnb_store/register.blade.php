<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan - {{ $company->NamaPartner }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff4757;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/images/fnb_login_bg.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0;
            overflow: hidden;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        h2 { font-weight: 700; margin-bottom: 10px; letter-spacing: -0.5px; }
        p { opacity: 0.8; font-size: 0.95rem; margin-bottom: 30px; }

        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 12px 20px;
            color: white;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.2);
            border-color: var(--primary-color);
            box-shadow: none;
            color: white;
        }

        .form-control::placeholder { color: rgba(255,255,255,0.5); }

        .btn-premium {
            background: var(--primary-color);
            border: none;
            border-radius: 15px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-premium:hover {
            background: #ff6b81;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 71, 87, 0.3);
        }
    </style>
</head>
<body>

    <div class="glass-card">
        <div class="logo-container">
            @if($company->Logo)
                <img src="data:image/png;base64,{{ base64_encode($company->Logo) }}" alt="Logo">
            @else
                <i class="fas fa-utensils fa-2x" style="color: var(--primary-color)"></i>
            @endif
        </div>

        <h2>Lengkapi Data</h2>
        <p>Silakan isi nama Anda untuk mempermudah pelayanan kami.</p>

        @if(session('info'))
            <div class="alert alert-info py-2 small" style="background: rgba(52, 152, 219, 0.2); border: none; color: #74b9ff;">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ $id ? route('fnb-store.register.post', ['id' => $id]) : route('fnb-store.register.post.custom') }}" method="POST">
            @csrf
            <div class="text-start">
                <label class="small fw-bold mb-1 opacity-75">Nomor HP / Email</label>
                <input type="text" name="identifier" class="form-control" value="{{ $identifier }}" readonly>
            </div>
            
            <div class="text-start">
                <label class="small fw-bold mb-1 opacity-75">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required autofocus>
            </div>
            
            <button type="submit" class="btn btn-premium w-100">DAFTAR & MULAI PESAN</button>
        </form>
    </div>

</body>
</html>
