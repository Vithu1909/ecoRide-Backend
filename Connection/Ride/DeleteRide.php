<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Make sure to check if both rideID and rateID are provided
        if (isset($_POST["rideID"]) && isset($_POST["rateID"])) {
            $rideId = $_POST["rideID"];  // Correctly retrieve rideID from request
            $rating = $_POST["rateID"];  // Correctly retrieve rateID from request

            // Log received values for debugging
            error_log("Received RideID: " . $rideId); 
            error_log("Received rateID: " . $rating);

            // Create RideDetails object and delete the ride
            $ride = new RideDetails();
            $res = $ride->deleteRide($rideId, $rating);

            // Check response and send appropriate JSON response
            if ($res === "Ride and associated bookings and deductions deleted successfully") {
                $response = array("message" => $res, "status" => 1);  // Set status to 1 for success
            } else {
                $response = array("message" => $res, "status" => 2);  // Set status to 2 for failure
            }

            echo json_encode($response);
        } else {
            throw new Exception("rideID or rateID not provided in request");
        }
    } catch (PDOException $e) {
        $response = array("message" => "Error: " . $e->getMessage());
        echo json_encode($response);
    } catch (Exception $e) {
        $response = array("message" => $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array("message" => "Invalid request method.");
    echo json_encode($response);
}
