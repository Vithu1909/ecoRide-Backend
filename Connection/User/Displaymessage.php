<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once "../../classes/Message.php";

use classes\Message;

$data = Message::Displaymessage(); 

if ($data !== false) {
    echo json_encode($data);
} else {
    http_response_code(500);
    echo "Error retrieving  data.";
}
