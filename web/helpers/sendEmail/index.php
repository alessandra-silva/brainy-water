<?php
declare(strict_types=1);

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

use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->from) && isset($data->to) && isset($data->replyTo) && isset($data->replyToName) && isset($data->message) && isset($data->subject) && isset($data->altBody)) {
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAutoTLS = false;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Username = 'projetowaterbrainy@gmail.com';
  $mail->Password = 'bw18023103';
  $mail->Port = 587;

  $mail->setFrom($data->from, 'BrainyWater');
  $mail->addReplyTo($data->replyTo, $data->replyToName);
  $mail->addAddress($data->to);

  $mail->isHTML(true);
  $mail->Subject = $data->subject;
  $mail->Body = $data->message;
  $mail->AltBody = $data->altBody;
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $result = $mail->send() ? true : false;
  $response = array("status" => $result ? 200 : 500 , "message" => $result ? "Successfully sent e-mail" : "Something went wrong while sending e-mail.");
} else {
  $response = array("status" => 400, "message" => "Malformed request.");
}

http_response_code($response["status"]);
echo json_encode($response);