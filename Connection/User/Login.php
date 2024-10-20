<?php
require_once "../../classes/User.php";
use classes\User;

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $password = $data['password'];
    $user = new User(null, null, $username, null, null, null, null, $password,null,null);
    $res = $user->LoginUser();
    if ($res) {
                    echo json_encode(array("message" => "Login successful.", "userID" => $res['User_ID'], "userrole" => $res['userrole']));
                } else {
                    echo json_encode(array("message" => "Invalid Username or Password."));
                }
            
              



}
else {
    echo json_encode(array("message" => "Method not allowed."));
}

?>
