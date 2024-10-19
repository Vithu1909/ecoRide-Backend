<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/User.php"; 

use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (!isset($_POST["userID"])) {
            throw new Exception("User ID not provided.");
        }

        $userID = $_POST["userID"];

        $user = new User($userID, null, null, null, null, null, null, null, null, null);
        
     
        $historyRecords = $user->getHistory($userID);
        
        
        if ($historyRecords) {
            $response = array("res" => $historyRecords, "message" => "History retrieved successfully.");
        } else {
            $response = array("message" => "No history found for this user.");
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
