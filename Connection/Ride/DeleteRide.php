<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (isset($_POST["rideID"])) {
            $rideId = $_POST["rideID"]; 

            error_log("Received RideID: " . $rideId); 

            $ride = new RideDetails();
            $res = $ride->deleteRide($rideId);

            if ($res === "Ride and associated bookings deleted successfully") {
                $response = array("message" => $res, "status" => 1);
            } else {
                $response = array("message" => $res, "status" => 2);
            }

            echo json_encode($response);
        } else {
            throw new Exception("rideId not provided in request");
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
?>
