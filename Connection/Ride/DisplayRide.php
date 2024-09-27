<?php
header("Access-Control-Allow-Origin: *");
require_once "../../classes/RideDetails.php";
header("Content-Type: application/json; charset=UTF-8");

use classes\RideDetails;

$data = RideDetails::DisplayRide(); 

if ($data !== false) {
    echo json_encode($data);
} else {
    http_response_code(500);
    echo "Error retrieving data.";
}
?>
