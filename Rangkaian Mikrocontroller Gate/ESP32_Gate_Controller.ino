#include <SPI.h>
#include <Ethernet.h>

// ========== KONFIGURASI JARINGAN (LAN) ==========
// Tentukan MAC Address untuk modul LAN (harus unik di jaringan Anda)
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
// Pin CS (Chip Select) untuk modul Ethernet W5500 (umumnya pin 5 di ESP32)
const int ETH_CS_PIN = 5;

// ========== KONFIGURASI BACKEND ==========
// Ganti localhost/127.0.0.1 dengan IP Server/Komputer yang menjalankan Laravel
// Contoh: 192.168.1.100 (Sesuaikan dengan IP lokal komputer server POS)
const char* serverIpString = "192.168.1.100";
const int serverPort = 8000;
// Jika menggunakan RecordOwnerID spesifik (opsional, biarkan kosong jika tidak dipakai)
const String recordOwnerId = "PT001"; 

// ========== KONFIGURASI PIN ESP32-S3 ==========
// Pin untuk Relay (Gate) pada Shield ESP32-S3
const int RELAY_PIN = 48;   // Relay 1 di papan Shield terhubung ke pin 48
// Ubah ke LOW jika Relay Anda tipe Active LOW (biasanya High Trigger sesuai deskripsi shield)
const int RELAY_ON = HIGH; 
const int RELAY_OFF = LOW;

// Pin Wiegand (Scanner/RFID)
const int PIN_D0 = 22;
const int PIN_D1 = 23;

// ========== VARIABEL WIEGAND ==========
volatile unsigned long wiegandValue = 0;
volatile int bitCount = 0;
volatile unsigned long lastWiegandTime = 0;

// Interrupt handler D0
void IRAM_ATTR pinD0Interrupt() {
  bitCount++;
  wiegandValue = wiegandValue << 1; // Geser ke kiri (tambah bit 0)
  lastWiegandTime = millis();
}

// Interrupt handler D1
void IRAM_ATTR pinD1Interrupt() {
  bitCount++;
  wiegandValue = (wiegandValue << 1) | 1; // Geser ke kiri dan tambah bit 1
  lastWiegandTime = millis();
}

void setup() {
  Serial.begin(115200);
  
  // Setup Relay
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, RELAY_OFF);

  // Setup Wiegand Pins
  pinMode(PIN_D0, INPUT_PULLUP);
  pinMode(PIN_D1, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(PIN_D0), pinD0Interrupt, FALLING);
  attachInterrupt(digitalPinToInterrupt(PIN_D1), pinD1Interrupt, FALLING);

  // Hubungkan ke LAN (DHCP)
  Ethernet.init(ETH_CS_PIN);
  Serial.println("Mendapatkan IP dari DHCP...");
  
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Gagal terhubung via DHCP. Periksa kabel LAN.");
    // Jika gagal, program akan berhenti di sini, atau Anda bisa men-set IP Statis
    while (true) { delay(1); }
  }
  
  Serial.print("LAN Terhubung! IP Address: ");
  Serial.println(Ethernet.localIP());
  Serial.println("Gate Controller Siap. Silakan Tap/Scan!");
}

void loop() {
  // Jika ada input dari Wiegand dan sudah berhenti menerima bit (timeout 50ms)
  if (bitCount > 0 && (millis() - lastWiegandTime > 50)) {
    
    // Konversi langsung nilai desimal ke string sebagai Identifier
    String identifier = String(wiegandValue);
    
    Serial.println("================================");
    Serial.print("Kode Diterima: ");
    Serial.println(identifier);
    Serial.print("Total Bit: ");
    Serial.println(bitCount);

    // Reset variabel Wiegand untuk pembacaan selanjutnya
    bitCount = 0;
    wiegandValue = 0;

    // Kirim ke server
    checkAccessBackend(identifier);
  }
}

void checkAccessBackend(String identifier) {
  // Pastikan kabel LAN masih terpasang
  if (Ethernet.linkStatus() == LinkON) {
    Serial.println("Memvalidasi ke server...");
    
    // IPAddress perlu di parsing dari string (Untuk demo kita set IP langsung)
    IPAddress serverIP(192, 168, 1, 100); // SESUAIKAN DENGAN IP SERVER 
    
    EthernetClient client;
    if (client.connect(serverIP, serverPort)) {
      String payload = "{\"identifier\": \"" + identifier + "\", \"record_owner_id\": \"" + recordOwnerId + "\"}";
      
      // Kirim HTTP POST Request murni
      client.println("POST /api/gate/check HTTP/1.1");
      client.print("Host: ");
      client.println(serverIpString);
      client.println("Content-Type: application/json");
      client.println("X-Gate-Secret: DSTECH-SECURE-KEY-2026");
      client.print("Content-Length: ");
      client.println(payload.length());
      client.println();
      client.println(payload);

      // Baca Respon
      String responseBody = "";
      while (client.connected() || client.available()) {
        if (client.available()) {
          char c = client.read();
          responseBody += c;
        }
      }
      client.stop();
      
      Serial.println("Response: ");
      Serial.println(responseBody);

      // Cek apakah diizinkan
      if (responseBody.indexOf("\"access\":true") > 0 || responseBody.indexOf("\"access\": true") > 0) {
        Serial.println("AKSES DIIZINKAN! Membuka Gate...");
        openGate();
      } else {
        Serial.println("AKSES DITOLAK!");
      }
    } else {
      Serial.println("Koneksi ke server gagal!");
    }
  } else {
    Serial.println("Kabel LAN terputus! Tidak bisa memvalidasi.");
  }
}

void openGate() {
  digitalWrite(RELAY_PIN, RELAY_ON);
  delay(1000); // Tahan relay selama 1 detik (sesuaikan dengan spesifikasi Tripod Gate)
  digitalWrite(RELAY_PIN, RELAY_OFF);
}
