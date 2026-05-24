# Panduan Instalasi Smart Gate Controller (ESP32)

Dokumen ini berisi panduan lengkap untuk merakit dan menginstal Smart Gate Controller untuk aplikasi Point of Sales.

## 1. Daftar Perangkat yang Dibutuhkan

Berikut adalah komponen yang harus disiapkan:

1.  **Modul Mikrokontroler:** **ESP32 S3 DevkitC 1 44PIN** (Sangat penting: Harus versi S3 dengan 44 Pin agar pas dengan Shield).
2.  **Expansion Board:** **Shield ESP32-S3 IO Board DevkitC 1** (Papan dengan built-in RS485, ADS1115, Relay, & MicroSD).
3.  **Alat Pembaca (Scanner):** QR Code & RFID Reader 13.56Mhz (Mendukung output **Wiegand 26/34** atau **RS485**).
4.  **Adaptor Daya (Power Supply):** Adaptor 12V (Min. 2A / 3A). Cukup satu adaptor ini dicolokkan ke soket hitam DC pada Expansion Board. Papan akan menyuplai listrik untuk ESP32 dan Scanner sekaligus.
5.  *(Opsional)* **Modul Ethernet W5500:** Jika ingin pakai kabel LAN alih-alih WiFi bawaan ESP32-S3.
6.  **Box Panel Plastik** untuk mengemas seluruh rangkaian agar rapi.

## 2. Skema Pengkabelan (Wiring)

Pastikan semua perangkat dalam keadaan mati (tidak tercolok listrik) sebelum melakukan penyambungan kabel.

### A. Sambungan Scanner Wiegand ke Shield ESP32-S3
Scanner Wiegand umumnya memiliki 4 kabel utama (Merah, Hitam, Hijau, Putih).
*   **Kabel Merah (12V):** Hubungkan ke **Terminal VCC 12V** (Atau ambil arus paralel dari soket DC 12V input).
*   **Kabel Hitam (GND):** Hubungkan ke deretan pin **GND (Hitam)** pada shield.
*   **Kabel Hijau (Data 0 / D0):** Hubungkan ke pin kuning **22** pada shield.
*   **Kabel Putih (Data 1 / D1):** Hubungkan ke pin kuning **23** pada shield.
*(Jika Anda memilih menggunakan komunikasi RS485 dari scanner, hubungkan kabel RS485 A dan B ke terminal blok biru RS485 di shield, namun pastikan script Arduino diubah ke mode serial).*

### B. Sambungan Relay (Built-in Shield) ke Tripod Gate
Papan shield Anda sudah memiliki Relay. Anda akan menggunakan **Relay 1** (yang dikontrol oleh pin GPIO 48 ESP32-S3).
*   Sambungkan kabel dari terminal blok biru **COM** pada Relay 1 ke terminal **COM/GND** pada Mainboard Tripod Gate.
*   Sambungkan kabel dari terminal blok biru **NO** pada Relay 1 ke terminal **OPEN / PUSH** pada Mainboard Tripod Gate.

### D. Sambungan Modul LAN W5500 ke ESP32 (Lewati jika menggunakan WiFi)
Jika Anda menggunakan modul W5500 eksternal (menggunakan komunikasi SPI):
*   **VCC:** Hubungkan ke **3V3** ESP32.
*   **GND:** Hubungkan ke **GND** ESP32.
*   **CS (SCS):** Hubungkan ke **GPIO 5** ESP32.
*   **SCK (SCLK):** Hubungkan ke **GPIO 18** ESP32.
*   **MISO:** Hubungkan ke **GPIO 19** ESP32.
*   **MOSI:** Hubungkan ke **GPIO 23** ESP32 *(Catatan: Karena GPIO 23 dipakai oleh D1 Wiegand pada skrip default, jika Anda memakai W5500, pindahkan kabel Putih (D1) Wiegand ke pin **GPIO 21** dan ubah konfigurasi `PIN_D1` di dalam script `.ino` menjadi `21`)*.

*(Catatan: Penggunaan WT32-ETH01 jauh lebih praktis karena tidak perlu menyambung pin SPI ini).*

### E. Tombol Keluar (Exit Button)
Untuk tombol keluar, sambungkan kabel tombol secara langsung ke mainboard Tripod Gate (Terminal **GND/COM** dan **OPEN/PUSH**). Tidak perlu melewati ESP32.

## 3. Tata Cara Instalasi Software (Arduino IDE)

1.  Unduh dan pasang aplikasi **Arduino IDE** di komputer Anda.
2.  Tambahkan dukungan *board* ESP32 ke dalam Arduino IDE:
    *   Buka `File` > `Preferences`.
    *   Pada kolom *Additional Boards Manager URLs*, masukkan link berikut: `https://dl.espressif.com/dl/package_esp32_index.json`
    *   Buka `Tools` > `Board` > `Boards Manager...`, cari `esp32` lalu instal.
3.  Instal *Library* yang dibutuhkan (jika menggunakan LAN):
    *   Buka `Sketch` > `Include Library` > `Manage Libraries...`
    *   Cari dan instal library **Ethernet** (oleh berbagai kontributor, biasanya versi Arduino).
4.  Buka file `ESP32_Gate_Controller.ino` yang ada di folder ini menggunakan Arduino IDE.
5.  Sesuaikan **Konfigurasi IP Server**:
    *   Cari baris `const char* serverIpString = "192.168.1.100";` dan ubah `192.168.1.100` menjadi IP dari komputer/server lokal yang menjalankan aplikasi POS Laravel Anda.
6.  Hubungkan ESP32 ke komputer menggunakan kabel USB.
7.  Pilih jenis *Board* di Arduino IDE (`Tools` > `Board` > `ESP32 Arduino` > `DOIT ESP32 DEVKIT V1` atau sesuai seri Anda).
8.  Pilih *Port* yang terdeteksi (`Tools` > `Port`).
9.  Klik tombol **Upload** (Tanda panah ke kanan) dan tunggu hingga proses selesai (`Done uploading`).

## 4. Pengujian

1.  Nyalakan server Laravel Anda dan pastikan terhubung ke jaringan lokal (LAN/WiFi) yang sama dengan ESP32.
2.  Buka Arduino IDE dan buka **Serial Monitor** (`Tools` > `Serial Monitor`), atur baudrate ke `115200`.
3.  Perhatikan Serial Monitor. ESP32 harus mencetak pesan bahwa ia telah mendapatkan IP dari DHCP dan siap.
4.  Coba pindai Barcode Tiket atau tap Kartu RFID pada alat pembaca.
5.  Serial Monitor akan menampilkan kode yang terbaca dan hasil validasi dari server (AKSES DIIZINKAN / AKSES DITOLAK).
6.  Jika diizinkan, lampu indikator pada modul Relay akan menyala selama 1 detik (berbunyi *cetek*), yang akan membuka Tripod Gate.

Selesai.
