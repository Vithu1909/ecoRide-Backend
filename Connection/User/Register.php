<?php
header("Access-Control-Allow-Origin: *");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $Name = $_POST["name"];
        $UserName = $_POST["username"];
        $Email = $_POST["email"];
        $PhoneNo = $_POST["phone"];
        $NicNo = $_POST["nic"];
        $Gender = $_POST["gender"];
        $Password = $_POST["password"];
        

        $user = new User(null, $Name, $UserName, $NicNo, $PhoneNo, $Email, $Gender, $Password,null,null);
        $res = $user->SignupUser();
        if ($res) {
            $response = array("message" => "User Added Successfully");
        } else {
            $response = array("message" => "Failed to add User or User already exists");
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
