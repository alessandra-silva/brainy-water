// Projeto BrainyWater
// Integrantes: eduardo.puhl@ifc.edu.br  
// Alessandra Silva dos Santos - alessandra.ss426@gmail.com
// Camila Eloisa Nascimento - cam.nsc007@gmail.com

#include <WiFi.h>
#include <HTTPClient.h> 
#include <Wire.h>
#include <ACROBOTIC_SSD1306.h>

char* WifiName = "Unifique_WIFI_287093";
char* WifiPassword = "74815920";

char* sensorNumber1 = "1";
char* sensorToken1 = "jkh31j2kh4kj12h43.mateuslucas";

const int PIN_AP = 2;

float Caixa01, Caixa04;

void setup() {
  const int PIN_AP = 2;                 // Pino do botão
  pinMode(PIN_AP, INPUT);

  Serial.begin(115200);                 // Inicialização do monitor serial
  delay(4000);                          // Delay necessário para conexão com a internet
  
  WiFi.begin(WifiName, WifiPassword);   // WiFi connection
  
  while (WiFi.status() != WL_CONNECTED) // Aguarda a conexo com a internet
  {
    OledConectandoInternet();
  }
}

void loop() {
  float caixa01 = 100 - (analogRead(34) * 100 / 4095); // cálculo da porcentagem da caixa 01
  FazerRequisicao(sensorNumber1, sensorToken1, caixa01);

  float caixa02 = 100 - (analogRead(35) * 100 / 4095); // cálculo da porcentagem da caixa 02

  float cisterna = 100 - (analogRead(32) * 100 / 4095); // cálculo da porcentagem da cisterna

  delay(60000);
}

void OledConectandoInternet() {
  Wire.begin();
  oled.init();
  oled.setTextXY(0,0);
  oled.putString("BrainyWater");
  oled.setTextXY(2,0);
  oled.putString("Conectando à Internet");
  oled.clearDisplay();
}

void FazerRequisicao(String sensor, String token, double reading) {
  if (WiFi.status() == WL_CONNECTED)
  { //Check WiFi connection status

    HTTPClient http; //Declare object of class HTTPClient

    http.begin("http://api.brainywater.tech/sensor-value/"); //Specify request destination
    http.addHeader("Content-Type", "application/json");      //Specify content-type header

    String request = "{\"sensor\":" + sensor + ", \"token\":\"" + token + "\", \"reading\":" + reading + "}";

    Serial.println(request);

    int httpCode = http.POST(request); //Send the request
    String payload = http.getString(); //Get the response payload

    Serial.println(httpCode); //Print HTTP return code
    Serial.println(payload);  //Print request response payload

    http.end(); //Close connection

    delay(250);
  }
  else
  {
    Serial.println("Error in WiFi connection");
  }
}
void TelaInicial(){
   Wire.begin(); 
  oled.init();                      // Initialze SSD1306 OLED display
  oled.clearDisplay();              // Clear screen
  oled.setTextXY(0,0);              // Set cursor position, start of line 0
  oled.putString("BrainyWater");
  oled.setTextXY(2,0);              // Set cursor position, start of line 1
  oled.putString("Caixa 1:");
  oled.setTextXY(6,0);             // Set cursor position, line 2 10th character
  oled.putString("Fluxo");
}

// Endo endo endo pau no cu de quem ta lendo
