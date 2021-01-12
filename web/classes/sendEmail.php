<?php

    require_once 'Email.php';
    $nome = $_POST['name'];
    $contato = $_POST['email'];
    $mensagem = $_POST['message'];

    class sendEmail
    {

        private $connection;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function sendUserEmail(string $email, $nome, $contato, $mensagem): bool
        {
            $emailObj = new Email($email);

            $name = $nome;
            $replyTo = $contato;
            $message = $mensagem;
            $altBody = '';

            $sendEmail = $emailObj->sendEmail($name, $replyTo, $message, $altBody);

            return $sendEmail;
        }
    }

?>