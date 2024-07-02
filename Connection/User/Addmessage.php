<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

require_once "../../classes/Message.php";
use classes\Message;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $Msgname = $_POST["Msgname"];
        $Msgemail = $_POST["Msgemail"];
        $Message = $_POST["Message"];
       

        $msg = new Message($MessageID,$Msgname, $Message, $Msgemail, );
        $res = $msg->Addmessage();
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
