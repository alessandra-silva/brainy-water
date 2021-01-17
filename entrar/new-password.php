<?php

require_once '../classes/User.class.php';
require_once '../helpers/send-email/sendEmail.php';
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

if (isset($data->email)) {
  $database = new Database();
  $db = $database->getConnection();

  $user = new User($db);
  $user->email = $data->email;

  $password = sha1($email + 'brainy_water');
  $user->password = $password;
  $user->generate_password();

  $response = $user->update();

  if ($response['status'] == 200) {
    $url = '../send-email/';
    $bw_email = 'projetowaterbrainy@gmail.com';
    $message = 'Sua nova senha é ' . $password . ', faça o login e altere sua senha.';

    $result = sendEmail($bw_email, $bw_email, 'Brainy Water', $data->email, 'Recuperar senha', $message, $message);
    $response = array("status" => $result ? 200 : 500, "message" => $result ? "Successfully sent e-mail" : "Something went wrong while sending e-mail.");
  }
} else {
  $response = array("status" => 400, "message" => "Malformed request.");
}

http_response_code($response["status"]);
echo json_encode($response);
