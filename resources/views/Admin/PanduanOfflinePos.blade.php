@extends('parts.header')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Panduan Offline POS</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book"></i>
                        Panduan Instalasi & Keamanan Mode Offline (Native MySQL)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Latar Belakang</h5>
                        <p>Fitur Offline POS Native MySQL dirancang agar Kasir tetap dapat melakukan transaksi dengan cepat meskipun tanpa internet. Transaksi akan disimpan di database MySQL lokal komputer kasir (XAMPP), lalu disinkronkan secara otomatis ke server Live setiap 5 menit di latar belakang.</p>
                    </div>

                    <h4>1. Panduan Instalasi di Komputer Kasir (Klien)</h4>
                    <p>Untuk mengaktifkan sinkronisasi otomatis, ikuti langkah-langkah di bawah ini pada komputer kasir:</p>
                    <ol>
                        <li><strong>Pengaturan Database Live:</strong> Buka file <code>.env</code> di dalam folder POS lokal, dan tambahkan parameter berikut di baris paling bawah:
                            <pre class="bg-dark text-white p-2 rounded mt-2">
# LIVE DB CONFIGURATION (FOR OFFLINE POS SYNC)
DB_LIVE_HOST=157.66.34.199
DB_LIVE_PORT=3306
DB_LIVE_DATABASE=smartpro
DB_LIVE_USERNAME=username_live_anda
DB_LIVE_PASSWORD=password_live_anda</pre>
                            <em>Pastikan IP komputer lokal diizinkan (Remote MySQL) di cPanel/VPS server Live.</em>
                        </li>
                        <li class="mt-2"><strong>Update Struktur Database Lokal:</strong> Buka <em>Command Prompt (CMD)</em>, masuk ke folder aplikasi POS, dan jalankan perintah migrasi berikut untuk menambahkan penanda sinkronisasi:
                            <pre class="bg-dark text-white p-2 rounded mt-2">C:\xampp\php\php.exe artisan migrate</pre>
                        </li>
                        <li class="mt-2"><strong>Aktivasi Sinkronisasi Otomatis:</strong> Di dalam folder POS, cari file bernama <code>install_sync_scheduler.bat</code>. <strong>Klik Kanan &gt; Run as Administrator</strong>. Script ini akan otomatis memasang <em>Windows Task Scheduler</em> agar proses sinkronisasi berjalan senyap setiap menit di latar belakang.</li>
                    </ol>

                    <hr>

                    <h4>2. Aspek Keamanan (Security)</h4>
                    <ul>
                        <li><strong>Koneksi Langsung Tanpa Middleware Terekspos:</strong> Tidak ada API endpoint terbuka yang bisa diserang dari luar. Sinkronisasi murni dilakukan satu arah dari Local ke Live (Push).</li>
                        <li><strong>Isolasi Tenant:</strong> Saat data disinkronkan ke live, sistem tetap mempertahankan <code>RecordOwnerID</code> (KodePartner) sehingga data tidak bercampur dengan perusahaan lain.</li>
                        <li><strong>Anti-Duplikasi (Idempotent):</strong> Script sinkronisasi dirancang menggunakan <code>->exists()</code>. Jika karena suatu hal internet putus di tengah jalan, sinkronisasi ulang tidak akan mencatat data ganda di Live.</li>
                        <li><strong>Transaksi (Rollback):</strong> Setiap sinkronisasi tiket/pesanan dibungkus dengan <em>Database Transaction</em>. Jika terjadi gagal kirim di tengah proses (misal header masuk tapi detail gagal), sistem akan membatalkan seluruhnya sehingga data tetap konsisten.</li>
                    </ul>

                    <hr>

                    <h4>3. Lisensi & Legalitas</h4>
                    <ul>
                        <li><strong>Batas Hak Cipta:</strong> Fitur Native Sync MySQL ini adalah hak milik DSTech Smart. Dilarang mendistribusikan script <code>pos:sync</code> kepada pihak ketiga tanpa izin resmi dari DSTech.</li>
                        <li><strong>Pembatasan Penggunaan:</strong> Mode offline ini dilisensikan hanya untuk <em>Tenant</em> (Perusahaan) yang berlangganan paket Premium/Retail dengan status aktif. Server pusat memiliki wewenang untuk memblokir Remote MySQL IP komputer klien jika masa berlangganan telah habis.</li>
                        <li><strong>Data Ownership:</strong> Data yang ada di MySQL lokal XAMPP sepenuhnya milik klien (Tenant). Namun, jaminan integritas data dari kerusakan <em>hardware</em> lokal adalah tanggung jawab klien. DSTech hanya menjamin keamanan data yang telah sukses tersinkronisasi ke server Live.</li>
                    </ul>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
