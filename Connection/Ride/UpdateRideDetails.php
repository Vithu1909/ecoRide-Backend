
<?php
// header("Access-Control-Allow-Origin: http://localhost:3000");
// header("Content-Type: application/json; charset=UTF-8");

// require_once "../../classes/RideDetails.php";
// use classes\RideDetails;

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//   try {
//     $rideID = $_POST["rideID"];
//     $driverID = $_POST["driverID"];
//     $date = $_POST["date"];
//     $departureTime = $_POST["departureTime"];
//     $destinationTime = $_POST["destinationTime"];
//     $availableSeats = $_POST["availableSeats"];

//     error_log("rideID: $rideID, driverID: $driverID, date: $date, departureTime: $departureTime, destinationTime: $destinationTime, availableSeats: $availableSeats");

//     $ride = new RideDetails();
//     $result = $ride->editRide($rideID, $driverID, $date, $departureTime, $destinationTime, $availableSeats);

//     if ($result['status'] === 1) {
//       echo json_encode(["status" => 1, "message" => "Ride updated successfully"]);
//     } else {
//       echo json_encode(["status" => 0, "message" => $result['message']]);
//     }
//   } catch (Exception $e) {
//     error_log("Exception: " . $e->getMessage());
//     echo json_encode(["status" => 0, "message" => "Error: " . $e->getMessage()]);
//   }
// } else {
//   echo json_encode(["status" => 0, "message" => "Invalid request method"]);
// }

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