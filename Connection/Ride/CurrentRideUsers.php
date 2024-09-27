<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $userID = $_POST["userID"];
        
        $ride = new RideDetails();
        $rideDetails = $ride->getCurrentRide($userID);

        if ($rideDetails) {
            $response = $rideDetails; // No extra array wrapping
        } else {
            $response = array("message" => "No current ride found for the user", "status" => 2);
        }
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = array("message" => "Error: " . $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array("message" => "Invalid request method.");
    echo json_encode($response);
}
?>
