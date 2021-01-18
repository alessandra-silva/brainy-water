<?php

class Sensors
{
  // Connection
  private $conn;

  // Columns
  public $user;
  public $name;
  public $sensor;

  // Database connection
  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function getAllSensors()
  {
    try {
      $sqlQuery = "SELECT `id`, `name`, `type` 
                   FROM `Sensor`
                   WHERE `user` = :user";

      $stmt = $this->conn->prepare($sqlQuery);

      $stmt->bindParam(":user", $this->user, PDO::PARAM_INT);

      $stmt->execute();

      $data = new stdClass();
      $counter = 0;

      while ($sensor = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data->status = 200;
        $data->sensors[$counter]->id = $sensor['id'];
        $data->sensors[$counter]->name = $sensor['name'];
        $data->sensors[$counter]->type = $sensor['type'];

        $sqlQuery = "SELECT `reading`, `date` 
                    FROM `Sensor_value` 
                    WHERE `sensor` = :sensor 
                    ORDER BY `id` 
                    DESC LIMIT 1";

        $sensorStmt = $this->conn->prepare($sqlQuery);
        $sensorStmt->bindParam(":sensor", $sensor['id'], PDO::PARAM_INT);
        $sensorStmt->execute();
        $dataRow = $sensorStmt->fetch(PDO::FETCH_ASSOC);

        if (!$dataRow) {
          break;
        }

        $data->sensors[$counter]->last_reading = number_format($dataRow['reading'], 2, ',', '.');;
        $data->sensors[$counter]->date = (new DateTime($dataRow['date']))->format('d/m/Y H:i:s');
        $counter++;
      }
      return json_encode($data);
    } catch (Exception $e) {
      return array("status" => 500, "message" => "Something went wrong.");
    }
  }

  public function updateSensorName() {
    try {
      $sqlQuery = "UPDATE `Sensor` 
                   SET `name` = :name
                   WHERE `id` = :sensor
                   AND `user` = :user";

      $stmt = $this->conn->prepare($sqlQuery);

      $stmt->bindParam(":name", $this->name);
      $stmt->bindParam(":sensor", $this->sensor, PDO::PARAM_INT);
      $stmt->bindParam(":user", $this->user, PDO::PARAM_INT);

      $stmt->execute();

      $dataRow = $stmt->rowCount();

      if ($dataRow == 0) {
        return array("status" => 400, "message" => "Something went wrong.");
      } else {
        return array("status" => 200, "message" => "Updated.");
      }

    } catch (Exception $e) {
      return array("status" => 500, "message" => "Something went wrong.");
    }
  }
}
