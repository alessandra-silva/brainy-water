<?php

    require_once 'Email.php';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    class sendEmailTo
    {
        private $connection;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function sendUserEmail(string $email, string $name, string $message, string $replyTo): bool
        {
            $emailObj = new Email($email);

            $sendEmail = $emailObj->sendEmail($email, $name, $message, $replyTo);

            return $sendEmail;
        }
    }

?>