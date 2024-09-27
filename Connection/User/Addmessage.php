<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/Message.php";
use classes\Message;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        if (!isset($_POST["Msgname"], $_POST["Msgemail"], $_POST["Message"])) {
            throw new Exception("Missing required fields");
        }

        $Msgname = $_POST["Msgname"];
        $Msgemail = $_POST["Msgemail"];
        $Message = $_POST["Message"];

        $msg = new Message(null, $Msgname, $Msgemail, $Message, null);
        $res = $msg->addMessage();
        if ($res) {
            $response = array("message" => "Message Added Successfully");
        } else {
            $response = array("message" => "Failed to add Message");
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
