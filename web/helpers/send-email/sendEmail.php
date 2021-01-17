<?php

use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendEmail(string $from, string $replyTo, string $replyToName, string $to, string $subject, string $message, string $altBody) : bool
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAutoTLS = false;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'projetowaterbrainy@gmail.com';
    $mail->Password = 'bw18023103';
    $mail->Port = 587;

    $mail->setFrom($from, 'BrainyWater');
    $mail->addReplyTo($replyTo, $replyToName);
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = $altBody;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    return $mail->send() ? true : false;
}
