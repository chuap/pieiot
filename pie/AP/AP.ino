#include <ESP8266WiFi.h>

WiFiServer server(88); // ประกาศสร้าง TCP Server ที่พอร์ต 88

String line;

void setup() {
  Serial.begin(115200); // เปิดใช้การ Debug ผ่าน Serial
  WiFi.mode(WIFI_AP); // ใช้งาน WiFi ในโหมด AP
  WiFi.softAP("Chuap"); // ตั้งให้ชื่อ WiFi เป็น ESP_IOXhop

  server.begin(); // เริ่มต้นใช้ TCP Server
}

void loop() {
  WiFiClient client = server.available();
  if (!client) // ถ้าไม่มีการเชื่อมต่อมาใหม่
    return; // ส่งลับค่าว่าง ทำให้ลูปนี้ถูกยกเลิก

  Serial.println("New client"); // ส่งข้อความว่า New client ไปที่ Serial Monitor
  while (client.connected()) { // วนรอบไปเรื่อย ๆ หากยังมีการเชื่อมต่ออยู่
    if (client.available()) { // ถ้ามีการส่งข้อมูลเข้ามา
      char c = client.read(); // อ่านข้อมูลออกมา 1 ไบต์
      if (c == '\r') { // ถ้าเป็น \r (return)
        Serial.println(line); // แสดงตัวแปร line ไปที่ Serial Monitor
        line = ""; // ล้างค่าตัวแปร line
        break; // ออกจากลูป
      } else if (c == '\n') { // ถ้าเป็น \n (new line)
        // Pass {new line}
      } else { // ถ้าไม่ใช่
        line += c; // เพิ่มข้อมูล 1 ไบต์ ไปต่อท้ายในตัวแปร line
      }
    }
  }

  delay(1);
  client.stop(); // ปิดการเชื่อมต่อกับ Client
  Serial.println("Client disconnect"); // ส่งข้อความว่า Client disconnect ไปที่ Serial Monitor
}
