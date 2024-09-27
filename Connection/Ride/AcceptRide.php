<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $BookId = $_POST["Bookid"];
        
        $ride = new RideDetails();
        $res = $ride->AcceptBooking($BookId);

        if ($res === "Request accepted successfully") {
            $response = array("message" => $res, "status" => 1);
        } else {
            $response = array("message" => $res, "status" => 2);
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
