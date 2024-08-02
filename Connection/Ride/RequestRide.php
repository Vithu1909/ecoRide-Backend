<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $rideId = $_POST["rideid"];
        $userID=$_POST["userid"];
      
        
      

      
        $ride = new RideDetails();
        $ride->RequestRide($rideId,$userID);

       
        $res = $ride->AddRide();

        if ($res) {
            $response = array("message" => "Request sent Successfully","status" =>1 );
        } else {
            $response = array("message" => "Failed to Request Ride" ,"status" =>2  );
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