
<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $rideID = $_POST["rideID"];

        // Initialize Ride object and pass the ride ID
        $ride = new RideDetails($rideID, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null); 
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
