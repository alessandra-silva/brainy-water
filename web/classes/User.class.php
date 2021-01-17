<?php

class User
{
  // Connection
  private $conn;

  // Tables
  private $db_table = "User";

  // Columns
	public $email;
	public $newPassword;

  // Database connection
  public function __construct($db)
  {
    $this->conn = $db;
  }

	public static function generate_password(string $password): string {
		return hash("sha512", $password);
	}

  // CREATE
  public function update()
  {
    // sanitize
    $this->email = htmlspecialchars(strip_tags($this->email));

    // 1) Verify sensor token
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
}
