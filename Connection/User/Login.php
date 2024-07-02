<?php
require './classes/DBconnector.php';
use classes\DBconnector;
require_once "../../classes/User.php";
use classes\User;

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['username']) || empty($data['password'])) {
        echo json_encode(array("message" => "Username and Password are required."));
        exit();
    }

    $username = $data['username'];
    $password = $data['password'];

    

    $user = new User(null, $username, null, null, null, null, $password);
    $res = $user->LoginUser($username, $password);

    if ($res) {
        echo json_encode(array("message" => "Login successful.", "userID" => $res['User_ID'], "role" => $res['userrole']));
    } else {
        echo json_encode(array("message" => "Invalid Username or Password."));
    }
} else {
    echo json_encode(array("message" => "Method not allowed."));
}
?>
