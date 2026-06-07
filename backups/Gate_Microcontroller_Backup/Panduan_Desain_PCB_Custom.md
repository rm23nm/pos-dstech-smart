# Panduan Pembuatan Papan PCB Kustom (Smart Gate Controller)

Jika Anda berniat untuk mendesain dan mencetak papan PCB (Printed Circuit Board) Anda sendiri dari nol alih-alih membeli papan *Shield* buatan pabrik, berikut adalah panduan skema kelistrikan (routing) dan langkah-langkah pembuatannya.

Membuat PCB sendiri sangat berguna jika Anda ingin membuat produk ini secara massal (produksi banyak) untuk klien-klien *Smart Gate* Anda dengan label merk nama perusahaan Anda sendiri.

---

## BAGIAN 1: Skema Jalur (Routing Schematic)

Berikut adalah daftar jalur tembaga (Trace) yang harus dihubungkan dalam *software* desain PCB Anda. Pusat dari PCB ini adalah pin-pin soket *Female Header* tempat mikrokontroler **ESP32-S3** ditancapkan.

### 1. Jalur Catu Daya (Power Line)
Ini adalah tulang punggung kelistrikan papan Anda.
*   **Jack DC (Input):** Pasang colokan DC barel untuk Adaptor 12V.
*   **Modul Step-Down (LM2596 / MP1584):** Tarik jalur dari `12V Input` ke modul Step-Down untuk diturunkan menjadi **5V**.
*   **Jalur 5V:** Tarik dari *output* Step-Down ke pin **5V/VIN** di soket ESP32-S3, pin **VCC** DFPlayer Mini, dan pin **VCC** Relay.
*   **Jalur 3.3V:** Tarik dari pin **3.3V** di soket ESP32-S3 (atau pasang regulator AMS1117-3.3V sendiri) menuju ke pin **VCC** Modul Ethernet W5500.
*   **Jalur GND (Ground):** **SANGAT PENTING.** Gabungkan semua jalur negatif (GND) menjadi satu jaringan *Copper Pour / Ground Plane* yang mengelilingi seluruh papan.

### 2. Jalur Pembaca Scanner (Wiegand/RS485)
Gunakan *Screw Terminal Block* (Terminal Baut) 4-Pin.
*   **Pin 1 (12V):** Tarik langsung dari `12V Input` murni.
*   **Pin 2 (GND):** Hubungkan ke `GND` papan.
*   **Pin 3 (D0 / Hijau):** Tarik jalur tembaga ke pin **GPIO 22** ESP32-S3.
*   **Pin 4 (D1 / Putih):** Tarik jalur tembaga ke pin **GPIO 23** ESP32-S3.

### 3. Jalur Modul LAN (W5500)
Pasang soket *Female Header* untuk menancapkan Modul W5500. Tarik jalur berikut:
*   **SCK:** Ke pin **GPIO 12**.
*   **MISO:** Ke pin **GPIO 13**.
*   **MOSI:** Ke pin **GPIO 11**.
*   **SCS (CS):** Ke pin **GPIO 14**.
*   **(VCC ke 3.3V dan GND ke GND).**

### 4. Jalur DFPlayer Mini (Suara)
Pasang soket *Female Header* 16-pin (2 baris 8-pin) untuk menancapkan DFPlayer.
*   **RX (Pin 2 di DFPlayer):** Tarik jalur menyilang ke **GPIO 17 (TX)** ESP32-S3.
*   **TX (Pin 3 di DFPlayer):** Tarik jalur menyilang ke **GPIO 16 (RX)** ESP32-S3.
*   **SPK_1 & SPK_2:** Tarik jalur tembaga yang **agak tebal** (minimal 0.5mm / 20 mil) ke *Screw Terminal* 2-Pin untuk dicolok ke kabel Speaker.
*   **(VCC ke 5V dan GND ke GND).**

### 5. Jalur Relay (Penggerak Tuas Gate)
Pasang Relay 5V 1-Channel (atau IC Relay seperti SRD-05VDC, Transistor penguat, dan Dioda Flyback jika Anda merakit komponen murni, bukan pakai modul jadi).
*   **IN (Sinyal):** Tarik jalur ke pin **GPIO 48**.
*   **COM (Keluaran Relay):** Tarik jalur tembaga yang tebal ke *Screw Terminal*.
*   **NO (Normally Open):** Tarik jalur tembaga tebal ke *Screw Terminal* di sebelahnya.

### 6. Jalur Tombol Keluar (Exit Button)
Gunakan *Screw Terminal Block* 2-Pin.
*   **Pin 1:** Ke pin **GPIO 15**.
*   **Pin 2:** Ke **GND**.

---

## BAGIAN 2: Tata Cara Mendesain dan Mencetak PCB

Jika Anda ingin PCB yang terlihat profesional, disablon warna hijau/hitam, dan anti karat (bukan PCB bolong-bolong yang disolder kawat manual), ikuti langkah modern ini:

### 1. Gunakan Software Desain (Contoh: EasyEDA)
**EasyEDA** adalah aplikasi perancang PCB berbasis web yang paling populer untuk pemula dan profesional (gratis).
1. Buka situs `easyeda.com` dan buat *Project* baru.
2. **Buat Schematic (.sch):** Masukkan komponen (*ESP32-S3, DFPlayer, W5500, Terminal Block, LM2596*) dari *Library* yang tersedia. Hubungkan kaki-kaki komponen menggunakan garis *Wire* sesuai panduan *Routing* di BAGIAN 1.
3. **Ubah ke PCB (.pcb):** Klik tombol *Convert to PCB*.
4. **Susun Letak (Layout):** Geser kotak-kotak komponen agar letaknya rapi. Taruh *Terminal Block* (baut kabel) di pinggir papan agar mudah dicolok kabel dari luar.
5. **Auto-Routing / Manual Routing:** Tarik garis merah (tembaga atas) atau biru (tembaga bawah) untuk menyambungkan antar pin komponen.
6. **Beri Nama/Logo:** Tambahkan teks *Silkscreen* seperti logo perusahaan Anda atau tulisan indikator "D0", "D1", "RELAY", "12V IN" di samping terminal baut agar teknisi lapangan tidak salah pasang kabel.

### 2. Mencetak PCB (Pabrikasi)
1. Di EasyEDA, klik tombol **"Generate Gerber"**. Ini akan mengunduh file berekstensi `.zip` (File Gerber adalah bahasa standar mesin cetak PCB pabrik di seluruh dunia).
2. Buka situs manufaktur PCB seperti **JLCPCB.com** atau **PCBWay.com** (ini adalah pabrik terbesar yang sering dipakai engineer Indonesia).
3. Unggah file `.zip` Gerber Anda.
4. Pilih warna papan (Hijau, Hitam, Biru, Putih).
5. Bayar (harganya biasanya sangat murah, sekitar $2-$5 untuk 5 keping PCB prototipe).
6. Papan profesional dengan tulisan merk Anda akan dikirim ke rumah Anda dalam 1-2 minggu.

### 3. Perakitan Akhir (Soldering)
Setelah papan PCB dari pabrik tiba:
1. Siapkan Solder dan Timah.
2. Masukkan pin *Female Header*, colokan *Terminal Block*, dan komponen pelengkap ke lubang-lubang yang sudah tercetak pas.
3. Solder kaki-kakinya dari bawah.
4. Terakhir, tinggal "tancapkan" ESP32-S3, modul W5500, dan DFPlayer Mini ke soket *Female Header* yang sudah disolder tadi. Alat Anda pun siap dijual/dipasang!
