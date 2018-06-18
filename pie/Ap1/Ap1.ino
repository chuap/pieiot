
#include <ESP8266WiFi.h>
const char* ssid = "cat-local";
const char* password = "12341234";
const IPAddress remote_ip(8, 8, 8, 8); // Remote host

void setup() {
  Serial.begin(115200);
  //Serial.println();
  Serial.print("connecting to ");
  
  Serial.println(ssid);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    Serial.println(WiFi.status());
 }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Setting AP");
  WiFi.mode(WIFI_AP_STA);
  WiFi.softAP("Chuap-8266", "12341234");
  Serial.println(WiFi.localIP());




}

void loop() {

}
