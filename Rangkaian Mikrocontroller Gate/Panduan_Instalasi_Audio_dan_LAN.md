# Panduan Instalasi Smart Gate (Komplit: LAN Kabel + Suara Audio)

Panduan ini untuk Anda yang ingin menggunakan fitur **Lengkap**:
Menggunakan **Shield ESP32-S3 DevkitC 1** + **Modul Kabel LAN W5500** + **Modul Suara DFPlayer Mini**.

Karena ESP32-S3 Shield tidak memiliki colokan kabel LAN bawaan, Anda harus menyambungkan modul **W5500** secara eksternal.

## 1. Tambahan Perangkat
Selain perangkat pada panduan sebelumnya (ESP32-S3, Shield, DFPlayer, Scanner, Adaptor), Anda wajib membeli:
*   **Modul Ethernet W5500** (Modul kecil berwarna merah atau biru yang memiliki port RJ45 untuk colokan kabel LAN).

## 2. Skema Pengkabelan (Wiring) yang Baru

### A. Modul Ethernet W5500 (SPI)
Hubungkan pin dari W5500 ke pin kuning (Header) di ESP32-S3 Shield menggunakan kabel jumper *Female-to-Female*:
*   **VCC / 3.3V:** Hubungkan ke pin **3V3** di shield. *(JANGAN ke 5V agar modul W5500 tidak rusak).*
*   **GND:** Hubungkan ke pin **GND**.
*   **SCK (SCLK):** Hubungkan ke pin **12** di shield.
*   **MISO:** Hubungkan ke pin **13** di shield.
*   **MOSI:** Hubungkan ke pin **11** di shield.
*   **SCS (CS):** Hubungkan ke pin **14** di shield.

### B. DFPlayer Mini (Audio)
*   **VCC:** Hubungkan ke **5V**.
*   **GND:** Hubungkan ke **GND**.
*   **RX:** Hubungkan ke pin **17**.
*   **TX:** Hubungkan ke pin **16**.
*   **SPK_1 & SPK_2:** Langsung ke Speaker.

### C. Scanner Wiegand
*   **12V & GND:** Hubungkan ke sumber daya (Terminal blok).
*   **D0 (Hijau):** Hubungkan ke pin **22**.
*   **D1 (Putih):** Hubungkan ke pin **23**.

### D. Tombol Keluar (Exit Button)
*   Kaki 1: Hubungkan ke pin **15**.
*   Kaki 2: Hubungkan ke **GND**.

### E. Relay (Sudah Built-in di Shield)
Tidak perlu sambung kabel tambahan ke ESP32, cukup gunakan sekrup terminal **COM** dan **NO** di **Relay 1** (yang dikendalikan secara internal oleh pin 48) menuju ke Mainboard Tripod Gate.

## 3. Instalasi Software

1.  Buka aplikasi Arduino IDE.
2.  Buka *file* bernama **`ESP32_Gate_Controller_Audio_LAN.ino`**.
3.  Pastikan library **Ethernet** (oleh Arduino) dan **DFRobotDFPlayerMini** sudah terpasang.
4.  Ubah IP Address peladen POS Anda di bagian konfigurasi:
    `const char* serverIpString = "192.168.1.100";`
5.  Upload kode ke ESP32-S3 (Pilih Board: **ESP32S3 Dev Module**).

## 4. Cara Kerja

Saat dicolokkan ke listrik:
1. ESP32-S3 akan mencoba mendapatkan IP Address dari _router_ melalui kabel LAN.
2. ESP32-S3 akan memeriksa koneksi ke DFPlayer Mini.
3. Saat Anda menyentuh tombol Keluar, sistem akan memutar MP3 "Selamat Tinggal" lalu membuka Tripod Gate secara lokal tanpa internet.
4. Saat Anda menscan Tiket/Kartu RFID, sistem akan mengirim data melalui kabel LAN ke aplikasi Laravel Anda. Jika valid, sistem memutar MP3 "Silakan Masuk" lalu membuka Tripod Gate.
