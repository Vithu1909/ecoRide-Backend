<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
require_once "../../classes/User.php";

use classes\User;

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['userID']) && !empty($_POST['userID'])) {
        $userID = $_POST['userID'];
        $response = User::deleteAccount($userID);
    } else {
        $response['message'] = 'User ID is required.';
    }
} else {
    $response['message'] = 'Invalid request method. POST method required.';
}

echo json_encode($response);
