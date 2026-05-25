<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSMS POS - Solusi Kasir Pintar & Terintegrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #d00904;
            --primary-blue: #0056b3;
            --secondary-blue: #004494;
            --accent-red: #ff3333;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(0, 0, 0, 0.05);
            --text-main: #1e293b;
            --text-dim: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--white);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        /* Animated Background */
        .bg-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #ffffff 0%, #f8fafc 100%);
            overflow: hidden;
        }

        .glow-1 {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(0, 86, 179, 0.05) 0%, transparent 70%);
            top: -300px;
            right: -200px;
            filter: blur(100px);
            z-index: -1;
        }

        .glow-2 {
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(208, 9, 4, 0.03) 0%, transparent 70%);
            bottom: -200px;
            left: -200px;
            filter: blur(100px);
            z-index: -1;
        }

        @keyframes move {
            from { transform: translate(0, 0); }
            to { transform: translate(100px, 100px); }
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0; width: 100%;
            padding: 1.5rem 5%;
            display: flex; justify-content: space-between; align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo img {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            margin-left: 2.5rem;
            font-weight: 400;
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: var(--accent-red);
        }

        .btn-login {
            background: #f1f5f9;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            color: var(--text-main) !important;
        }

        /* Hero Section */
        .hero {
            padding: 10rem 10% 5rem;
            display: flex;
            align-items: center;
            gap: 4rem;
            min-height: 90vh;
        }

        .hero-content {
            flex: 1;
        }

        .badge {
            background: rgba(208, 9, 4, 0.1);
            color: var(--accent-red);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(208, 9, 4, 0.2);
        }

        h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        h1 span {
            display: block;
            color: var(--dark);
        }

        .hero-p {
            font-size: 1.1rem;
            color: var(--text-dim);
            margin-bottom: 2.5rem;
            max-width: 500px;
        }

        .cta-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            padding: 1rem 2rem;
            border-radius: 12px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(208, 9, 4, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(208, 9, 4, 0.3);
        }

        .btn-outline {
            background: #f8fafc;
            padding: 1rem 2rem;
            border-radius: 12px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .btn-outline:hover {
            background: #e2e8f0;
        }

        .btn-whatsapp {
            background: #25D366;
            padding: 1rem 2rem;
            border-radius: 12px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.2);
        }

        .btn-whatsapp:hover {
            transform: translateY(-3px);
            filter: brightness(1.1);
        }

        .hero-image {
            flex: 1;
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--glass-border);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: 0.5s;
        }

        .hero-image:hover img {
            transform: perspective(1000px) rotateY(0) rotateX(0);
        }

        /* Features */
        .features {
            padding: 5rem 10%;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-p {
            color: var(--text-dim);
            margin-bottom: 4rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #f8fafc;
            padding: 3rem 2rem;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
            text-align: left;
        }

        .feature-card:hover {
            background: #ffffff;
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border-color: var(--secondary-blue);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(0, 86, 179, 0.1);
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: var(--primary-blue);
            margin-bottom: 1.5rem;
        }

        /* Pricing Section */
        .pricing {
            padding: 8rem 10%;
        }

        .pricing-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .glass-card {
            background: var(--white);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tab-btn {
            background: #f1f5f9;
            border: 1px solid transparent;
            color: var(--text-dim);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .tab-btn.active {
            background: var(--primary-red);
            color: white;
            border-color: var(--primary-red);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .pricing-card {
            background: #f8fafc;
            padding: 3rem 2rem;
            border-radius: 30px;
            border: 1px solid var(--glass-border);
            position: relative;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pricing-card:hover {
            border-color: var(--primary-red);
            background: #ffffff;
            transform: translateY(-5px);
        }

        .type-badge {
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--secondary);
            color: white;
            padding: 5px 40px;
            transform: rotate(45deg);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-blue);
        }

        .plan-price {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .plan-price span {
            font-size: 0.9rem;
            color: var(--text-dim);
            font-weight: 400;
        }

        .plan-desc {
            font-size: 0.9rem;
            color: var(--text-dim);
            margin-bottom: 2rem;
            min-height: 80px;
        }

        .plan-desc ul {
            list-style: none;
            padding: 0;
        }

        .plan-desc li {
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .plan-desc li i {
            color: var(--primary-blue);
            font-size: 0.8rem;
        }

        .btn-buy {
            display: block;
            text-align: center;
            padding: 1.2rem;
            border-radius: 15px;
            background: linear-gradient(135deg, var(--primary-red), var(--primary-blue));
            text-decoration: none;
            color: white;
            font-weight: 700;
            transition: 0.3s;
            margin-top: auto;
        }

        .btn-buy:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
        }

        /* Footer */
        footer {
            background: #f8fafc;
            padding: 5rem 5% 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-links h4 {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: var(--text-dim);
            text-decoration: none;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .copy {
            text-align: center;
            color: var(--text-dim);
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            h1 { font-size: 3rem; }
            .hero { flex-direction: column; text-align: center; padding-top: 8rem; }
            .hero-p { margin: 0 auto 2.5rem; }
            .cta-group { justify-content: center; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 480px) {
            h1 { font-size: 2.5rem; }
            .nav-links { display: none; }
            .footer-grid { grid-template-columns: 1fr; }
        }

        /* Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: 1s all ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="bg-glow">
        <div class="glow-1"></div>
        <div class="glow-2"></div>
    </div>

    <nav>
        <a href="{{ url('/') }}" class="logo" style="font-weight: 800; font-size: 1.5rem; color: #020617; gap: 10px;">
            <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo">
            <span>DSMS POS</span>
        </a>
        <div class="nav-links">
            <a href="#features">Fitur</a>
            <a href="#pricing">Harga</a>
            @auth
                @if(auth()->user()->RecordOwnerID == "999999")
                    <a href="{{ url('/dashboardadmin') }}" class="btn-login">Admin Panel</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-login">Masuk Aplikasi</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="badge">Edisi Terbaru 2026 • AI Powered</div>
            <h1>
                <span>Kelola Bisnis</span>
                <span>Lebih Cerdas</span>
            </h1>
            <p class="hero-p">
                DSMS POS adalah sistem kasir generasi masa depan yang dirancang untuk mempercepat transaksi, mengelola stok otomatis, dan menganalisis keuntungan Anda secara real-time.
            </p>
            <div class="cta-group">
                @auth
                    @if(auth()->user()->RecordOwnerID == "999999")
                        <a href="{{ url('/dashboardadmin') }}" class="btn-primary">Buka Admin Panel</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Buka Dashboard</a>
                    @endif
                @else
                    <a href="#pricing" class="btn-primary">Mulai Sekarang</a>
                    <a href="{{ route('login') }}" class="btn-outline">Masuk Aplikasi</a>
                @endauth
                <a href="https://wa.me/6282258493130" class="btn-whatsapp"><i class="fab fa-whatsapp"></i> Demo Gratis</a>
            </div>
        </div>
        <div class="hero-image" style="position: relative;">
            <img id="heroSliderImg" src="{{ asset('images/misc/slide_retail.png') }}" alt="DSMS POS Interface">
            <div id="heroSliderCaption" style="position: absolute; bottom: 20px; left: 20px; right: 20px; background: rgba(0, 0, 0, 0.65); padding: 20px 25px; border-radius: 15px; backdrop-filter: blur(8px); border-left: 6px solid var(--primary-blue); box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: opacity 0.5s;">
                <h3 id="heroSliderTitle" style="color: white; margin-bottom: 5px; font-weight: 700; font-size: 1.4rem;">Grosir & Supermarket</h3>
                <p id="heroSliderSubtitle" style="color: rgba(255,255,255,0.9); font-size: 0.95rem; margin-bottom: 0;">Kelola puluhan ribu stok barang, barcode barcode scanner, dan transaksi kasir secara cepat dan akurat.</p>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const heroSlides = [
                        { image: "{{ asset('images/misc/slide_retail.png') }}", title: "Grosir & Supermarket", subtitle: "Kelola puluhan ribu stok barang, barcode barcode scanner, dan transaksi kasir secara cepat dan akurat." },
                        { image: "{{ asset('images/misc/slide_kelontong.png') }}", title: "Toko Kelontong", subtitle: "Manajemen warung dan toko kelontong modern. Catat penjualan harian dengan mudah." },
                        { image: "{{ asset('images/misc/slide_bengkel.png') }}", title: "Bengkel Otomotif", subtitle: "Sistem POS cerdas untuk mencatat jasa service, sparepart, dan antrian pelanggan secara real-time." },
                        { image: "{{ asset('images/misc/slide_apotik.png') }}", title: "Apotik & Farmasi", subtitle: "Pencatatan obat, cek tanggal kedaluwarsa (expired), dan kontrol stok farmasi yang detail dan rapi." },
                        { image: "{{ asset('images/misc/slide_fnb.png') }}", title: "Rumah Makan & Restoran", subtitle: "Solusi tepat untuk manajemen pesanan, pengaturan meja (table management), dan split bill." },
                        { image: "{{ asset('images/misc/slide_caffe.png') }}", title: "Caffe & Coffee Shop", subtitle: "POS dinamis untuk coffee shop. Catat resep, varian minuman, dan sistem takeaway / dine-in." },
                        { image: "{{ asset('images/misc/slide_hotel.png') }}", title: "Hotel & Penginapan", subtitle: "Kemudahan manajemen check-in/check-out, tagihan layanan kamar, dan pencatatan fasilitas tamu." },
                        { image: "{{ asset('images/misc/slide_hiburan.png') }}", title: "Lapangan Olahraga", subtitle: "Sistem booking jam otomatis untuk lapangan Badminton, Padel, Basket, dan Futsal." },
                        { image: "{{ asset('images/misc/slide_billiar.png') }}", title: "Arena Billiar", subtitle: "Menghitung durasi penyewaan meja biliar dengan tarif otomatis berdasarkan menit/jam (billing system)." },
                        { image: "{{ asset('images/misc/slide_tiket.png') }}", title: "GYM & Fitness", subtitle: "Manajemen membership, paket bulanan, kunjungan harian, dan penjualan suplemen dalam satu kasir." },
                        { image: "{{ asset('images/misc/slide_pool.png') }}", title: "Kolam Renang", subtitle: "POS khusus ticketing kolam renang, kontrol akses pengunjung, dan pencatatan penyewaan loker/ban." },
                        { image: "{{ asset('images/misc/slide_wahana.png') }}", title: "Wahana Hiburan", subtitle: "Cetak tiket masuk wahana, scan barcode gelang, dan laporan jumlah pengunjung yang akurat." },
                        { image: "{{ asset('images/misc/slide_futsal.png') }}", title: "Lapangan Futsal", subtitle: "Booking lapangan futsal dengan mudah, pencatatan otomatis, dan manajemen jadwal yang anti-bentrok." },
                        { image: "{{ asset('images/misc/slide_basket.png') }}", title: "Lapangan Basket", subtitle: "Sistem penyewaan lapangan basket terpadu, kontrol lampu otomatis sesuai durasi sewa." },
                        { image: "{{ asset('images/misc/slide_iot.png') }}", title: "Integrasi IoT & Smart Relay", subtitle: "Terhubung langsung ke lampu meja billiar atau lapangan. Lampu menyala saat billing aktif, dan mati otomatis saat waktu habis." },
                        { image: "{{ asset('images/misc/slide_kelebihan.png') }}", title: "Kelebihan Produk Kami", subtitle: "100% Cloud Base, Laporan Real-time, Mendukung Multi Cabang, dan Keamanan Data Tingkat Tinggi dengan dukungan AI." }
                    ];
                    let currentSlide = 0;
                    const heroImgEl = document.getElementById('heroSliderImg');
                    const heroCaptionEl = document.getElementById('heroSliderCaption');
                    const heroTitleEl = document.getElementById('heroSliderTitle');
                    const heroSubtitleEl = document.getElementById('heroSliderSubtitle');
                    
                    if (heroImgEl && heroCaptionEl) {
                        setInterval(() => {
                            heroImgEl.style.opacity = 0.2; // fade out image
                            heroCaptionEl.style.opacity = 0; // fade out text
                            setTimeout(() => {
                                currentSlide = (currentSlide + 1) % heroSlides.length;
                                heroImgEl.src = heroSlides[currentSlide].image;
                                heroTitleEl.innerText = heroSlides[currentSlide].title;
                                heroSubtitleEl.innerText = heroSlides[currentSlide].subtitle;
                                heroImgEl.style.opacity = 1; // fade in image
                                heroCaptionEl.style.opacity = 1; // fade in text
                            }, 500);
                        }, 4000);
                    }
                });
            </script>
        </div>
    </section>

    <section class="features" id="features">
        <h2 class="section-title reveal">Fitur Unggulan</h2>
        <p class="section-p reveal">Semua yang Anda butuhkan untuk mengembangkan bisnis ada di sini.</p>
        
        <div class="feature-grid">
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                <h3>Transaksi Kilat</h3>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 1rem;">Proses pembayaran kurang dari 3 detik dengan berbagai metode: QRIS, Tunai, & Kartu.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="fas fa-boxes-stacked"></i></div>
                <h3>Manajemen Stok</h3>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 1rem;">Notifikasi otomatis saat stok menipis dan integrasi supplier yang sangat mudah.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Laporan Real-Time</h3>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 1rem;">Pantau omzet, laba rugi, dan produk terlaris langsung dari smartphone Anda kapan saja.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Loyalty Program</h3>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 1rem;">Kelola data pelanggan dan buat program promo khusus untuk meningkatkan repeat order.</p>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <div style="text-align: center;">
            <h2 class="section-title reveal">Pilih Paket Bisnis Anda</h2>
            <p class="section-p reveal">Paket berlangganan yang dapat disesuaikan dengan skala usaha Anda.</p>
        </div>

        <div class="pricing-tabs reveal">
            <button class="tab-btn active" onclick="filterPackages('All')">Semua</button>
            <button class="tab-btn" onclick="filterPackages('Retail')">Retail</button>
            <button class="tab-btn" onclick="filterPackages('FnB')">FnB</button>
            <button class="tab-btn" onclick="filterPackages('Hiburan')">Hiburan</button>
        </div>

        <div class="pricing-grid" id="package-grid">
            @foreach($subscriptionheader as $item)
            <div class="pricing-card reveal package-item" data-type="{{ $item->JenisUsaha }}">
                <div class="type-badge">{{ $item->JenisUsaha }}</div>
                <div class="plan-name">{{ $item->NamaSubscription }}</div>
                <div class="plan-price">
                    Rp {{ number_format($item->Harga - $item->Potongan, 0, ',', '.') }}
                    <span>/ {{ $item->LamaSubsription }} Bln</span>
                </div>
                <div class="plan-desc">
                    {!! $item->DeskripsiSubscription !!}
                </div>
                <a href="{{ route('daftar') }}?package={{ $item->NoTransaksi }}&type={{ $item->JenisUsaha }}" class="btn-buy">Beli Paket</a>
            </div>
            @endforeach
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-info">
                <div class="logo footer-logo">
                    <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo" style="height: 35px;">
                </div>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Solusi ERP dan Point of Sale terpercaya untuk UMKM hingga Enterprise di Indonesia. Membangun masa depan digital bisnis Anda.</p>
            </div>
            <div class="footer-links">
                <h4>Produk</h4>
                <ul>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#pricing">Harga</a></li>
                    <li><a href="{{ route('login') }}">Masuk Aplikasi</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Perusahaan</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                    <li><a href="#">Karir</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>
        <div class="copy">
            &copy; 2026 DSTECH SMART. All rights reserved.
        </div>
    </footer>

    <script>
        // Reveal animation on scroll
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        reveal();

        // Package filtering
        function filterPackages(type) {
            // Update buttons
            const btns = document.querySelectorAll('.tab-btn');
            btns.forEach(btn => {
                if(btn.innerText === type || (type === 'All' && btn.innerText === 'Semua')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Filter items
            const items = document.querySelectorAll('.package-item');
            items.forEach(item => {
                if (type === 'All' || item.getAttribute('data-type') === type) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Re-trigger reveal to fix visibility
            reveal();
        }
    </script>

    <style>
        .chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: 0.3s;
        }
        .chatbot-btn:hover {
            transform: scale(1.1);
        }
        .chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            max-width: 90vw;
            height: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        .chatbot-header {
            background: var(--primary-blue);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chatbot-header h5 {
            margin: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chatbot-close {
            cursor: pointer;
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
        }
        .chatbot-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .chat-msg {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .msg-bot {
            background: white;
            color: #333;
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 2px;
            align-self: flex-start;
        }
        .msg-user {
            background: var(--primary-blue);
            color: white;
            border-bottom-right-radius: 2px;
            align-self: flex-end;
        }
        .chatbot-input {
            padding: 15px;
            background: white;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 10px;
        }
        .chatbot-input input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 25px;
            outline: none;
            font-size: 0.9rem;
        }
        .chatbot-input input:focus {
            border-color: var(--primary-blue);
        }
        .chatbot-input button {
            background: var(--primary-blue);
            color: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }
        .chatbot-input button:hover {
            background: #2563eb;
        }
        .typing-indicator {
            display: none;
            align-self: flex-start;
            background: transparent;
            padding: 5px 10px;
        }
        .typing-indicator span {
            height: 6px;
            width: 6px;
            background: #94a3b8;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>

    <div class="chatbot-btn" onclick="toggleChatbot()">
        <i class="fas fa-comment-dots"></i>
    </div>

    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <h5><i class="fas fa-robot"></i> DSMS AI Assistant</h5>
            <button class="chatbot-close" onclick="toggleChatbot()"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chat-msg msg-bot">
                Halo! Saya AI Assistant dari DSMS POS. Ada yang bisa saya bantu terkait produk atau fitur kasir kami?
            </div>
            <div class="typing-indicator" id="typingIndicator">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatInput" placeholder="Ketik pesan..." onkeypress="handleChatKey(event)">
            <button onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script>
        function toggleChatbot() {
            const chatWin = document.getElementById('chatbotWindow');
            chatWin.style.display = chatWin.style.display === 'flex' ? 'none' : 'flex';
        }

        function handleChatKey(e) {
            if (e.key === 'Enter') sendChatMessage();
        }

        async function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            input.value = '';

            // Show typing indicator
            const typing = document.getElementById('typingIndicator');
            const messagesContainer = document.getElementById('chatbotMessages');
            messagesContainer.appendChild(typing);
            typing.style.display = 'block';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                typing.style.display = 'none';
                
                if (data.success) {
                    addMessage(data.reply, 'bot');
                } else {
                    addMessage('Maaf, ada gangguan sistem saat ini.', 'bot');
                }
            } catch (error) {
                typing.style.display = 'none';
                addMessage('Koneksi terputus. Silakan coba lagi.', 'bot');
            }
        }

        function addMessage(text, sender) {
            const container = document.getElementById('chatbotMessages');
            const typing = document.getElementById('typingIndicator');
            const div = document.createElement('div');
            div.className = `chat-msg msg-${sender}`;
            div.innerHTML = text;
            
            // Insert before typing indicator
            container.insertBefore(div, typing);
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>
