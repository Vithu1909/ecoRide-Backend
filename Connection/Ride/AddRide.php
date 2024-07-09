<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
    
        $vehicleNo = $_POST["vehicleNo"];
        $vehicleModel = $_POST["vehicleModel"];
        $seats = $_POST["seats"];
        $airCondition = isset($_POST["airCondition"]) ? true : false; 
        $StartLocation = $_POST["departurePoint"];
        $EndLocation = $_POST["destinationPoint"];
        $Date = $_POST["date"];
        $cost = $_POST["seatCost"];
        $StartTime = $_POST["departureTime"];
        $EndTime = $_POST["destinationTime"];
        $gender = $_POST["gender"];
        $vehicleImg = $_FILES["image"]["tmp_name"]; 
        $route = $_POST["route"];
        $preferences = $_POST["preferences"];

    
        $ride = new RideDetails(null, $vehicleNo, $vehicleModel, $seats, $airCondition, $StartLocation, $EndLocation, $Date, $cost, $StartTime, $EndTime, $gender, $vehicleImg, $route, $preferences);
        $res = $ride->AddRide();

        if ($res) {
            $response = array("message" => "Ride Added Successfully");
        } else {
            $response = array("message" => "Failed to add Ride");
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
