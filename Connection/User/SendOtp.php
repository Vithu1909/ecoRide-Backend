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
   
    $otp = rand(100000, 999999);
    $user = new User(null, null, null, null, null, $Email, null, null, null, $otp); 

    $res = $user->sendOTP($otp); // Pass the OTP to the sendOTP method

    if ($res === true) {
        echo json_encode(array("message" => "OTP sent successfully.", "status" => 1));
    } elseif ($res === "email_not_found") {
        echo json_encode(array("message" => "Email not found.", "status" => 2));
    } else {
        echo json_encode(array("message" => "OTP sending failed.", "status" => 3));
    }
} else {
    echo json_encode(array("message" => "Method not allowed."));
}
?>
