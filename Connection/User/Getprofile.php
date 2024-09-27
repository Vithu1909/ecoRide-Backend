<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../classes/User.php";

use classes\User;

if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    $userProfile = User::getProfileDetails($userID);

    if ($userProfile) {
        echo json_encode($userProfile);
    } else {
        echo json_encode(["message" => "Profile not found."]);
    }
} else {
    echo json_encode(["message" => "User ID not provided."]);
}