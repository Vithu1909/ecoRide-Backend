<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../../classes/User.php";

use classes\User;

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['userId'], $data['username'], $data['name'], $data['email'], $data['nicno'], $data['gender'], $data['phoneno'])) {
        $userId = $data['userId'];
        $username = $data['username'];
        $name = $data['name'];
        $email = $data['email'];
        $nicno = $data['nicno'];
        $gender = $data['gender'];
        $phoneno = $data['phoneno'];


        $user = new User($userId, $name, $username, $nicno, $phoneno, $email, $gender, null, null, null);


        $result = $user->editProfile($userId, $username, $name, $email, $nicno, $gender, $phoneno);

        if ($result) {
            echo json_encode(array("success" => true, "message" => "Profile updated successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Failed to update profile."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Missing required parameters."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Method not allowed."));
}
