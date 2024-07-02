<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
require_once "../../classes/User.php";

use classes\User;

$data = User::DisplayUser(); 

if ($data !== false) {
    echo json_encode($data);
} else {
    http_response_code(500);
    echo "Error retrieving  data.";
}
