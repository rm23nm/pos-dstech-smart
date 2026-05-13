# 📜 PROTOKOL KETAT PENGEMBANGAN APLIKASI (WAJIB BAGI SEMUA AGEN AI)

Dokumen ini berisi aturan fundamental yang **HARUS** dipatuhi oleh setiap Agen AI yang bekerja pada repository ini. Kegagalan mematuhi aturan ini dapat merusak integritas sistem IoT (ESP32) dan sinkronisasi database multi-aplikasi.

---

## 1. Keamanan Database & Arsitektur Multi-App
*   **Prefix Wajib:** Setiap penambahan tabel atau kolom baru **WAJIB** menggunakan prefix aplikasi yang sesuai:
    *   `mosque_` : Aplikasi Masjidkusmart.com
    *   `wa_` : Aplikasi Smartpro
    *   `access_` : Aplikasi Smart Gate
    *   `dstech_` : Aplikasi Dstechsmart.com
*   **Dilarang Keras:** Memodifikasi kolom atau data milik prefix lain tanpa instruksi spesifik.
*   **Stabilitas Database:** Jangan mengubah struktur database Live secara langsung. Selalu lakukan di Lokal terlebih dahulu.

## 2. Proteksi Sistem IoT (ESP32 & Kontrol Lampu)
*   **Area Terlarang:** Jangan mengubah file berikut kecuali untuk perbaikan fitur IoT:
    *   `app/Http/Controllers/TitikLampuController.php`
    *   `app/Http/Controllers/MasterControllerController.php`
    *   `app/Http/Controllers/KelompokLampuController.php`
*   **API Integrity:** Dilarang mengubah rute di `routes/api.php` khususnya:
    *   `/api/checkCommand`
    *   `/api/releaseCommand`
*   **IoT Logic:** Perubahan pada UI/Frontend tidak boleh memutus koneksi API yang dipanggil oleh perangkat ESP32.

## 3. Prosedur Kerja & Backup
*   **Analisis Sebelum Edit:** Agen AI harus tahu penyebab masalah secara pasti sebelum menyentuh kode.
*   **Prosedur Backup:** Sebelum mengedit file, buat salinan cadangan di folder `_backup_perbaikan/`. Jika setelah edit sistem berjalan normal selama beberapa waktu, file backup baru boleh dihapus.
*   **Lokal Sebelum Live:** Perbaikan **WAJIB** dilakukan dan diuji di lingkungan lokal (`http://127.0.0.1:8000`) sebelum diunggah ke server Live.

## 4. Dokumentasi Progres (Source of Truth)
*   **Laporan Progres:** Gunakan file `LAPORAN_PROGRES.md` sebagai catatan utama.
*   **Urutan Kerja:**
    1.  Cek `LAPORAN_PROGRES.md` untuk melihat apa yang sudah dilakukan agen sebelumnya.
    2.  Tuliskan rencana langkah-langkah kerja (step-by-step) sebelum memulai.
    3.  Update status menjadi "SELESAI" setelah berhasil.
*   **Dilarang Menghapus:** Jangan pernah menghapus riwayat perbaikan yang sudah dilakukan sebelumnya. Hanya tambahkan list baru di bawahnya.

## 5. Konfigurasi Model AI
*   **Default Version:** Tetap gunakan konfigurasi default (Versi 2.5 atau yang sudah ditentukan User). Jangan mengganti versi model utama kecuali diminta untuk pengujian spesifik.

---

> [!IMPORTANT]
> **PESAN UNTUK AGEN AI SELANJUTNYA:**
> Sebelum memulai tugas, baca file ini dan `LAPORAN_PROGRES.md`. Jangan mengulangi pekerjaan yang sudah selesai dan pastikan prefix database selalu konsisten.
