<?php

session_start();

require_once '../classes/Sensors.class.php';
require_once '../api/src/config/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    echo json_encode(array(
        "status" => 405,
        "message" => "You cannot " . $_SERVER['REQUEST_METHOD'] . " this endpoint."
    ));
    exit();
}

if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] == true) {
    $database = new Database();
    $db = $database->getConnection();
    
    $sensors = new Sensors($db);
    $sensors->user = $_SESSION['user'];
    
    http_response_code($response["status"]);
    $result = $sensors->getAllSensors();
    print($result);
}
