<?php
header("Access-Control-Allow-Origin: *");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $userID = $_POST["userID"];
        
        $user = new User($userID, null, null, null, null, null, null, null, null, null);
        $userRole = $user->selectUserrole();
        if ($userRole !== false) {
            $response = array("userRole" => $userRole);
            echo json_encode($response);
        } else {
            $response = array("message" => "Failed to retrieve user role.");
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response = array("message" => "Error: " . $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array("message" => "Invalid request method.");
    echo json_encode($response);
}
?>
