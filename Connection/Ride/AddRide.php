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

        $ride=new RideDetails();
        $ride->setCost($cost);
        $ride->setDate($Date);
        $ride->setVehicleNo($vehicleNo);
        $ride->setVehicleModel($vehicleModel);
        $ride->setSeats($seats);
        $ride->setAirCondition($airCondition);
        $ride->setStartLocation($StartLocation);
        $ride->setEndLocation($EndLocation);
        $ride->setStartTime($StartTime);
        $ride->setEndTime($EndTime);
        $ride->setRoute($route);
        $ride->setPreferences($preferences);
        $ride->setDriver_ID($driverID);
        $ride->setGender($gender);

        $res = $ride->AddRide();

        if ($res) {
            $response = array("message" => "Ride Added Successfully", "status" => 1);
        } else {
            $response = array("message" => "Failed to add Ride", "status" => 2);
        }
    } catch (Exception $e) {
        $response = array("message" => "Error: " . $e->getMessage());
        error_log("Exception: " . $e->getMessage());
    }
    echo json_encode($response);
} else {
    $response = array("message" => "Invalid request method.");
    echo json_encode($response);
}
   

?>
