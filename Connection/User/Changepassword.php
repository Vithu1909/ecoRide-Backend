<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


require_once "../../classes/User.php";

use classes\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userID = isset($_POST['userID']) ? trim($_POST['userID']) : '';
    $currentPassword = isset($_POST['currentPassword']) ? trim($_POST['currentPassword']) : '';
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';

    if (!empty($userID) && !empty($currentPassword) && !empty($newPassword)) {

        $result = User::changePassword($userID, $currentPassword, $newPassword);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Password updated successfully.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update password. Check current password or try again later.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Incomplete request data.'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ];
}

echo json_encode($response);
