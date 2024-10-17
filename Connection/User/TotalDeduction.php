<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); 
header("Content-Type: application/json");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $user = new User(null, null, null, null, null, null, null, null, null, null);
        
        $totalDeduction = $user->getTotalDeductionAmount();

        if ($totalDeduction !== false) {
            $response = array(
                "total_deduction" => $totalDeduction, 
                "message" => "Total deduction fetched successfully"
            );
        } else {
            $response = array("message" => "Failed to fetch total deduction.");
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
