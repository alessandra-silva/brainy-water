<?php

    require_once 'Email.php';
    $nome = isset($_POST['name']) ? $_POST['name'] : '';
    $contato = isset($_POST['email']) ? $_POST['email'] : '';
    $mensagem = isset($_POST['message']) ? $_POST['message'] : '';

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