<?php
header("Access-Control-Allow-Origin: *");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $rideID = $_POST["rideID"];  // Get the rideID from POST request

        // Initialize Ride object without parameters
        $ride = new RideDetails();  

        // Set rideID using a setter method
        $ride->setRideID($rideID);

        // Call the SelectRide method to fetch ride details
        $res = $ride->SelectRide();
        
        if ($res) {
            $response = array("res" => $res, "message" => "Ride selected successfully");
        } else {
            $response = array("message" => "Ride selection not successful");
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
