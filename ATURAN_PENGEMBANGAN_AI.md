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

## 4. Dokumentasi Progres & Pelaporan (WAJIB & KETAT)
*   **Laporan Progres sebagai Kompas:** File `LAPORAN_PROGRES.md` adalah satu-satunya sumber kebenaran untuk status pekerjaan.
*   **Prosedur Wajib Sebelum Bekerja:**
    1.  **Baca:** Cek `LAPORAN_PROGRES.md` untuk memahami progres terakhir.
    2.  **Catat:** Tuliskan daftar rincian pekerjaan (step-by-step) yang AKAN dilakukan ke dalam laporan sebelum menyentuh kode satu baris pun.
*   **Prosedur Selama & Setelah Bekerja:**
    1.  **Update Segera:** Segera setelah satu poin pekerjaan selesai, beri tanda centang atau update statusnya di laporan.
    2.  **Jangan Lewatkan:** Dilarang keras melakukan pekerjaan tanpa mencatatnya terlebih dahulu di laporan, meskipun pekerjaan tersebut kecil.
*   **Dilarang Menghapus:** Jangan pernah menghapus riwayat perbaikan yang sudah dilakukan sebelumnya. Riwayat harus tetap ada sebagai referensi permanen.
*   **Antrean Pesan:** Jika ada permintaan baru saat pekerjaan lama belum selesai, catat sebagai antrean pekerjaan ke depan di laporan, jangan langsung dikerjakan sebelum pekerjaan saat ini tuntas.

## 5. Konfigurasi Model AI
*   **Default Version:** Tetap gunakan konfigurasi default (Versi 2.5 atau yang sudah ditentukan User). Sediakan opsi dari versi 1.5 sampai 3.5, namun jangan ganti default utama tanpa persetujuan.

---

> [!IMPORTANT]
> **PESAN UNTUK AGEN AI SELANJUTNYA:**
> Sebelum memulai tugas, baca file ini dan `LAPORAN_PROGRES.md`. Jangan mengulangi pekerjaan yang sudah selesai dan pastikan prefix database selalu konsisten.
