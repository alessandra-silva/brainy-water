<?php

if (isset($_SESSION)) {
  session_destroy();
}

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

if (isset($data->email) && isset($data->password)) {
  $database = new Database();
  $db = $database->getConnection();

  $user = new User($db);
  $user->email = $data->email;

  $user->password = $data->password;
  $user->generate_password();

  $response = $user->authenticate();
  if ($response['status'] == 200) {
    session_start();
    $_SESSION['user'] = $response['user'];
    $_SESSION['name'] = $response['name'];
    $_SESSION['picture'] = $response['picture'];
    $_SESSION['isAuth'] = true;
  }
} else {
  $response = array("status" => 400, "message" => "Malformed request.");
}

http_response_code($response["status"]);
echo json_encode($response);
