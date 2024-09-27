<?php
use classes\RideDetails;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require_once "../../classes/RideDetails.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $rideID = $_POST["rideid"];
        
        // Initialize Ride object without parameters
        $ride = new RideDetails();  
        
        // Set rideID using a setter method
        $ride->setRideID($rideID);

        // Call the deleteRide method
        $res = $ride->deleteRide();
        
        if ($res) {
            $response = array("res" => $res, "message" => "Ride deleted successfully");
        } else {
            $response = array("message" => "Failed to delete the ride");
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
