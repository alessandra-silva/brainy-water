//Projeto BrainyWater
//Integrantes:eduardo.puhl@ifc.edu.br  
//Alessandra Silva dos Santos - alessandra.ss426@gmail.com
//Camila Eloisa Nascimento - cam.nsc@gmail.com


//WIFIMANAGER
#include <WiFi.h>      //ESP32 Core WiFi Library    
//needed for library
#include <DNSServer.h> //Local DNS Server used for redirecting all requests to the configuration portal ( https://github.com/zhouhan0126/DNSServer---esp32 )
#include <WebServer.h> //Local WebServer used to serve the configuration portal ( https://github.com/zhouhan0126/WebServer-esp32 )
#include <WiFiManager.h>   // WiFi Configuration Magic ( https://github.com/zhouhan0126/WIFIMANAGER-ESP32 ) >> https://github.com/tzapu/WiFiManager (ORIGINAL)
//flag para indicar se foi salva uma nova configuração de rede
bool shouldSaveConfig = false;


//THINGERIO
#include <ThingerESP32.h>
#define USERNAME "projetowaterbrainy"
#define DEVICE_ID "ESP32BW"
#define DEVICE_CREDENTIAL "ESP32BW"
ThingerESP32 thing(USERNAME, DEVICE_ID, DEVICE_CREDENTIAL);

//DISPLAY
#include <Wire.h>
#include <ACROBOTIC_SSD1306.h>




//pino do botão
const int PIN_AP = 2;

float Caixa01;
float Caixa02;
float Cisterna;
float Caixa04; // coisa nova

void TelaInicial();
void setup() {
  Serial.begin(9600);
  pinMode(PIN_AP, INPUT);
  //declaração do objeto wifiManager
  WiFiManager wifiManager;

  TelaInicial();

 const int PIN_AP = 2;





//THINGERIO
thing["Caixa01"] >> [](pson& out){ out = int(Caixa01); };
thing["Caixa02"] >> [](pson& out){ out = int(Caixa02); };
thing["Cisterna"] >> [](pson& out){ out = int(Cisterna); };


//utilizando esse comando, as configurações são apagadas da memória
//caso tiver salvo alguma rede para conectar automaticamente, ela é apagada.
//  wifiManager.resetSettings();

//por padrão as mensagens de Debug vão aparecer no monitor serial, caso queira desabilitá-la
//utilize o comando setDebugOutput(false);
//  wifiManager.setDebugOutput(false);

//caso queira iniciar o Portal para se conectar a uma rede toda vez, sem tentar conectar 
//a uma rede salva anteriormente, use o startConfigPortal em vez do autoConnect
//  wifiManager.startConfigPortal(char const *apName, char const *apPassword = NULL);

  //setar IP fixo para o ESP (deve-se setar antes do autoConnect)
//  setAPStaticIPConfig(ip, gateway, subnet);
//  wifiManager.setAPStaticIPConfig(IPAddress(192,168,16,2), IPAddress(192,168,16,1), IPAddress(255,255,255,0)); //modo AP

//  setSTAStaticIPConfig(ip, gateway, subnet);
//  wifiManager.setSTAStaticIPConfig(IPAddress(192,168,0,99), IPAddress(192,168,0,1), IPAddress(255,255,255,0)); //modo estação

//callback para quando entra em modo de configuração AP
  wifiManager.setAPCallback(configModeCallback); 
//callback para quando se conecta em uma rede, ou seja, quando passa a trabalhar em modo estação
  wifiManager.setSaveConfigCallback(saveConfigCallback); 

  if (!wifiManager.autoConnect("BrainyWater","")){
    TelaConectWifi();//cria uma rede sem senha
  }
//wifiManager.autoConnect(); //gera automaticamente o SSID com o chip ID do ESP e sem senha

//  wifiManager.setMinimumSignalQuality(10); // % minima para ele mostrar no SCAN

//wifiManager.setRemoveDuplicateAPs(false); //remover redes duplicadas (SSID iguais)

//wifiManager.setConfigPortalTimeout(10); //timeout para o ESP nao ficar esperando para ser configurado para sempre
  
  



}

void loop() {
  
  WiFiManager wifiManager;
 
  Caixa01 = analogRead(34);
  Caixa01 = 100-(Caixa01*100/4095);
  oled.setTextXY(2,0);              // Set cursor position, start of line 1
  oled.putString("Caixa 1: "+ String(int(Caixa01)) +"%  ");
  thing.handle();

  Caixa02 = analogRead(35);
  Caixa02 = 100-(Caixa02*100/4095);
  oled.setTextXY(3,0);              // Set cursor position, start of line 1
  oled.putString("Caixa 2: "+ String(int(Caixa02)) +"%  ");
  thing.handle();

  
  Cisterna = analogRead(32);
  Cisterna = 100-(Cisterna*100/4095);
  oled.setTextXY(4,0);              // Set cursor position, start of line 1
  oled.putString("Cisterna: "+ String(int(Cisterna)) +"%  ");
  thing.handle();

  //coisa nova
  oled.putString("Caixa 4: "+ String(int(Caixa04)) +"%  ");
  oled.setTextXY(4,0);              // Set cursor position, start of line 1
  
  thing.handle();


  

}



void TelaConectWifi(){
   Wire.begin(); 
  oled.init();                      // Initialze SSD1306 OLED display
  oled.clearDisplay();              // Clear screen
  oled.setTextXY(0,0);              // Set cursor position, start of line 0
  oled.putString("BrainyWater");
  oled.setTextXY(1,0);
  oled.putString("192.168.4.1");
  oled.setTextXY(2,0);              // Set cursor position, start of line 1
  oled.putString("Falha de Conexao");
  oled.setTextXY(3,0);              // Set cursor position, start of line 1
  oled.putString("Conecte a rede  BrainyWater");
 
}


void TelaInicial(){
   Wire.begin(); 
  oled.init();                      // Initialze SSD1306 OLED display
  oled.clearDisplay();              // Clear screen
  oled.setTextXY(0,0);              // Set cursor position, start of line 0
  oled.putString("BrainyWater");
  oled.setTextXY(2,0);              // Set cursor position, start of line 1
  oled.putString("Caixa 1:");
  oled.setTextXY(3,0);              // Set cursor position, start of line 2
  oled.putString("Caixa 2:");
  oled.setTextXY(4,0);             // Set cursor position, line 2 10th character
  oled.putString("Cisterna:");
  oled.setTextXY(5,0);
   oled.putString("Caixa 4:");
  oled.setTextXY(6,0);             // Set cursor position, line 2 10th character
  oled.putString("Fluxo");
  
}


//callback que indica que o ESP entrou no modo AP
void configModeCallback (WiFiManager *myWiFiManager) {  
  Serial.println("Entrou no modo de configuração");
  Serial.println(WiFi.softAPIP()); //imprime o IP do AP
  Serial.println(myWiFiManager->getConfigPortalSSID()); //imprime o SSID criado da rede

}

//callback que indica que salvamos uma nova rede para se conectar (modo estação)
void saveConfigCallback () {
  Serial.println("Configuração salva");
  Serial.println(WiFi.softAPIP()); //imprime o IP do AP
}
