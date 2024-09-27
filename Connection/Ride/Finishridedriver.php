<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // $rideID = $_POST["rideID"];
        $userID = $_POST["userID"];

        $ride = new RideDetails();

        $res = $ride->finishRide($rideID, $userID);

        if ($res) {
            $response = array("message" => "Ride finished successfully", "status" => 1);
        } else {
            $response = array("message" => "No ride found or not authorized", "status" => 2);
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