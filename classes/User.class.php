<?php

class User
{
  // Connection
  private $conn;

  // Tables
  private $db_table = "User";

  // Columns
	public $email;
  public $password;

  // Database connection
  public function __construct($db)
  {
    $this->conn = $db;
  }

	public function generate_password(): void {
		$this->password = hash("sha512", $this->password);
	}

  // CREATE
  public function update()
  {
    // sanitize
    $this->email = htmlspecialchars(strip_tags($this->email));

    $sqlQuery = "SELECT `id` 
                   FROM `" . $this->db_table . "`
                   WHERE `email` = :email";

    $stmt = $this->conn->prepare($sqlQuery);

    // Bind data
    $stmt->bindParam(":email", $this->email);

    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dataRow) {
      return array("status" => 404, "message" => "Email not found.");
    } else {
      $sqlQuery = "UPDATE `" . $this->db_table . "`
                     SET `password` = :password 
                     WHERE `email` = :email";

      $stmt = $this->conn->prepare($sqlQuery);

      // bind data
      $stmt->bindParam(":password", $this->password);
      $stmt->bindParam(":email", $this->email);

      if ($stmt->execute()) {
        return array("status" => 200, "message" => "Password updated.");
      }

      return array("status" => 500, "message" => "Something went wrong.");
    }
  }

  public function authenticate()
  {
    // sanitize
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));

    $sqlQuery = "SELECT `id` 
                   FROM `" . $this->db_table . "`
                   WHERE `email` = :email
                   AND `password` = :password";

    $stmt = $this->conn->prepare($sqlQuery);

    // Bind data
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);

    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dataRow) {
      return array("status" => 401, "message" => "Unauthorized.");
    } else {
      return array("status" => 200, "message" => "Granted.", "user" => $dataRow['id']);
    }
  }
}
