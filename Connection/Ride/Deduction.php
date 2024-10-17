<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $bookingId = $_POST["BookingID"];
        $ride = new RideDetails();
        $res = $ride->deductAmountAndCalculateRevenue($bookingId);

        if ($res) {
           
            $response = array(
                "message" => $res['message'], 
                "status" => 1
            );
        } else {
            
            $response = array(
                "message" => "Failed to process the deduction.",
                "status" => 2
            );
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
