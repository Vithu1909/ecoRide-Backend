<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
require_once "../../classes/RideDetails.php";
header("Content-Type: application/json; charset=UTF-8");

use classes\RideDetails;

$data = RideDetails::AdminDisplayRide(); 

if ($data !== false) {
    echo json_encode($data);
} else {
    http_response_code(500);
    echo "Error retrieving data.";
}
?>