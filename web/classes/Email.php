<?php declare(strict_types=1);

use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Email
{

    public function sendEmail(string $message, string $subject, string $altBody, string $replyTo): bool
    {
      $fromHost = 'smtp.gmail.com';
      $from = 'projetowaterbrainy@gmail.com';
      $fromPassword = '18023103bw';
      $fromPort = 587;

      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = $fromHost;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'tls';
      $mail->Username = $from;
      $mail->Password = $fromPassword;
      $mail->Port = $fromPort;

      $to = $this->email;

      $mail->setFrom($replyTo, $replyTo);
      $mail->addReplyTo($replyTo, $replyTo);
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->AltBody = $altBody;
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';

      return !$mail->send() ? false : true;
    }
}