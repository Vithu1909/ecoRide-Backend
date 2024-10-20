
<?php


header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        
        $rideID = $_POST["rideID"];
        $driverID = $_POST["driverID"];
        $departureTime = $_POST["departureTime"];
        $destinationTime = $_POST["destinationTime"];
        $availableSeats = $_POST["availableSeats"];

        
        $ride = new RideDetails();
        $res = $ride->editRide($rideID, $driverID, $departureTime, $destinationTime, $availableSeats);

      
        if ($res['status'] === 1) {
            $response = array(
                "message" => "Ride details updated successfully",
                "status" => 1
            );
        } else {
            $response = array(
                "message" => $res['message'],
                "status" => 0
            );
        }

        echo json_encode($response);
    } catch (PDOException $e) {
        
        $response = array(
            "message" => "Error: " . $e->getMessage(),
            "status" => 0
        );
        echo json_encode($response);
    }
} else {
    
    $response = array(
        "message" => "Invalid request method.",
        "status" => 0
    );
    echo json_encode($response);
}


?>