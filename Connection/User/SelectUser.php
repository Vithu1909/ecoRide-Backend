<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/User.php";
use classes\User;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $userID = $_POST["userID"];
        

        $user = new User($userID,null , null, null, null, null, null, null,null,null);
        $res = $user->SelectUser();
        if ($res) {
            $response = array("res" => $res,"message" => "User select Successfully");
        } else {
            $response = array("message" => "User select not  Successfully");
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
?>
