#include <U8x8lib.h>
#include <LoRa.h>
#include <SPI.h>

String receivedText;
String receivedRssi;

// WIFI_LoRa_32 ports
// GPIO5  -- SX1278's SCK
// GPIO19 -- SX1278's MISO
// GPIO27 -- SX1278's MOSI
// GPIO18 -- SX1278's CS
// GPIO14 -- SX1278's RESET
// GPIO26 -- SX1278's IRQ(Interrupt Request)

#define SS      18
#define RST     14
#define DI0     26
#define BAND    915E6

// the OLED used
U8X8_SSD1306_128X64_NONAME_SW_I2C u8x8(/* clock=*/ 15, /* data=*/ 4, /* reset=*/ 16);


int counter = 0;

void setup() {
  Serial.begin(115200);
  while (!Serial); //if just the the basic function, must connect to a computer
  delay(1000);

  u8x8.begin();
  u8x8.setFont(u8x8_font_chroma48medium8_r);

  Serial.println("LoRa Receiver");
  u8x8.drawString(0, 1, "LoRa Receiver");

  SPI.begin(5, 19, 27, 18);
  LoRa.setPins(SS, RST, DI0);

  if (!LoRa.begin(BAND)) {
    Serial.println("Starting LoRa failed!");
    u8x8.drawString(0, 1, "Starting LoRa failed!");
    while (1);
  }
}

void sendMessage() {
  // send packet
//  LoRa.beginPacket();
//  LoRa.print("CHOONEWZA-");
//  LoRa.print(counter++);
//  LoRa.endPacket();
}

void loop() {

  // try to parse packet
  int packetSize = LoRa.parsePacket();
  if (packetSize) {
    // received a packet
    
    u8x8.drawString(0, 2, "Payload");

    // read packet
    while (LoRa.available()) {
      receivedText += (char)LoRa.read();
    }
    receivedRssi = LoRa.packetRssi();

    //OLED Display Payload
    char * receivedTextOLED = new char [receivedText.length() + 1];
    strcpy (receivedTextOLED, receivedText.c_str());
    u8x8.drawString(0, 3, receivedTextOLED);

    //OLED Display RSSI
    u8x8.drawString(0, 7, "PacketRS");
    char currentrs[64];
    receivedRssi.toCharArray(currentrs, 64);
    u8x8.drawString(9, 7, currentrs);

    Serial.println("Received packet '"+receivedText+"' with RSSI "+receivedRssi);
    
    receivedText = "";
  }

    delay(300);
    sendMessage();
}
