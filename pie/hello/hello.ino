void setup() {
  // put your setup code here, to run once:
pinMode(16, OUTPUT);
}

void loop() {
  digitalWrite(16, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(1000);              // wait for a second
  digitalWrite(16, LOW);    // turn the LED off by making the voltage LOW
  delay(1000); 
}
