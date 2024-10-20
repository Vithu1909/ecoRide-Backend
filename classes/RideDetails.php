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
    private $carType;

    private $ridedistance;

    private $deductionID;

    private $rateID;
    
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

    public function setcarType($carType){$this->carType=$carType;}
    public function setRideDistance($ridedistance){$this->ridedistance=$ridedistance;}

    public function setDeductionId($deductionID) {
        $this->deductionID = $deductionID;
    }

    public function setrateID($rateID){
        $this->rateID=$rateID;
    }
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
                                ', status:', b.status,
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
                    WHERE
                    r.rideStatus = 'active'

                    GROUP BY
                        r.rideID, u.User_ID, u.Name, u.Email, u.PhoneNo, u.NicNo ";
                        
            
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

    public static function AdminDisplayRide() {
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
                                ', status:', b.status,
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
                        r.rideID, u.User_ID, u.Name, u.Email, u.PhoneNo, u.NicNo ";
                        
            
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
    
    $calculatedSeatCost = $this->calculateCostPerSeat($this->carType, $this->ridedistance, $this->seats);

            $query = "INSERT INTO tb_ride (
                          vehicleNo, vehicleModel, seats, airCondition,carType, 
                          departurePoint, destinationPoint, date, seatCost, 
                          departureTime, destinationTime, Ridegender, 
                          route, preferences, publishedDate, publishedTime, driverID, ridedistance
                      ) 
                      VALUES (
                          :vehicleNo, :vehicleModel, :seats, :airCondition,:carType, 
                          :StartLocation, :EndLocation, :Date, :cost, 
                          :StartTime, :EndTime, :gender, 
                          :route, :preferences, :publishedDate, :publishedTime, :driverID, :ridedistance
                      )";
            
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':vehicleNo', $this->vehicleNo);
            $stmt->bindValue(':vehicleModel', $this->vehicleModel); 
            $stmt->bindValue(':seats', $this->seats);
            $stmt->bindValue(':airCondition', $this->airCondition);
            $stmt->bindValue(':carType', $this->carType);
            $stmt->bindValue(':StartLocation', $this->StartLocation);
            $stmt->bindValue(':EndLocation', $this->EndLocation);
            $stmt->bindValue(':Date', $this->Date);
            $stmt->bindValue(':cost', $calculatedSeatCost); 
            $stmt->bindValue(':StartTime', $this->StartTime);
            $stmt->bindValue(':EndTime', $this->EndTime);
            $stmt->bindValue(':gender', $this->gender);
            //$stmt->bindValue(':vehicleImg', $this->vehicleImg);
            $stmt->bindValue(':route', $this->route);
            $stmt->bindValue(':preferences', $this->preferences);
            $stmt->bindValue(':publishedDate', $this->publishedDate);
            $stmt->bindValue(':publishedTime', $this->publishedTime);
            $stmt->bindValue(':driverID',$this->Driver_ID);
            $stmt->bindValue(':ridedistance', $this->ridedistance);
            
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


