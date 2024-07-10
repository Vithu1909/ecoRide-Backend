<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $vehicleNo = $_POST["vehicleNo"];
        $vehicleModel = $_POST["vehicleModel"];
        $seats = $_POST["seats"];
        $airCondition = ($_POST["airCondition"]) ;
        $StartLocation = $_POST["departurePoint"];
        $EndLocation = $_POST["destinationPoint"];
        $Date = $_POST["date"];
        $cost = $_POST["seatCost"];
        $StartTime = $_POST["departureTime"];
        $EndTime = $_POST["destinationTime"];
        $gender = $_POST["gender"];
        $route = $_POST["route"];
        $preferences = $_POST["preferences"];
        $driverID=$_POST["DriverID"];
        
        // // Handle the image file
        // if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        //     $vehicleImg = file_get_contents($_FILES["image"]["tmp_name"]);
        // } else {
        //     throw new Exception("Error in uploading image");
        // }

      
        $ride = new RideDetails(
            null,  // Ride_ID
            $driverID,  // Driver_ID (will be set in AddRide)
            null,  // Passanger_ID
            $StartLocation,
            $EndLocation,
            $StartTime,
            $EndTime,
            $vehicleNo,
            $vehicleModel,
            $seats,
            $airCondition,
            $Date,
            $cost,
            $gender,
           null,
            $route,
            $preferences,
            null, // publishedDate (will be set in AddRide)
            null  // publishedTime (will be set in AddRide)
        );

        // Add the ride
        $res = $ride->AddRide();

        if ($res) {
            $response = array("message" => "Ride Added Successfully","status" =>1 );
        } else {
            $response = array("message" => "Failed to add Ride" ,"status" =>2  );
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