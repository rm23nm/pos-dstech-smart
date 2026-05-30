# SOP (Standar Operasional Prosedur) AI Agent - Database & Deployment
**DOKUMEN INI WAJIB DIBACA DAN DIPATUHI OLEH SEMUA AI AGENT YANG BEKERJA DI PROJECT INI.**

## 1. Aturan Ketat Perubahan Database (Zero Manual DB Changes)
Agar database lokal dan server live selalu 100% sinkron tanpa ada kendala ketika melakukan deployment:
- AI **DILARANG KERAS** membuat, mengedit, atau menghapus tabel maupun kolom secara langsung menggunakan koneksi SQL murni (MySQL/phpMyAdmin/Navicat) di lokal.
- Semua perubahan database **WAJIB** menggunakan **Laravel Migrations**.
- Cara membuat tabel/kolom baru:
  1. Jalankan `php artisan make:migration nama_perubahan_tabel_xyz`
  2. Tulis kode skema perubahan di dalam file migration tersebut (`up()` dan `down()`).
  3. Jalankan `php artisan migrate` di lokal.

## 2. Aturan Push & Deploy ke Live
- Proses memindahkan kode dari lokal ke live **WAJIB** menggunakan script deployer resmi project ini, atau secara otomatis menjalankan urutan berikut di live:
  1. `git pull`
  2. **`php artisan migrate --force`** *(Sangat Penting! Ini mengeksekusi migration baru ke live)*
  3. `php artisan optimize:clear` (atau clear cache, route, view)
- Dengan adanya baris `php artisan migrate --force` pada saat deploy, setiap penambahan fitur yang merubah database lokal akan otomatis ter-apply di database live dengan struktur yang sama persis 100%.

## 3. Penyesuaian Data Dummy (Seeder)
- Jika ada penyesuaian data paten (seperti Role "SuperAdmin", settingan default, dsb), wajib diletakkan di **Laravel Seeder**.
- AI dilarang menginput data sistem krusial secara manual via SQL, gunakan Seeder agar bisa di-run di live.

## 4. Pelaporan
- Sebelum AI menyelesaikan pekerjaan (turn off), AI wajib mencatat hal yang dikerjakan di `progress_report.md` (di scratch directory atau root jika ada).
- AI harus memastikan tidak meninggalkan pekerjaan "setengah jalan" pada script deploy.

**Jika AI melanggar aturan ini, maka AI gagal memenuhi <RULE[user_global]> dari User utama.**
