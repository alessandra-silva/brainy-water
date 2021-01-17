<?php 

  // Show all errors 
  // error_reporting(E_ALL);
  // ini_set('display_errors', 1);

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  require_once("src/config/database.php");
  
  try {

      $database = new Database();
      
      $conn = $database->getConnection();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      http_response_code(200);
      echo json_encode(
        array("message" => "Server and database are connected.", 
              "status" => "Running",
              "api-endpoints" => array("sensor-value/"),
              "version" => "1.0")
      );
      
  } catch(PDOException $e) {
      http_response_code(500);
      echo json_encode(
        array("message" => "Connection failed. Please contact the server administrator.")
      );
  }
