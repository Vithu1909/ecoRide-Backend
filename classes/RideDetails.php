<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RideDetails {
    private $Ride_ID;
    private $Driver_ID;
    private $Passanger_ID;
    private $StartLocation;
    private $EndLocation;
    private $StartTime;
    private $EndTime;
    private $vehicleNo;
    private $vehicleModel;
    private $seats;
    private $airCondition;
    private $Date;
    private $cost;
    private $gender;
    private $vehicleImg;
    private $route;
    private $preferences;
    private $publishedDate;
    private $publishedTime;

    public function __construct() {}

   
    
    public function setRide_ID($Ride_ID) { $this->Ride_ID = $Ride_ID; }
    public function setDriver_ID($Driver_ID) { $this->Driver_ID = $Driver_ID; }
    public function setPassanger_ID($Passanger_ID) { $this->Passanger_ID = $Passanger_ID; }
    public function setStartLocation($StartLocation) { $this->StartLocation = $StartLocation; }
    public function setEndLocation($EndLocation) { $this->EndLocation = $EndLocation; }
    public function setStartTime($StartTime) { $this->StartTime = $StartTime; }
    public function setEndTime($EndTime) { $this->EndTime = $EndTime; }
    public function setVehicleNo($vehicleNo) { $this->vehicleNo = $vehicleNo; }
    public function setVehicleModel($vehicleModel) { $this->vehicleModel = $vehicleModel; }
    public function setSeats($seats) { $this->seats = $seats; }
    public function setAirCondition($airCondition) { $this->airCondition = $airCondition; }
    public function setDate($Date) { $this->Date = $Date; }
    public function setCost($cost) { $this->cost = $cost; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setVehicleImg($vehicleImg) { $this->vehicleImg = $vehicleImg; }
    public function setRoute($route) { $this->route = $route; }
    public function setPreferences($preferences) { $this->preferences = $preferences; }
    public function setPublishedDate($publishedDate) { $this->publishedDate = $publishedDate; }
    public function setPublishedTime($publishedTime) { $this->publishedTime = $publishedTime; }

    public static function DisplayRide() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            
            $sql = "SELECT 
                        r.*, 
                        u.User_ID AS driver_ID, 
                        u.Name AS driverName, 
                        u.Email AS driverEmail, 
                        u.PhoneNo AS driverPhoneNo, 
                        u.NicNo AS driverNicNo, 
                        u.rating AS rating,
                        GROUP_CONCAT(
                            CONCAT(
                                'PassengerID:', p.User_ID, 
                                ', PassengerName:', p.Name, 
                                ', PassengerEmail:', p.Email, 
                                ', PassengerPhoneNo:', p.PhoneNo, 
                                ', PassengerNicNo:', p.NicNo
                            ) SEPARATOR '; '
                        ) AS passengers
                    FROM  
                        tb_ride r
                    INNER JOIN
                        tb_user u
                    ON
                        r.driverID = u.User_ID
                    LEFT JOIN
                        tb_booking b
                    ON
                        r.rideID = b.RideID
                    LEFT JOIN
                        tb_user p
                    ON
                        b.PassengerID = p.User_ID
                    GROUP BY
                        r.rideID, u.User_ID, u.Name, u.Email, u.PhoneNo, u.NicNo";
            
            $stmt = $conn->prepare($sql);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function AddRide() {
        try {
           
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
           
            $this->publishedDate = date('Y-m-d'); 
            $this->publishedTime = date('H:i:s');
    
            $query = "INSERT INTO tb_ride (
                          vehicleNo, vehicleModel, seats, airCondition, 
                          departurePoint, destinationPoint, date, seatCost, 
                          departureTime, destinationTime, Ridegender, 
                          route, preferences, publishedDate, publishedTime, driverID
                      ) 
                      VALUES (
                          :vehicleNo, :vehicleModel, :seats, :airCondition, 
                          :StartLocation, :EndLocation, :Date, :cost, 
                          :StartTime, :EndTime, :gender, 
                          :route, :preferences, :publishedDate, :publishedTime, :driverID
                      )";
            
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':vehicleNo', $this->vehicleNo);
            $stmt->bindValue(':vehicleModel', $this->vehicleModel); 
            $stmt->bindValue(':seats', $this->seats);
            $stmt->bindValue(':airCondition', $this->airCondition);
            $stmt->bindValue(':StartLocation', $this->StartLocation);
            $stmt->bindValue(':EndLocation', $this->EndLocation);
            $stmt->bindValue(':Date', $this->Date);
            $stmt->bindValue(':cost', $this->cost);
            $stmt->bindValue(':StartTime', $this->StartTime);
            $stmt->bindValue(':EndTime', $this->EndTime);
            $stmt->bindValue(':gender', $this->gender);
            //$stmt->bindValue(':vehicleImg', $this->vehicleImg);
            $stmt->bindValue(':route', $this->route);
            $stmt->bindValue(':preferences', $this->preferences);
            $stmt->bindValue(':publishedDate', $this->publishedDate);
            $stmt->bindValue(':publishedTime', $this->publishedTime);
            $stmt->bindValue(':driverID',$this->Driver_ID);
            
            $res = $stmt->execute();
            if($res)
            {
                $UserRole='driver';
                $query1 = "UPDATE tb_user SET userrole = :userrole WHERE User_ID = :userid"; 
                $stmt1 = $conn->prepare($query1);
                $stmt1->bindParam(':userrole', $UserRole);
                $stmt1->bindParam(':userid', $this->Driver_ID);
                $res = $stmt1->execute();
                return true;
            }
          
        } catch (PDOException $e) {
            error_log("addRide PDOException: " . $e->getMessage());
            return false;
        }
    }
    


    public function RequestRide($rideId, $userID, $seatsNo) {
        try {
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
            $query = "SELECT u.Email, u.Name, r.date, r.destinationPoint, r.departureTime, r.destinationTime, r.departurePoint 
                      FROM tb_user u 
                      JOIN tb_ride r ON u.User_ID = r.driverID 
                      WHERE r.rideID = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $rideId);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC); // fetch a single row
            
            if ($res) {
                $this->StartLocation = $res['departurePoint'];
                $this->EndLocation = $res['destinationPoint'];
                $Drivername = $res['Name'];
                $this->Date = $res['date'];
                $driverEmail = $res['Email'];
                $this->StartTime = $res['departureTime'];
                $this->EndTime = $res['destinationTime'];
    
                $query1 = "SELECT Name, Gender FROM tb_user WHERE User_ID = ?";
                $stmt1 = $con->prepare($query1);
                $stmt1->bindValue(1, $userID);
                $stmt1->execute();
                $user_res = $stmt1->fetch(PDO::FETCH_ASSOC); // fetch a single row
                
                if ($user_res) {
                    $username = $user_res['Name'];
                   
                    RideDetails::sentRequestmail($Drivername, $driverEmail, $username, $this->StartLocation, $this->EndLocation, $this->StartTime, $this->EndTime, $this->Date, $seatsNo);
                    return $driverEmail;
                }
            }
        } catch (PDOException $e) {
            error_log("requestRide PDOException: " . $e->getMessage());
            return false;
        }
    }
    
    
    public static function sentRequestmail($Drivername, $driverEmail, $username,  $StartLocation, $EndLocation, $StartTime, $EndTime, $Date,$seatsNo) {
        require __DIR__ . '/../mail/Exception.php';
        require __DIR__ . '/../mail/PHPMailer.php';
        require __DIR__ . '/../mail/SMTP.php';
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0; 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecoridecst@gmail.com';
            $mail->Password = 'frqg vgig bgmn uyxf';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('ecoridecst@gmail.com');
            $mail->addAddress($driverEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Request sent!';
            $message = "Dear " . $Drivername . "<br><br>";
            $message .= "<span style='color: green;'><b>A $username has requested $seatsNo seats for your ride.</b></span><br>";
            $message .= "<hr><br>";
            $message .= "Route: $StartLocation to $EndLocation<br>";
            $message .= "Start Time:$StartTime <br>";
            $message .= "Finish Time:$EndTime<br><br>";
            $message .= "Please confirm the booking at your earliest convenience.<br><br>";
            $message .= "<span style='font-weight: bold;'>Best regards,</span><br>";
            $message .= "ecoRide Admin<br>";
            $mail->Body = $message;

            $mail->send();

           
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        } 
    }
}
?>
