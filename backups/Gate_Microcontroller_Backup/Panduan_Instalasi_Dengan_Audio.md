# Panduan Instalasi Smart Gate Controller (Fitur Suara Audio / DFPlayer)

Panduan ini dikhususkan bagi Anda yang ingin menambahkan fitur **Notifikasi Suara ("Silakan Masuk", "Selamat Tinggal")** menggunakan modul **DFPlayer Mini**.

Karena *Shield ESP32-S3 DevkitC* tidak memiliki port LAN bawaan, panduan ini menggunakan **Koneksi WiFi** agar rangkaian tidak terlalu rumit dengan kabel tambahan (modul W5500 + DFPlayer).

## 1. Daftar Perangkat yang Dibutuhkan

1.  **Modul Mikrokontroler:** **ESP32 S3 DevkitC 1 44PIN**.
2.  **Expansion Board:** **Shield ESP32-S3 IO Board DevkitC 1**.
3.  **Alat Pembaca (Scanner):** QR Code & RFID Reader 13.56Mhz (Output **Wiegand 26/34**).
4.  **Adaptor Daya:** Adaptor 12V (Min. 2A) dicolokkan ke soket DC papan Shield.
5.  **Modul Suara:** **DFPlayer Mini** MP3 Player.
6.  **MicroSD Card:** Ukuran kecil saja (1GB - 8GB cukup), diformat ke FAT32.
7.  **Speaker Mini:** Speaker kecil 3 Watt (8 Ohm atau 4 Ohm).
8.  **Tombol Keluar (Push Button):** Untuk memicu gate terbuka saat pengunjung ingin keluar dari area.

## 2. Persiapan File Suara (MicroSD)

Anda perlu merekam atau mendownload suara berformat `.mp3`.
1.  Format MicroSD Anda menjadi **FAT32**.
2.  Buat folder baru bernama `mp3` (harus huruf kecil) di dalam MicroSD.
3.  Masukkan file suara ke dalam folder `mp3` dan ubah namanya menjadi **angka 4 digit** atau **3 digit** seperti ini:
    *   `001.mp3` -> Suara "Silakan Masuk, Terima Kasih"
    *   `002.mp3` -> Suara "Selamat Tinggal, Sampai Jumpa Kembali"
    *   `003.mp3` -> Suara saat alat pertama kali menyala (opsional)
    *   `004.mp3` -> Suara "Maaf, Akses Ditolak" (opsional)
4.  Masukkan MicroSD ke dalam slot yang ada di modul **DFPlayer Mini** (Bukan slot MicroSD yang ada di ESP32 Shield).

## 3. Skema Pengkabelan (Wiring)

Pastikan Adaptor tidak dicolokkan ke listrik saat merangkai!

### A. Sambungan DFPlayer Mini ke Shield ESP32-S3
*   **VCC:** Hubungkan ke pin **5V** (Merah) di shield.
*   **GND:** Hubungkan ke pin **GND** (Hitam) di shield.
*   **RX:** Hubungkan ke pin kuning **17 (TX2 / TX)** di shield.
*   **TX:** Hubungkan ke pin kuning **16 (RX2 / RX)** di shield.
    *(Catatan: RX modul harus menyilang ke TX ESP, dan sebaliknya).*
*   **SPK_1 & SPK_2:** Hubungkan langsung ke dua kabel **Speaker Mini** Anda.

### B. Sambungan Scanner Wiegand ke Shield ESP32-S3
*   **Kabel Merah (12V):** Hubungkan ke **Terminal VCC 12V**.
*   **Kabel Hitam (GND):** Hubungkan ke pin **GND**.
*   **Kabel Hijau (D0):** Hubungkan ke pin **22** pada shield.
*   **Kabel Putih (D1):** Hubungkan ke pin **23** pada shield.

### C. Sambungan Relay ke Tripod Gate
*   Hubungkan terminal **COM** pada **Relay 1** (Relay di papan Shield) ke terminal **COM/GND** pada Mainboard Tripod Gate.
*   Hubungkan terminal **NO** pada **Relay 1** ke terminal **OPEN / PUSH** pada Mainboard Tripod Gate.

### D. Sambungan Tombol Keluar (Exit Button)
Karena kita butuh ESP32 untuk memutar lagu saat tombol keluar ditekan, kita hubungkan tombolnya ke ESP32 (Bukan ke Mainboard Tripod Gate).
*   Sambungkan satu kaki tombol ke pin kuning **15** di shield.
*   Sambungkan kaki tombol yang lain ke **GND** (Hitam) di shield.

## 4. Tata Cara Instalasi Software

1.  Pastikan Anda sudah menginstal **Arduino IDE** dan board **ESP32**.
2.  Instal Library **DFRobotDFPlayerMini** melalui Arduino IDE:
    *   Buka `Sketch` > `Include Library` > `Manage Libraries...`
    *   Cari "DFRobotDFPlayerMini" lalu klik Install.
3.  Buka file `ESP32_Gate_Controller_Audio_WiFi.ino`.
4.  Sesuaikan pengaturan di dalam file:
    *   Isi `ssid` dan `password` WiFi Anda.
    *   Isi `serverUrl` dengan IP lokal peladen POS Anda (misal: `http://192.168.1.100:8000/api/gate/check`).
5.  Colokkan ESP32-S3 ke komputer. Di menu `Tools` > `Board`, pilih **ESP32S3 Dev Module**.
6.  Klik tombol **Upload**.

## 5. Uji Coba

1.  Nyalakan alat (colok adaptor 12V).
2.  Tunggu sekitar 5 detik agar ESP32 terhubung ke WiFi dan mendeteksi DFPlayer.
3.  **Tes Masuk:** Tap kartu RFID / Scan Barcode. Jika valid di database, Tripod Gate akan terbuka dan Speaker akan berbunyi "Silakan Masuk".
4.  **Tes Keluar:** Tekan tombol Exit. Tripod Gate akan terbuka dan Speaker akan langsung berbunyi "Selamat Tinggal".
