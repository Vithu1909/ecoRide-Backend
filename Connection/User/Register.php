<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

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

        $user = new User(null, $Name, $UserName, $NicNo, $PhoneNo, $Email, $Gender, $Password, null, null);
        $res = $user->SignupUser();

       
        echo json_encode($res);
    } catch (Exception $e) {
        $response = [
            "status" => "failure",
            "message" => "Error: " . $e->getMessage()
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => "failure",
        "message" => "Invalid request method."
    ];
    echo json_encode($response);
}
?>
