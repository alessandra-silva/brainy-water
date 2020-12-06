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

// Primeiro sensor
char* sensorNumber1 = "1";
char* sensorToken1 = "jkh31j2kh4kj12h43.mateuslucas";

// Segundo sensor
char* sensorNumber2 = "2";
char* sensorToken2 = "lkjh15b123hj5.mateuslucas";

// Terceiro sensor
char* sensorNumber3 = "3";
char* sensorToken3 = "jgh2315vxzcv.mateuslucas";

const int PIN_AP = 2;

float Caixa01, Caixa04;

void setup() {
  const int PIN_AP = 2;                 // Pino do botão
  pinMode(PIN_AP, INPUT);

  Serial.begin(115200);                 // Inicialização do monitor serial
  delay(4000);                          // Delay necessário para conexão com a internet
  
  WiFi.begin(WifiName, WifiPassword);   // WiFi connection
  
  Wire.begin(); // Inicializa o display
  oled.init();
  
  while (WiFi.status() != WL_CONNECTED) // Aguarda a conexo com a internet
  {
    oledConectandoInternet();
  }

  oled.clearDisplay(); // Limpa o display
}

void loop() {
  oled.clearDisplay(); // Limpa o display
  bool precisaEsperar = false;

  float caixa01 = 100 - (analogRead(34) * 100 / 4095); // cálculo da porcentagem da caixa 01

  float caixa02 = 100 - (analogRead(35) * 100 / 4095); // cálculo da porcentagem da caixa 02

  float cisterna = 100 - (analogRead(32) * 100 / 4095); // cálculo da porcentagem da cisterna

  if (caixa01 > 0) {
    FazerRequisicao(sensorNumber1, sensorToken1, caixa01);
    precisaEsperar = true;
    oledEnviandoParaAPI();
  }
  
  if (caixa02 > 0) {
    FazerRequisicao(sensorNumber2, sensorToken2, caixa02);
    precisaEsperar = true;
    oledEnviandoParaAPI();
  }

  if (cisterna > 0) {
    FazerRequisicao(sensorNumber3, sensorToken2, caixa03);
    precisaEsperar = true;
    oledEnviandoParaAPI();
  }

  if (precisaEsperar == true) {
    delay(1000); // Espera 1 minuto para fazer a leitura
  }
}

void oledEnviandoParaAPI() {
  oled.setTextXY(2,0);
  oled.putString("Enviando dados para a API...");
}

void oledConectandoInternet() {
  oled.setTextXY(0,0);
  oled.putString("BrainyWater");
  oled.setTextXY(2,0);
  oled.putString("Conectando à Internet");
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

// Endo endo endo pau no cu de quem ta lendo
