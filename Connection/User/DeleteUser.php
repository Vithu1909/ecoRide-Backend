<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $userID = $_POST["userid"];
        
        $user = new User($userID, null, null, null, null, null, null, null, null, null);
        

        $res = $user->deleteUser($userID);
        if ($res) {
            $response = array("status" => 1, "message" => "User deleted successfully");
        } else {
            $response = array("status" => 0, "message" => "User deletion failed","userid"=>$userID);
        }
        echo json_encode($response);
    } catch (Exception $e) {
        $response = array("status" => 0, "message" => "Error: " . $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array("status" => 0, "message" => "Invalid request method.");
    echo json_encode($response);
}
?>
