<?php
require_once "../../classes/User.php";
use classes\User;

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $password = $data['password'];
    $user = new User(null, null, $username, null, null, null, null, $password,null,null);
    $res = $user->LoginUser();
    if ($res) {
                    echo json_encode(array("message" => "Login successful.", "userID" => $res['User_ID'], "userrole" => $res['userrole']));
                } else {
                    echo json_encode(array("message" => "Invalid Username or Password."));
                }
            
              



}
else {
    echo json_encode(array("message" => "Method not allowed."));
}
//     try {
//         $UserName = $_POST["username"];
//         $Password = $_POST["password"];

//         $user = new User(null, null, $UserName, null, null, null, null, $Password,null);
//         $res = $user->LoginUser();

//         if ($res) {
//             echo json_encode(array("message" => "Login successful.", "userID" => $res['User_ID'], "userrole" => $res['UserRole']));
//         } else {
//             echo json_encode(array("message" => "Invalid Username or Password."));
//         }
//     } catch (Exception $e) {
//         $response = array("message" => "Error: " . $e->getMessage());
//         echo json_encode($response);
//     }
// } else {
//     $response = array("message" => "Invalid request method.");
//     echo 'Invalid';
//     //echo json_encode($response);
// }
?>
