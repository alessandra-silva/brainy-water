<?php

class Database {

  private $host = "br850.hostgator.com.br";
  private $database_name = "brainy29_main";
  private $username = "brainy29_lele";
  private $password = "saranghae123";

  public $conn;

  public function getConnection() {
      $this->conn = null;

      try {
          $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->conn->exec("set names utf8");
      } catch(PDOException $exception) {
          echo "Something went wrong while connecting to database.";
      }
      
      return $this->conn;
  }
}

?>
