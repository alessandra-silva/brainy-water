<?php

class Database {

  private $host = "localhost";
  private $database_name = "id14599367_brainywater";
  private $username = "id14599367_mateux";
  private $password = "G9lMJ@5wiwsO+tUn";

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
