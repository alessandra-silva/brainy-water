<?php

    require_once 'Email.php';
    $nome = $_REQUEST['name'];
    $contato = $_REQUEST['email'];
    $mensagem = $_REQUEST['message'];

    class sendEmail
    {

        private $connection;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function sendUserEmail(string $email): bool
        {
            $emailObj = new Email($email);

            $name = '';
            $replyTo = '';
            $message = '';
            $altBody = '';

            $sendEmail = $emailObj->sendEmail($name, $replyTo, $message, $altBody);

            return $sendEmail;
        }
    }

?>