private function calculateCostPerSeat($carType, $distance, $seats) {

    switch ($carType) {
        case 'low-consumption':
            $costPerKm = 30.73;
            break;
        case 'medium-consumption':
            $costPerKm = 45.10;
            break;
        case 'high-consumption':
            $costPerKm = 60.86;
            break;
        case 'hybrid':
            $costPerKm = 30.20;
            break;
        default:
            $costPerKm = 0;
    }

    $totalCost = $costPerKm * $distance;
    $discountedCost = $totalCost * 0.75;
    $costPerSeat = $discountedCost / $seats;
    return round($costPerSeat, 2);
}




   

    public function RequestRide($rideId, $userID, $seatsNo, $distance,$fromtowhere) {
        try {
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
            
            // Query to get ride and driver details
            $query = "SELECT u.Email, u.Name, u.User_ID, r.date, r.destinationPoint, r.departureTime, r.destinationTime, r.departurePoint, r.carType
                      FROM tb_user u 
                      JOIN tb_ride r ON u.User_ID = r.driverID 
                      WHERE r.rideID = ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $rideId);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            if ($res) {
                $this->StartLocation = $res['departurePoint'];
                $this->EndLocation = $res['destinationPoint'];
                $driverId = $res['User_ID'];
                $Drivername = $res['Name'];
                $this->Date = $res['date'];
                $driverEmail = $res['Email'];
                $this->StartTime = $res['departureTime'];
                $this->EndTime = $res['destinationTime'];
                $carType = $res['carType']; 
                
                
                $query1 = "SELECT Name, Gender FROM tb_user WHERE User_ID = ?";
                $stmt1 = $con->prepare($query1);
                $stmt1->bindValue(1, $userID);
                $stmt1->execute();
                $user_res = $stmt1->fetch(PDO::FETCH_ASSOC); 
                
                if ($user_res) {
                    $username = $user_res['Name'];
                  
                    // $carType = $this->getCarTypeFromRide($rideId); 
                    // Calculate the total cost based on car type, distance, and seats requested
                    // $totalCost = $this->calculateTotalCost($carType, $distance, $seatsNo);
                    $totalCost = $this->calculateTotalCost($rideId, $distance, $seatsNo);
                    // Insert the booking into the database
                    $query2 = "INSERT INTO tb_booking (RideID, PassengerID, seats, driverId, totalCost, fromtowhere) VALUES (?, ?, ?, ?, ?,?)";
                    $pstmt2 = $con->prepare($query2);
                    $pstmt2->bindValue(1, $rideId);
                    $pstmt2->bindValue(2, $userID);
                    $pstmt2->bindValue(3, $seatsNo);
                    $pstmt2->bindValue(4, $driverId);
                    $pstmt2->bindValue(5, $totalCost);
                    $pstmt2->bindValue(6, $fromtowhere);
                    
                    if ($pstmt2->execute()) {
                        
                        RideDetails::sentRequestmail($Drivername, $driverEmail, $username, $this->StartLocation, $this->EndLocation, $this->StartTime, $this->EndTime, $this->Date, $seatsNo, $totalCost);
                        return $driverEmail;
                    }
                }
            }
        } catch (PDOException $e) {
            die("requestRide PDOException: " . $e->getMessage());
        }
    }

    private function calculateTotalCost($rideId, $distance, $seatsNo) {
        $dbcon = new DBconnector();
        $con = $dbcon->getConnection();

        $query = "SELECT seatCost, ridedistance FROM tb_ride WHERE rideID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $rideId);
        $stmt->execute();
        $rideDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$rideDetails) {
            throw new Exception("Ride details not found.");
        }

        $seatCost = (float) $rideDetails['seatCost'];
        $rideDistance = (float) $rideDetails['ridedistance'];

        if ($seatCost <= 0 || $rideDistance <= 0 || $distance <= 0 || $seatsNo <= 0) {
            throw new Exception("Please enter valid numbers for distance, seats, and ride details.");
        }
        $costPerKm = $seatCost / $rideDistance;
        $totalCost = $costPerKm * $distance * $seatsNo;
    
        return round($totalCost, 2);
    }
    
    public function AcceptBooking($Bookid){
        try{
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
    
            $query = "UPDATE tb_booking SET status=? WHERE BookingID=?";
            $status = 'accepted';
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, $status);
            $stmt->bindValue(2, $Bookid);
            
            if($stmt->execute()) {
                $query1 = "SELECT tb_user.*, tb_booking.* FROM tb_user JOIN tb_booking ON tb_user.User_ID = tb_booking.PassengerID WHERE tb_booking.BookingID = ?";
                $stmt1 = $con->prepare($query1);
                $stmt1->bindValue(1, $Bookid);
                $stmt1->execute();
                $user_res = $stmt1->fetch(PDO::FETCH_ASSOC);
                
                if($user_res) {
                    $UserEmail = $user_res["Email"];
                    $Username = $user_res["Name"];
                    $seatsno = $user_res["seats"];
                    $rideId = $user_res["RideID"];
                    $totalCost = $user_res["totalCost"]; 

                    $query2 = "SELECT tb_user.* FROM tb_user JOIN tb_booking ON tb_user.User_ID = tb_booking.driverId WHERE tb_booking.BookingID = ?";
                    $stmt2 = $con->prepare($query2);
                    $stmt2->bindValue(1, $Bookid);
                    $stmt2->execute();
                    $Driver_res = $stmt2->fetch(PDO::FETCH_ASSOC);
    
                    if($Driver_res) {
                        $drivername = $Driver_res["Name"];
                        $phone = $Driver_res["PhoneNo"];
                        $query4 = "SELECT BookingSeats, seats FROM tb_ride WHERE rideID=?";
                        $stmt4 = $con->prepare($query4);
                        $stmt4->bindValue(1, $rideId);
                        $stmt4->execute();
                        $res = $stmt4->fetch(PDO::FETCH_ASSOC);
    
                        if($res) {
                            $seactcount = $res["BookingSeats"];
                            //$totalseats = $res["seats"];
                            $newseats = $seactcount + $seatsno;
    
                            $query3 = "UPDATE tb_ride SET BookingSeats=? WHERE rideID=?";
                            $pstmt3 = $con->prepare($query3);
                            $pstmt3->bindValue(1, $newseats);
                            $pstmt3->bindValue(2, $rideId);
    
                            if($pstmt3->execute()) {
                                if(RideDetails::sentAcceptMail($UserEmail, $Username, $drivername, $phone, $totalCost)) {
                                    return "Request accepted successfully";
                                } else {
                                    return "Failed to send acceptance email";
                                }
                            } else {
                                return "Failed to update ride booking seats";
                            }
                        } else {
                            return "Failed to fetch ride details";
                        }
                    } else {
                        return "Failed to fetch driver details";
                    }
                } else {
                    return "Failed to fetch user details";
                }
            } else {
                return "Failed to update booking status";
            }
        } catch(PDOException $e) {
            return "requestRide PDOException: " . $e->getMessage();
        }
    }


    
    
    public static function sentRequestmail($Drivername, $driverEmail, $username,  $StartLocation, $EndLocation, $StartTime, $EndTime, $Date,$seatsNo,$totalCost) {
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
            $message .= "<span style='color: green;'><b>A $username has requested $seatsNo seats for your ride and total cost for their ride is:$totalCost.</b></span><br>";
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
    public static function sentAcceptMail($email, $Username, $drivername, $phone,$totalCost) {
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
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Booking Accepted';
            
            $message = "Dear " . $Username . ",<br><br>";
            $message .= "Your booking has been accepted by driver " . $drivername . ".<br>";
            $message .= "For further information, please contact the driver at " . $phone . ".<br><br>";
            $message .= "Best regards,<br>";
            $message .= "ecoRide Admin";
            
            $mail->Body = $message;
            $mail->send();
            
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    
    public function getCurrentRide($userID) {
        try {
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
    
            // Step 1: Get all rides where the user is a passenger or has a booking
            $queryBooking = "SELECT            
                                b.BookingID AS Bookid, 
                                r.rideID,
                                r.departurePoint, 
                                r.destinationPoint, 
                                r.date, 
                                r.departureTime, 
                                r.destinationTime, 
                                r.vehicleModel, 
                                r.seatCost, 
                                (r.seats - r.BookingSeats) AS availableSeats,  
                                u.Name AS driverName, 
                                u.PhoneNo AS driverContact,                     
                                b.status AS bookingStatus,
                                b.totalCost AS passengercost,
                                b.fromtowhere AS place
                            FROM 
                                tb_booking b
                            JOIN 
                                tb_ride r ON b.RideID = r.rideID
                            JOIN 
                                tb_user u ON r.driverID = u.User_ID
                            WHERE 
                                b.PassengerID = ? OR b.driverId = ?
                            GROUP BY
                                r.rideID, b.BookingID";
            
            $stmt = $con->prepare($queryBooking);
            $stmt->bindValue(1, $userID);
            $stmt->bindValue(2, $userID);
            $stmt->execute();
            $rideDetailsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Step 2: Get all rides where the user is a driver, but not present in tb_booking
            $queryRide = "SELECT 
                             NULL AS Bookid, 
                             r.rideID,
                             r.departurePoint, 
                             r.destinationPoint, 
                             r.date, 
                             r.departureTime, 
                             r.destinationTime, 
                             r.vehicleModel, 
                             r.seatCost, 
                             (r.seats - COALESCE(SUM(b.seats), 0)) AS availableSeats, 
                             u.Name AS driverName, 
                             u.PhoneNo AS driverContact, 
                             'driver' AS bookingStatus,
                             b.totalCost AS passengercost,
                             b.fromtowhere AS place
                          FROM 
                             tb_ride r
                          LEFT JOIN 
                             tb_booking b ON r.rideID = b.RideID
                          JOIN 
                             tb_user u ON r.driverID = u.User_ID
                          WHERE 
                             r.driverID = ? AND b.BookingID IS NULL
                          GROUP BY 
                             r.rideID";
    
            $stmt = $con->prepare($queryRide);
            $stmt->bindValue(1, $userID);
            $stmt->execute();
            $driverRides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Combine the two results into a single list
            $rideDetailsList = array_merge($rideDetailsList, $driverRides);
    
            $output = [];
    
            foreach ($rideDetailsList as $rideDetails) {
                // Initialize arrays
                $requests = [];
                $acceptedPassengers = [];
    
                // If the user is a driver, fetch ride requests and accepted passengers
                if (!empty($rideDetails['driverName'])) {
                    $queryRequests = "SELECT 
                                        u.Name AS passengerName, 
                                        u.PhoneNo AS passengerContact, 
                                        b.seats AS seatsRequested,
                                        b.BookingID AS requestID,
                                        b.totalCost AS passengercost,
                                        b.fromtowhere AS place
                                      FROM 
                                        tb_booking b
                                      JOIN 
                                        tb_user u ON b.PassengerID = u.User_ID
                                      WHERE 
                                        b.RideID = ? AND b.status = 'waiting'";
    
                    $stmtRequests = $con->prepare($queryRequests);
                    $stmtRequests->bindValue(1, $rideDetails['rideID']);
                    $stmtRequests->execute();
                    $requests = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
    
                    // Query to fetch accepted passengers for each ride
                    $queryAcceptedPassengers = "SELECT 
                                                  u.Name AS passengerName, 
                                                  u.PhoneNo AS passengerContact, 
                                                  b.seats AS seatsRequested, 
                                                  b.totalCost AS passengercost,
                                                  b.fromtowhere AS place
                                                FROM 
                                                  tb_booking b
                                                JOIN 
                                                  tb_user u ON b.PassengerID = u.User_ID
                                                WHERE 
                                                  b.RideID = ? AND b.status = 'accepted'";
    
                    $stmtAcceptedPassengers = $con->prepare($queryAcceptedPassengers);
                    $stmtAcceptedPassengers->bindValue(1, $rideDetails['rideID']);
                    $stmtAcceptedPassengers->execute();
                    $acceptedPassengers = $stmtAcceptedPassengers->fetchAll(PDO::FETCH_ASSOC);
                }
    
                // Build the final output array for each ride
                $output[] = [
                    'Bookid' => $rideDetails['Bookid'],
                    'rideID' => $rideDetails['rideID'],
                    'departurePoint' => $rideDetails['departurePoint'],
                    'destinationPoint' => $rideDetails['destinationPoint'],
                    'date' => $rideDetails['date'],
                    'departureTime' => $rideDetails['departureTime'],
                    'destinationTime' => $rideDetails['destinationTime'],
                    'vehicleModel' => $rideDetails['vehicleModel'],
                    'seatCost' => $rideDetails['seatCost'],
                    'status' => $rideDetails['bookingStatus'],
                    'availableSeats' => $rideDetails['availableSeats'],
                    'passengercost' => $rideDetails['passengercost'],
                    'place'=>$rideDetails['place'],
                    'driver' => [
                        'name' => $rideDetails['driverName'],
                        'contact' => $rideDetails['driverContact']
                    ],
                    'requests' => $requests,
                    'acceptedPassengers' => $acceptedPassengers
                ];
            }
    
            return $output;
        } catch (PDOException $e) {
            error_log("getCurrentRide PDOException: " . $e->getMessage());
            return false;
        }
    }
    
    
   
    
public function rejectBooking($Bookid){
    try{
        $dbcon = new DBconnector();
        $con = $dbcon->getConnection();

        // First, update the booking status to 'rejected'
        $query = "UPDATE tb_booking SET status=? WHERE BookingID=?";
        $status = 'rejected';
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $status);
        $stmt->bindValue(2, $Bookid);

        if($stmt->execute()) {
            // After status is updated to 'rejected', fetch user and driver details
            $query1 = "SELECT tb_user.*, tb_booking.* FROM tb_user JOIN tb_booking ON tb_user.User_ID = tb_booking.PassengerID WHERE tb_booking.BookingID = ?";
            $stmt1 = $con->prepare($query1);
            $stmt1->bindValue(1, $Bookid);
            $stmt1->execute();
            $user_res = $stmt1->fetch(PDO::FETCH_ASSOC);

            if($user_res) {
                $UserEmail = $user_res["Email"];
                $Username = $user_res["Name"];
                $seatsno = $user_res["seats"];
                $rideId = $user_res["RideID"];

                $query2 = "SELECT tb_user.* FROM tb_user JOIN tb_booking ON tb_user.User_ID = tb_booking.driverId WHERE tb_booking.BookingID = ?";
                $stmt2 = $con->prepare($query2);
                $stmt2->bindValue(1, $Bookid);
                $stmt2->execute();
                $Driver_res = $stmt2->fetch(PDO::FETCH_ASSOC);

                if($Driver_res) {
                    $drivername = $Driver_res["Name"];
                    $phone = $Driver_res["PhoneNo"];
                    
                    // Send rejection email
                    if(RideDetails::sentRejectMail($UserEmail, $Username, $drivername, $phone)) {
                        
                        // Delete the booking record after the status is rejected and email is sent
                        $deleteQuery = "DELETE FROM tb_booking WHERE BookingID = ?";
                        $stmtDelete = $con->prepare($deleteQuery);
                        $stmtDelete->bindValue(1, $Bookid);
                        
                        if($stmtDelete->execute()) {
                            return "Request rejected and booking deleted successfully";
                        } else {
                            return "Failed to delete booking data";
                        }
                    } else {
                        return "Failed to send rejection email";
                    }
                } else {
                    return "Failed to fetch driver details";
                }
            } else {
                return "Failed to fetch user details";
            }
        } else {
            return "Failed to update booking status";
        }
    } catch(PDOException $e) {
        return "rejectBooking PDOException: " . $e->getMessage();
    }
}


    public static function sentRejectMail($email, $Username, $drivername, $phone) {
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
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Booking Rejected';
    
            $message = "Dear " . $Username . ",<br><br>";
            $message .= "Your booking request has been rejected by driver " . $drivername . ".<br>";
            //$message .= "For further information, please contact the driver at " . $phone . ".<br><br>";
            $message .= "Best regards,<br>";
            $message .= "ecoRide Admin";
    
            $mail->Body = $message;
            $mail->send();
    
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
    public function deleteRide($rideId, $rating) {
    try {
        $dbcon = new DBconnector();
        $con = $dbcon->getConnection();

        // Start a transaction
        $con->beginTransaction();

        // Delete from tb_rating using rateID
        $deleteRatingQuery = "DELETE FROM tb_rating WHERE rateID = ?";
        $stmtRating = $con->prepare($deleteRatingQuery);
        $stmtRating->bindParam(1, $rating, PDO::PARAM_INT);
        $stmtRating->execute();

        // Get all bookings related to the ride
        $getBookingsQuery = "SELECT BookingID FROM tb_booking WHERE RideID = ?";
        $stmtGetBookings = $con->prepare($getBookingsQuery);
        $stmtGetBookings->bindParam(1, $rideId, PDO::PARAM_INT);
        $stmtGetBookings->execute();
        $bookings = $stmtGetBookings->fetchAll(PDO::FETCH_ASSOC);

        // Loop through each booking and delete associated deductions
        foreach ($bookings as $booking) {
            $bookingID = $booking['BookingID'];

            // Delete from tb_deductions where BookingID matches
            $deleteDeductionQuery = "DELETE FROM tb_deductions WHERE BookingID = ?";
            $stmtDeductions = $con->prepare($deleteDeductionQuery);
            $stmtDeductions->bindParam(1, $bookingID, PDO::PARAM_INT);
            $stmtDeductions->execute();
        }

        // Delete from tb_booking using rideID
        $deleteBookingsQuery = "DELETE FROM tb_booking WHERE RideID = ?";
        $stmtBookings = $con->prepare($deleteBookingsQuery);
        $stmtBookings->bindParam(1, $rideId, PDO::PARAM_INT);

        if (!$stmtBookings->execute()) {
            $con->rollBack();
            return "Failed to delete bookings";
        }

        // Delete from tb_ride using rideID
        $deleteRideQuery = "DELETE FROM tb_ride WHERE rideID = ?";
        $stmtRide = $con->prepare($deleteRideQuery);
        $stmtRide->bindParam(1, $rideId, PDO::PARAM_INT);

        if ($stmtRide->execute()) {
            // Commit transaction
            $con->commit();
            return "Ride and associated bookings and deductions deleted successfully";
        } else {
            // Rollback transaction if deletion failed
            $con->rollBack();
            return "Failed to delete the ride";
        }
    } catch (PDOException $e) {
        // Rollback transaction in case of any exception
        $con->rollBack();
        return "deleteRide PDOException: " . $e->getMessage();
    }
}

    
    

  
          
        public function editRide($rideID, $driverID, $departureTime, $destinationTime, $availableSeats) {
            try {
                $dbcon = new DBconnector();
                $con = $dbcon->getConnection();
        
    
                $queryCheckDriver = "SELECT * FROM tb_ride WHERE rideID = ? ";
                $stmtCheckDriver = $con->prepare($queryCheckDriver);
                $stmtCheckDriver->bindValue(1, $rideID);
               // $stmtCheckDriver->bindValue(2, $driverID);
                $stmtCheckDriver->execute();
                $result = $stmtCheckDriver->fetch(PDO::FETCH_ASSOC);
                
                if (!$result) {
    
                    return ['status' => 0, 'message' => "editRide: Ride or driver not found for rideID: $rideID"];
                }
    
                $queryUpdateRide = "UPDATE tb_ride 
                                    SET departureTime = ?, destinationTime = ?, seats = ? 
                                    WHERE rideID = ?";
                
                $stmtUpdateRide = $con->prepare($queryUpdateRide);
    
                $stmtUpdateRide->bindValue(1, $departureTime);
                $stmtUpdateRide->bindValue(2, $destinationTime);
                $stmtUpdateRide->bindValue(3, $availableSeats);
                $stmtUpdateRide->bindValue(4, $rideID);
                //$stmtUpdateRide->bindValue(5, $driverID);
        
                if ($stmtUpdateRide->execute()) {
    
                    $queryGetAcceptedPassengers = "SELECT tb_user.Email, tb_user.Name 
                                                   FROM tb_user 
                                                   JOIN tb_booking ON tb_user.User_ID = tb_booking.PassengerID 
                                                   WHERE tb_booking.RideID = ? AND tb_booking.status = 'accepted'";
                    $stmtGetAcceptedPassengers = $con->prepare($queryGetAcceptedPassengers);
                    $stmtGetAcceptedPassengers->bindValue(1, $rideID);
                    $stmtGetAcceptedPassengers->execute();
        
                    $acceptedPassengers = $stmtGetAcceptedPassengers->fetchAll(PDO::FETCH_ASSOC);
        
                    foreach ($acceptedPassengers as $passenger) {
                        $UserEmail = $passenger["Email"];
                        $Username = $passenger["Name"];
                        
                        $this->sendRideUpdateMail($UserEmail, $Username, $departureTime, $destinationTime);
                    }
        
                    return ['status' => 1, 'message' => 'Ride updated and passengers notified successfully'];
                } else {
                    return ['status' => 0, 'message' => 'Failed to update the ride'];
                }
        
            } catch (PDOException $e) {
                error_log("editRide PDOException: " . $e->getMessage());
                return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
            }
        }
        
        public function sendRideUpdateMail($email, $username, $newDepartureTime, $newDestinationTime) {
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
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Ride Details Updated';
        
                $message = "Dear " . $username . ",<br><br>";
                $message .= "The ride you booked has been updated with new details:<br>";
               // $message .= "New Date: " . $newDate . "<br>";
                $message .= "New Departure Time: " . $newDepartureTime . "<br>";
                $message .= "New Destination Time: " . $newDestinationTime . "<br><br>";
                $message .= "Please be prepared accordingly.<br><br>";
                $message .= "Best regards,<br>";
                $message .= "ecoRide Admin";
        
                $mail->Body = $message;
        
                $mail->send();
        
                return true;
            } catch (Exception $e) {
                error_log("sendRideUpdateMail Mailer Error: " . $mail->ErrorInfo);
                return false;
            }
        }

        public function addrating($bookid, $rating) {
            try {
                $dbcon = new DBconnector();
                $conn = $dbcon->getConnection();
                
                // Select the driverID based on booking ID
                $selectDriverID = "SELECT driverId FROM tb_booking WHERE BookingID = ?";
                $stmt = $conn->prepare($selectDriverID);
                $stmt->bindValue(1, $bookid);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // If driverId is found, insert the rating
                if ($result) {
                    $driverId = $result['driverId'];
        
                    // Insert the new rating for the driver
                    $query = "INSERT INTO tb_rating (driverID, rating) VALUES (?, ?)";
                    $insertStmt = $conn->prepare($query);
                    $insertStmt->bindValue(1, $driverId);
                    $insertStmt->bindValue(2, $rating);
                    $res = $insertStmt->execute();
                    
                    if ($res) {
                        // Fetch all ratings for the driver and calculate the average
                        $getAllRatings = "SELECT AVG(rating) as averageRating FROM tb_rating WHERE driverID = ?";
                        $stmtAllRatings = $conn->prepare($getAllRatings);
                        $stmtAllRatings->bindValue(1, $driverId);
                        $stmtAllRatings->execute();
                        $ratingResult = $stmtAllRatings->fetch(PDO::FETCH_ASSOC);
                        
                        // If average rating is calculated, update it in the tb_user table
                        if ($ratingResult) {
                            $averageRating = $ratingResult['averageRating'];
        
                            // Update the rating in tb_user table
                            $updateRatingQuery = "UPDATE tb_user SET rating = ? WHERE User_ID = ?";
                            $stmtUpdateRating = $conn->prepare($updateRatingQuery);
                            $stmtUpdateRating->bindValue(1, $averageRating);
                            $stmtUpdateRating->bindValue(2, $driverId);
                            $updateRes = $stmtUpdateRating->execute();
                            
                            // Check if the update was successful
                            if ($updateRes) {
                                // Now, update the ride status in tb_booking to 'finished'
                                $updateBookingStatusQuery = "UPDATE tb_booking SET status = ? WHERE BookingID = ?";
                                $stmtUpdateBookingStatus = $conn->prepare($updateBookingStatusQuery);
                                $stmtUpdateBookingStatus->bindValue(1, 'finish');
                                $stmtUpdateBookingStatus->bindValue(2, $bookid);
                                $stmtUpdateBookingStatus->execute();
                                
                                if ($stmtUpdateBookingStatus->rowCount() > 0) {
                                    // Successfully updated booking status, ride should no longer appear in the current rides
                                    return array(
                                        "message" => "Rating submitted, ride marked as finished, and user profile updated successfully.",
                                        "averageRating" => $averageRating
                                    );
                                } else {
                                    return "Rating submitted, but failed to update booking status.";
                                }
                            } else {
                                return "Failed to update average rating in user profile.";
                            }
                        } else {
                            return "Failed to calculate average rating.";
                        }
                    } else {
                        return "Failed to insert rating.";
                    }
                } else {
                    return "Booking ID not found.";
                }
                
            } catch (PDOException $e) {
                error_log("addrating PDOException: " . $e->getMessage());
                return false;
            }
        }
        
       

        public function cancelBooking($Bookid) { 
            try {
                $dbcon = new DBconnector();
                $con = $dbcon->getConnection();
        
              
                $con->beginTransaction();
        
                $query = "UPDATE tb_booking SET status=? WHERE BookingID=?";
                $status = 'cancel';
                $stmt = $con->prepare($query);
                $stmt->bindValue(1, $status);
                $stmt->bindValue(2, $Bookid);
        
                if ($stmt->execute()) {
                    $query1 = "SELECT tb_user.*, tb_booking.* FROM tb_user 
                               JOIN tb_booking ON tb_user.User_ID = tb_booking.PassengerID 
                               WHERE tb_booking.BookingID = ?";
                    $stmt1 = $con->prepare($query1);
                    $stmt1->bindValue(1, $Bookid);
                    $stmt1->execute();
                    $user_res = $stmt1->fetch(PDO::FETCH_ASSOC);
        
                    if ($user_res) {
                        $Username = $user_res["Name"];
                        $rideId = $user_res["RideID"];
                        $seatsToRelease = (int)$user_res["seats"];
                        $passengerStatus = $user_res["status"]; 
        
                        $query2 = "SELECT Seats, BookingSeats FROM tb_ride WHERE RideID = ?";
                        $stmt2 = $con->prepare($query2);
                        $stmt2->bindValue(1, $rideId);
                        $stmt2->execute();
                        $ride_res = $stmt2->fetch(PDO::FETCH_ASSOC);
        
                        if ($ride_res) {
                            $currentAvailableSeats = (int)$ride_res["Seats"];
                            $currentBookingSeats = (int)$ride_res["BookingSeats"];
        
                            if ($passengerStatus === 'waiting') {
                                $updatedBookingSeats = $currentBookingSeats;
                            } else if ($passengerStatus === 'accepted') {
                                $updatedBookingSeats = $currentBookingSeats - $seatsToRelease;
                            }
        
                          
                            $query3 = "UPDATE tb_ride SET BookingSeats=? WHERE RideID=?";
                            $stmt3 = $con->prepare($query3);
                            $stmt3->bindValue(1, $updatedBookingSeats); 
                            $stmt3->bindValue(2, $rideId);
        
                            if ($stmt3->execute()) {
        
                                
                                $query4 = "SELECT tb_user.* FROM tb_user 
                                           JOIN tb_booking ON tb_user.User_ID = tb_booking.driverId 
                                           WHERE tb_booking.BookingID = ?";
                                $stmt4 = $con->prepare($query4);
                                $stmt4->bindValue(1, $Bookid);
                                $stmt4->execute();
                                $Driver_res = $stmt4->fetch(PDO::FETCH_ASSOC);
        
                                if ($Driver_res) {
                                    $driverEmail = $Driver_res["Email"];
                                    $drivername = $Driver_res["Name"];
        
                                    if (RideDetails::sentCancelMail($driverEmail, $drivername, $Username)) {
        
                                        $query5 = "DELETE FROM tb_deductions WHERE BookingID = ?";
                                        $stmt5 = $con->prepare($query5);
                                        $stmt5->bindValue(1, $Bookid);
        
                                        if ($stmt5->execute()) {
                     
                                            $query6 = "DELETE FROM tb_booking WHERE BookingID = ?";
                                            $stmt6 = $con->prepare($query6);
                                            $stmt6->bindValue(1, $Bookid);
        
                                            if ($stmt6->execute()) {
                                            
                                                $con->commit();
                                                return [
                                                    "message" => "Booking cancelled, booking and deduction records removed, and driver notified successfully",
                                                    "availableSeats" => $currentAvailableSeats,
                                                    "updatedBookingSeats" => $updatedBookingSeats  
                                                ];
                                            } else {
                                                error_log("Failed to remove booking record.");
                                                $con->rollBack();
                                                return "Failed to remove booking record";
                                            }
                                        } else {
                                            error_log("Failed to remove deduction record.");
                                            $con->rollBack();
                                            return "Failed to remove deduction record";
                                        }
                                    } else {
                                        error_log("Failed to send cancellation email to driver.");
                                        $con->rollBack();
                                        return "Failed to send cancellation email to driver";
                                    }
                                } else {
                                    error_log("Failed to fetch driver details.");
                                    return "Failed to fetch driver details";
                                }
                            } else {
                                error_log("Failed to update booking seats for the ride.");
                                $con->rollBack();
                                return "Failed to update booking seats for the ride";
                            }
                        } else {
                            error_log("Failed to fetch ride details.");
                            return "Failed to fetch ride details";
                        }
                    } else {
                        error_log("Failed to fetch user details.");
                        return "Failed to fetch user details";
                    }
                } else {
                    error_log("Failed to update booking status.");
                    return "Failed to update booking status";
                }
            } catch (PDOException $e) {
                error_log("cancelBooking PDOException: " . $e->getMessage());
                $con->rollBack();
                return "cancelBooking PDOException: " . $e->getMessage();
            }
        }
        
        


        public static function sentCancelMail($driverEmail, $drivername, $Username) {
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
                $mail->Subject = 'Booking Cancelled';
        
                $message = "Dear " . $drivername . ",<br><br>";
                $message .= "The passenger " . $Username . " has cancelled their booking.<br>";
                $message .= "Please check your dashboard for updates.<br><br>";
                $message .= "Best regards,<br>";
                $message .= "ecoRide Admin";
        
                $mail->Body = $message;
                $mail->send();
        
                return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                return false;
            }
        }

        

        public function finishRide($userID) {
                try {
                    $dbcon = new DBconnector();
                    $con = $dbcon->getConnection();
            
                    error_log("Received userID (driverID): " . $userID);
            
                    
                    $queryUpdate = "UPDATE tb_ride SET rideStatus = ? WHERE driverID = ?";
                    $status = 'finished';
                    $stmt = $con->prepare($queryUpdate);
                    $stmt->bindValue(1, $status);
                    // $stmt->bindValue(2, $rideID);
                    $stmt->bindValue(2, $userID);
                    $stmt->execute();
            
                    if ($stmt->rowCount() > 0) {

                        $queryUpdatePassengers = "UPDATE tb_booking SET status = ? WHERE driverid=?";
                        $Status='finished';
                        $stmtPassengers = $con->prepare($queryUpdatePassengers);
                        $stmtPassengers->bindValue(1, $Status);
                        $stmtPassengers->bindValue(2, $userID);
                        $stmtPassengers->execute();
            
                        return true;
                    } else {
                        error_log("No matching ride found or not authorized driverID: $userID");
                        return false;
                    }
                } catch (PDOException $e) {
                    error_log("finishRide PDOException: " . $e->getMessage());
                    return false;
                }
            }


           
            public function deductAmountAndCalculateRevenue($bookingId) {
                try {
                    $dbcon = new DBconnector();
                    $conn = $dbcon->getConnection();
                    
                    // Start a transaction to ensure both operations are atomic
                    $conn->beginTransaction();
            
                    // Fetch the driver's cost from the booking record
                    $selectCostQuery = "SELECT driverId, totalCost FROM tb_booking WHERE BookingID = ?";
                    $stmt = $conn->prepare($selectCostQuery);
                    $stmt->bindValue(1, $bookingId);
                    $stmt->execute();
                    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
                    if ($booking) {
                        $driverId = $booking['driverId'];
                        $passengercost = $booking['totalCost'];
                        
                        // Calculate the deduction (10% of passengercost)
                        $deduction = $passengercost * 0.1;
                        
                        // Step 1: Update the booking status to 'accepted'
                        $updateStatusQuery = "UPDATE tb_booking SET status = 'accepted' WHERE BookingID = ?";
                        $updateStmt = $conn->prepare($updateStatusQuery);
                        $updateStmt->bindValue(1, $bookingId);
                        $updateStmt->execute();
            
                        // Step 2: Insert the deduction into the tb_deductions table
                        $insertDeductionQuery = "INSERT INTO tb_deductions (driverID, BookingID, deductionAmount, deductionDate) 
                                                 VALUES (?, ?, ?, NOW())";
                        $insertStmt = $conn->prepare($insertDeductionQuery);
                        $insertStmt->bindValue(1, $driverId);
                        $insertStmt->bindValue(2, $bookingId);
                        $insertStmt->bindValue(3, $deduction);
                        $deductionResult = $insertStmt->execute();
            
                        // Commit the transaction if both operations are successful
                        if ($updateStmt->rowCount() > 0 && $deductionResult) {
                            $conn->commit();
                            return array(
                                "message" => "Deduction of Rs. $deduction has been applied to the booking and the status has been updated to accepted."
                            );
                        } else {
                            // Rollback if something went wrong
                            $conn->rollBack();
                            return "Failed to apply deduction or update booking status.";
                        }
                    } else {
                        return "Booking ID not found.";
                    }
                } catch (PDOException $e) {
                    // Rollback in case of error
                    $conn->rollBack();
                    error_log("deductAmountAndCalculateRevenue PDOException: " . $e->getMessage());
                    return false;
                }
            }

  

    public function AddRidePayment($cardData, $driverID) {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
          
            $paymentDate = date('Y-m-d H:i:s');
            
            $hashedCVV = password_hash($cardData['cardCVV'], PASSWORD_DEFAULT);

            $query = "INSERT INTO tb_payment (
                          driverID, cardName, cardNumber, cardExpiryDate, cardCVV, paymentDate
                      ) 
                      VALUES (
                          :driverID, :cardName, :cardNumber, :cardExpiryDate, :cardCVV, :paymentDate
                      )";
           
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':driverID', $driverID);
            $stmt->bindValue(':cardName', $cardData['cardName']);
            $stmt->bindValue(':cardNumber', $cardData['cardNumber']);
            $stmt->bindValue(':cardExpiryDate', $cardData['cardExpiryDate']);
            $stmt->bindValue(':cardCVV', $hashedCVV);
            $stmt->bindValue(':paymentDate', $paymentDate);
            
           
            $res = $stmt->execute();
            
        
            if ($res) {
              
                return true;
            } else {
                return false;
            }
            
        } catch (PDOException $e) {
            
            error_log("AddRidePayment PDOException: " . $e->getMessage());
            return false;
        }
    }
}

