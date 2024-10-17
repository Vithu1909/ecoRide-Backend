<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json"); 

require_once "../../classes/User.php"; 
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $user = new User(null, null, null, null, null, null, null, null, null, null);

        $monthlyDeductions = $user->getMonthlyDeductionAmounts();

        if ($monthlyDeductions !== false) {
            $response = array(
                "monthly_deductions" => $monthlyDeductions, 
                "message" => "Monthly deductions fetched successfully"
            );
        } else {
            $response = array("message" => "Failed to fetch monthly deductions.");
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
