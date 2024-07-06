<?php
require_once "../../classes/User.php";
use classes\User;

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $Email = $_POST["email"];
    $Password=$_POST["newPassword"];
    $User_id=$_POST["userid"];
   
   
    $user = new User($User_id, null, null, null, null, $null, null, $Password, null, $null); 

    $res = $user->Updatepassword(); // Pass the OTP to the sendOTP method

    if ($res) {
        echo json_encode(array("message" => "Password change successfully.", "status" => 1,"userrole"=>$res['userrole']));
    } 
    else {
        echo json_encode(array("message" => "Password chang failed.", "status" => 2));
    }
} else {
    echo json_encode(array("message" => "Method not allowed."));
}
?>
