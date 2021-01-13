<?php declare(strict_types=1);

use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Email
{

    public function sendEmail(string $message, string $subject, string $altBody, string $replyTo): void
    {

      $from = 'projetowaterbrainy@gmail.com';
      $fromPassword = 'bw18023103';


      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAutoTLS = false;
      $mail->SMTPDebug = 1;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'tls';
      $mail->Username = $from;
      $mail->Password = $fromPassword;
      $mail->Port = 587;

      $to = $from;

      $mail->setFrom($from, 'BrainyWater');
      $mail->addReplyTo($replyTo, $replyTo);
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->AltBody = $altBody;
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
    }
}