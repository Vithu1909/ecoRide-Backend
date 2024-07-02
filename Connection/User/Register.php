<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $UserName = $_POST["username"];
        $Name = $_POST["name"];
        $NicNo = $_POST["nic"];
        $PhoneNo = $_POST["phone"];
        $Email = $_POST["email"];
        $Gender = $_POST["gender"];
        $Password = $_POST["password"];

        $user = new User($Name, $UserName, $NicNo, $PhoneNo, $Email, $Gender, $Password);
        $res = $user->SignupUser();
        if ($res) {
            $response = array("message" => "User Added Successfully");
        } else {
            $response = array("message" => "Failed to add User");
        }
        echo json_encode($response);
    } catch (Exception $e) {
        $response = array("message" => "Error: " . $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array("message" => "Invalid request method.");
    echo json_encode($response);
}
?>
