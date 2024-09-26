<?php
use classes\RideDetails;
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


require_once "../../classes/RideDetails.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $rideID = $_POST["rideid"];

        $ride = new RideDetails($rideID, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);  // Assuming Ride class takes these parameters
        $res = $ride->deleteRide();  // Implement deleteRide function in Ride class
        if ($res) {
            $response = array("res" => $res,"message" => "Ride deleted successfully");
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
