<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/RideDetails.php";
use classes\RideDetails;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $BookId = $_POST["Bookid"];
        $rating = $_POST["rating"];

        // Create a new RideDetails instance and call addrating method
        $ride = new RideDetails();
        $res = $ride->addrating($BookId, $rating);

        // Handle the response from addrating method
        if ($res === true) {
            $response = array("message" => "Rating added successfully", "status" => 1);
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
