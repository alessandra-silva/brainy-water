<?php

session_start();

require_once '../classes/User.class.php';
require_once '../api/src/config/database.php';

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

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->name)) {

  if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true) {
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->email = $data->email;
    $user->name = $data->name;
    $user->id = $_SESSION['user'];

    $response = $user->updateEmailAndName();

    if ($response['status'] == 200) {
      $_SESSION['name'] = $user->name;
      $_SESSION['email'] = $user->email;
    }
  }
} else {
  $response = array("status" => 400, "message" => "Malformed request.");
}

http_response_code($response["status"]);
echo json_encode($response);
