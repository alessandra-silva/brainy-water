<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  http_response_code(405);
  echo json_encode(array(
    "status" => 405,
    "message" => "You cannot " . $_SERVER['REQUEST_METHOD'] . " this endpoint."
  ));
  exit();
}

require_once '../src/config/database.php';
require_once '../src/classes/SensorValue.class.php';

$database = new Database();
$db = $database->getConnection();

$item = new SensorValue($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->sensor) && isset($data->reading) && isset($data->token)) {
  if (gettype($data->sensor) != "integer") {
    $response = array("status" => 401, "message" => "Sensor must be an integer.");
  } else if (gettype($data->reading) != "double") {
    $response = array("status" => 401, "message" => "Reading must be a double.");
  } else if (gettype($data->token) != "string") {
    $response = array("status" => 401, "message" => "Token must be a string.");
  } else {
    $item->sensor = $data->sensor;
    $item->reading = $data->reading;
    $item->token = $data->token;

    $response = $item->create();
  }
} else {
  $response = array("status" => 401, "message" => "Malformed request.");
}


http_response_code($response["status"]);
echo json_encode($response);
