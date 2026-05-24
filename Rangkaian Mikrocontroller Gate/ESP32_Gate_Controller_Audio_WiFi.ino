#include <WiFi.h>
#include <HTTPClient.h>
#include "HardwareSerial.h"
#include "DFRobotDFPlayerMini.h"

// ========== KONFIGURASI WIFI ==========
const char* ssid = "NAMA_WIFI_ANDA";
const char* password = "PASSWORD_WIFI";

// ========== KONFIGURASI BACKEND ==========
const char* serverUrl = "http://192.168.1.100:8000/api/gate/check"; 
const String recordOwnerId = "PT001"; 

// ========== KONFIGURASI PIN ESP32-S3 SHIELD ==========
// Pin Relay
const int RELAY_PIN = 48; // Relay 1
const int RELAY_ON = HIGH; 
const int RELAY_OFF = LOW;

// Pin Wiegand (Scanner/RFID)
const int PIN_D0 = 22;
const int PIN_D1 = 23;

// Pin Push Button Exit (Untuk memutar suara "Selamat Tinggal")
// Hubungkan kabel tombol Exit ke Pin 15 dan GND.
const int EXIT_BTN_PIN = 15;

// Konfigurasi DFPlayer Mini (Menggunakan Serial1 ESP32-S3)
// Hubungkan TX DFPlayer ke RX ESP32 (Pin 16)
// Hubungkan RX DFPlayer ke TX ESP32 (Pin 17)
HardwareSerial mySoftwareSerial(1);
DFRobotDFPlayerMini myDFPlayer;

// ========== VARIABEL ==========
volatile unsigned long wiegandValue = 0;
volatile int bitCount = 0;
volatile unsigned long lastWiegandTime = 0;
bool isExitBtnPressed = false;
unsigned long lastExitPressTime = 0;

// Interrupt handler Wiegand
void IRAM_ATTR pinD0Interrupt() {
  bitCount++;
  wiegandValue = wiegandValue << 1;
  lastWiegandTime = millis();
}

void IRAM_ATTR pinD1Interrupt() {
  bitCount++;
  wiegandValue = (wiegandValue << 1) | 1;
  lastWiegandTime = millis();
}

void setup() {
  Serial.begin(115200);
  
  // Setup Relay & Tombol Exit
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, RELAY_OFF);
  pinMode(EXIT_BTN_PIN, INPUT_PULLUP);

  // Setup Wiegand
  pinMode(PIN_D0, INPUT_PULLUP);
  pinMode(PIN_D1, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(PIN_D0), pinD0Interrupt, FALLING);
  attachInterrupt(digitalPinToInterrupt(PIN_D1), pinD1Interrupt, FALLING);

  // Setup DFPlayer (Serial1 di ESP32-S3: RX=16, TX=17)
  mySoftwareSerial.begin(9600, SERIAL_8N1, 16, 17);
  Serial.println("Inisialisasi DFPlayer Mini...");
  
  if (!myDFPlayer.begin(mySoftwareSerial)) {
    Serial.println("Gagal mendeteksi DFPlayer: Cek koneksi kabel TX/RX atau MicroSD!");
  } else {
    Serial.println("DFPlayer Terhubung!");
    myDFPlayer.volume(20); // Atur volume (0-30)
    // myDFPlayer.play(3); // Putar suara startup opsional (003.mp3)
  }

  // Hubungkan WiFi
  WiFi.begin(ssid, password);
  Serial.print("Menghubungkan ke WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Terhubung! IP: " + WiFi.localIP().toString());
  Serial.println("Gate Controller Siap!");
}

void loop() {
  // 1. Cek Wiegand (Seseorang Tap Kartu/Barcode)
  if (bitCount > 0 && (millis() - lastWiegandTime > 50)) {
    String identifier = String(wiegandValue);
    bitCount = 0;
    wiegandValue = 0;
    
    Serial.println("Kode Diterima: " + identifier);
    checkAccessBackend(identifier);
  }

  // 2. Cek Tombol Keluar
  // Jika tombol ditekkan (LOW) dan ada jeda anti-bouncing
  if (digitalRead(EXIT_BTN_PIN) == LOW) {
    if (!isExitBtnPressed && (millis() - lastExitPressTime > 1000)) {
      isExitBtnPressed = true;
      lastExitPressTime = millis();
      
      Serial.println("Tombol Keluar Ditekan!");
      myDFPlayer.play(2); // Putar file 002.mp3 ("Selamat Tinggal")
      openGate();         // Buka Gate untuk keluar
    }
  } else {
    isExitBtnPressed = false;
  }
}

void checkAccessBackend(String identifier) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-Gate-Secret", "DSTECH-SECURE-KEY-2026");

    String payload = "{\"identifier\": \"" + identifier + "\", \"record_owner_id\": \"" + recordOwnerId + "\"}";
    int httpResponseCode = http.POST(payload);

    if (httpResponseCode > 0) {
      String responseBody = http.getString();
      Serial.println("Response: " + responseBody);

      if (responseBody.indexOf("\"access\":true") > 0 || responseBody.indexOf("\"access\": true") > 0) {
        Serial.println("Akses Diizinkan!");
        myDFPlayer.play(1); // Putar file 001.mp3 ("Silakan Masuk")
        openGate();
      } else {
        Serial.println("Akses Ditolak!");
        myDFPlayer.play(4); // Putar file 004.mp3 ("Akses Ditolak") opsional
      }
    } else {
      Serial.println("Error HTTP.");
    }
    http.end();
  } else {
    Serial.println("WiFi terputus!");
  }
}

void openGate() {
  digitalWrite(RELAY_PIN, RELAY_ON);
  delay(1000); 
  digitalWrite(RELAY_PIN, RELAY_OFF);
}
