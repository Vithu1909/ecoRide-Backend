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
   
   
    $user = new User(null, null, null, null, null, $Email, null, null, null, $null); 

    $res = $user->VerifyOTP(); // Pass the OTP to the sendOTP method

    if ($res) {
        echo json_encode(array("message" => "OTP verify successfully.", "status" => 1,"otp"=>$res['otp'],"userID"=>$res['User_ID']));
    } elseif ($res === "email_not_found") {
        echo json_encode(array("message" => "Email not found.", "status" => 2));
    } else {
        echo json_encode(array("message" => "OTP verify failed.", "status" => 3));
    }
} else {
    echo json_encode(array("message" => "Method not allowed."));
}
?>
