#include <SPI.h>
#include <Ethernet.h>
#include "HardwareSerial.h"
#include "DFRobotDFPlayerMini.h"

// ========== KONFIGURASI JARINGAN (LAN W5500) ==========
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
// Pin CS (Chip Select) untuk modul W5500 (Gunakan pin 14 pada ESP32-S3)
const int ETH_CS_PIN = 14;

// ========== KONFIGURASI BACKEND ==========
const char* serverIpString = "192.168.1.100";
const int serverPort = 8000;
const String recordOwnerId = "PT001"; 

// ========== KONFIGURASI PIN ESP32-S3 SHIELD ==========
const int RELAY_PIN = 48; // Relay 1
const int RELAY_ON = HIGH; 
const int RELAY_OFF = LOW;

const int PIN_D0 = 22;
const int PIN_D1 = 23;

const int EXIT_BTN_PIN = 15;

// Konfigurasi DFPlayer (Serial1 di ESP32-S3: RX=16, TX=17)
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
  
  // Setup Relay & Tombol
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, RELAY_OFF);
  pinMode(EXIT_BTN_PIN, INPUT_PULLUP);

  // Setup Wiegand
  pinMode(PIN_D0, INPUT_PULLUP);
  pinMode(PIN_D1, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(PIN_D0), pinD0Interrupt, FALLING);
  attachInterrupt(digitalPinToInterrupt(PIN_D1), pinD1Interrupt, FALLING);

  // Setup DFPlayer
  mySoftwareSerial.begin(9600, SERIAL_8N1, 16, 17);
  Serial.println("Inisialisasi DFPlayer Mini...");
  if (!myDFPlayer.begin(mySoftwareSerial)) {
    Serial.println("DFPlayer Gagal!");
  } else {
    Serial.println("DFPlayer OK!");
    myDFPlayer.volume(20);
  }

  // Setup Koneksi LAN W5500 
  // Konfigurasi khusus SPI pin untuk ESP32-S3 (SCK=12, MISO=13, MOSI=11, CS=14)
  SPI.begin(12, 13, 11, ETH_CS_PIN);
  Ethernet.init(ETH_CS_PIN);
  
  Serial.println("Mendapatkan IP dari DHCP...");
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Gagal terhubung via LAN. Cek kabel!");
  } else {
    Serial.print("LAN Terhubung! IP Address: ");
    Serial.println(Ethernet.localIP());
  }
  
  Serial.println("Gate Controller Komplit (LAN+Audio) Siap!");
}

void loop() {
  // 1. Cek Wiegand
  if (bitCount > 0 && (millis() - lastWiegandTime > 50)) {
    String identifier = String(wiegandValue);
    bitCount = 0;
    wiegandValue = 0;
    
    Serial.println("Kode Diterima: " + identifier);
    checkAccessBackend(identifier);
  }

  // 2. Cek Tombol Keluar
  if (digitalRead(EXIT_BTN_PIN) == LOW) {
    if (!isExitBtnPressed && (millis() - lastExitPressTime > 1000)) {
      isExitBtnPressed = true;
      lastExitPressTime = millis();
      
      Serial.println("Tombol Keluar Ditekan!");
      myDFPlayer.play(2); // "Selamat Tinggal"
      openGate();         
    }
  } else {
    isExitBtnPressed = false;
  }
}

void checkAccessBackend(String identifier) {
  if (Ethernet.linkStatus() == LinkON) {
    Serial.println("Memvalidasi...");
    IPAddress serverIP(192, 168, 1, 100); 
    
    EthernetClient client;
    if (client.connect(serverIP, serverPort)) {
      String payload = "{\"identifier\": \"" + identifier + "\", \"record_owner_id\": \"" + recordOwnerId + "\"}";
      
      client.println("POST /api/gate/check HTTP/1.1");
      client.print("Host: ");
      client.println(serverIpString);
      client.println("Content-Type: application/json");
      client.println("X-Gate-Secret: DSTECH-SECURE-KEY-2026");
      client.print("Content-Length: ");
      client.println(payload.length());
      client.println();
      client.println(payload);

      String responseBody = "";
      while (client.connected() || client.available()) {
        if (client.available()) {
          char c = client.read();
          responseBody += c;
        }
      }
      client.stop();
      
      if (responseBody.indexOf("\"access\":true") > 0 || responseBody.indexOf("\"access\": true") > 0) {
        Serial.println("Akses Diizinkan!");
        myDFPlayer.play(1); // "Silakan Masuk"
        openGate();
      } else {
        Serial.println("Akses Ditolak!");
        myDFPlayer.play(4); // "Ditolak"
      }
    } else {
      Serial.println("Koneksi gagal!");
    }
  } else {
    Serial.println("Kabel LAN putus!");
  }
}

void openGate() {
  digitalWrite(RELAY_PIN, RELAY_ON);
  delay(1000); 
  digitalWrite(RELAY_PIN, RELAY_OFF);
}
