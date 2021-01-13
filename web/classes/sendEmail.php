<?php

    require_once 'Email.php';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $emailObj = new Email();

    $sendEmail = $emailObj->sendEmail($message, $name, $message, $email);

?>