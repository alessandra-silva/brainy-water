<?php

class SensorValue
{

  // Connection
  private $conn;

  // Tables
  private $db_table = "Sensor_value";
  private $db_parent_table = "Sensor";

  // Columns
  public $sensor;
  public $reading;
  public $token;

  // Database connection
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // CREATE
  public function create()
  {

    if ($this->reading <= 0) {
      return array("status" => 406, "message" => "The reading value must be greater than 0.");
    }

    // sanitize
    $this->sensor = htmlspecialchars(strip_tags($this->sensor));
    $this->token = htmlspecialchars(strip_tags($this->token));

    // 1) Verify sensor token
    $sqlQuery = "SELECT `id` 
                   FROM `" . $this->db_parent_table . "`
                   WHERE `id` = :sensor 
                     AND `token` = :token";

    $stmt = $this->conn->prepare($sqlQuery);

    // Bind data
    $stmt->bindParam(":sensor", $this->sensor, PDO::PARAM_INT);
    $stmt->bindParam(":token", $this->token);

    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dataRow) {
      return array("status" => 404, "message" => "Sensor not found or token malformed.");
    } else {
      $sqlQuery = "INSERT INTO `" . $this->db_table . "`
                     SET `reading` = :reading, 
                         `sensor` = :sensor";

      $stmt = $this->conn->prepare($sqlQuery);

      // bind data
      $stmt->bindParam(":reading", $this->reading);
      $stmt->bindParam(":sensor", $this->sensor, PDO::PARAM_INT);

      if ($stmt->execute()) {
        return array("status" => 201, "message" => "Successfully added sensor value.");
      }

      return array("status" => 500, "message" => "Something went wrong");
    }
  }
}
