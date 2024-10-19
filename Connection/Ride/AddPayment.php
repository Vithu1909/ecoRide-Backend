<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php"; 
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
      
        $driverID = $_POST["driverID"]; 
        
        $cardData = array(
            "cardName" => $_POST["cardName"],
            "cardNumber" => $_POST["cardNumber"],
            "cardExpiryDate" => $_POST["cardExpiryDate"],
            "cardCVV" => $_POST["cardCVV"]
        );

       
        $rideDetails = new RideDetails();

        
        $res = $rideDetails->AddRidePayment($cardData, $driverID);

        if ($res) {
            $response = array("message" => "Payment added successfully", "status" => 1);
        } else {
            $response = array("message" => "Failed to add payment", "status" => 0);
        }

        echo json_encode($response);

    } catch (PDOException $e) {
      
        error_log("AddRidePayment PDOException: " . $e->getMessage());
        $response = array("message" => "Error: " . $e->getMessage());
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
