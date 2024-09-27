<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RideDetails {
    private $rideID;
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

    public function __construct($rideID, $Driver_ID, $Passanger_ID, $StartLocation, $EndLocation, $StartTime, $EndTime, $vehicleNo, $vehicleModel, $seats, $airCondition, $Date, $cost, $gender, $vehicleImg, $route, $preferences, $publishedDate, $publishedTime) {
        $this->rideID = $rideID;
        $this->Driver_ID = $Driver_ID;
        $this->Passanger_ID = $Passanger_ID;
        $this->StartLocation = $StartLocation;
        $this->EndLocation = $EndLocation;
        $this->StartTime = $StartTime;
        $this->EndTime = $EndTime;
        $this->vehicleNo = $vehicleNo;
        $this->vehicleModel = $vehicleModel;
        $this->seats = $seats;
        $this->airCondition = $airCondition;
        $this->Date = $Date;
        $this->cost = $cost;
        $this->gender = $gender;
        $this->vehicleImg = $vehicleImg;
        $this->route = $route;
        $this->preferences = $preferences;
        $this->publishedDate = $publishedDate;
        $this->publishedTime = $publishedTime;
    }

   
    
    public function setrideID($rideID) { $this->rideID = $rideID; }
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
            $query = "SELECT u.Email, u.Name,u.User_ID, r.date, r.destinationPoint, r.departureTime, r.destinationTime, r.departurePoint 
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
                $driverId=$res['User_ID'];
                $Drivername = $res['Name'];
                $this->Date = $res['date'];
                $driverEmail = $res['Email'];
                $this->StartTime = $res['departureTime'];
                $this->EndTime = $res['destinationTime'];
    
                $query1 = "SELECT Name, Gender FROM tb_user WHERE User_ID = ?";
                $stmt1 = $con->prepare($query1);
                $stmt1->bindValue(1, $userID);
                $stmt1->execute();
                $user_res = $stmt1->fetch(PDO::FETCH_ASSOC); 
                
                if ($user_res) {
                    $username = $user_res['Name'];
                    $query2 ="INSERT INTO tb_booking (RideID,PassengerID,seats,driverId) VALUES(?,?,?,?)";
                    $pstmt2=$con->prepare($query2);
                    $pstmt2->bindValue(1,$rideId);
                    $pstmt2->bindValue(2,$userID);
                    $pstmt2->bindValue(3,$seatsNo);
                    $pstmt2->bindValue(4,$driverId);
                    if($pstmt2->execute())
                    {
                        RideDetails::sentRequestmail($Drivername, $driverEmail, $username, $this->StartLocation, $this->EndLocation, $this->StartTime, $this->EndTime, $this->Date, $seatsNo);
                        return $driverEmail;

                    }
                   
                    
                }
            }
        } catch (PDOException $e) {
            die("requestRide PDOException: " . $e->getMessage());
           
        }
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
                                if(RideDetails::sentAcceptMail($UserEmail, $Username, $drivername, $phone)) {
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
    public static function sentAcceptMail($email, $Username, $drivername, $phone) {
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

    // public function getCurrentRide($userID) {
    //     try {
    //         $dbcon = new DBconnector();
    //         $con = $dbcon->getConnection();
    //         $query1="SELECT  * FROM tb_booking WHERE driverId=?";
    //         $pstmt=$con->prepare($query1);
    //         $pstmt->bindValue(1,$userID);
            
    
    //         // Query to fetch all ride requests made by the passenger
    //         $query = "SELECT 
    //                     b.BookingID AS Bookid, 
    //                     r.departurePoint, 
    //                     r.destinationPoint, 
    //                     r.date, 
    //                     r.departureTime, 
    //                     r.destinationTime, 
    //                     r.vehicleModel, 
    //                     r.seatCost, 
    //                     b.status, 
    //                     (r.seats - r.BookingSeats) AS availableSeats, 
    //                     u.Name AS driverName, 
    //                     u.PhoneNo AS driverContact
    //                   FROM 
    //                     tb_booking b
    //                   JOIN 
    //                     tb_ride r ON b.RideID = r.rideID
    //                   JOIN 
    //                     tb_user u ON r.driverID = u.User_ID
    //                   WHERE 
    //                     b.PassengerID = ? OR b.driverId = ?";
    
    //         $stmt = $con->prepare($query);
    //         $stmt->bindValue(1, $userID);
    //         $stmt->bindValue(2, $userID);
    //         $stmt->execute();
    //         $rideDetailsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //         $output = [];
    
    //         foreach ($rideDetailsList as $rideDetails) {
    //             // Query to fetch ride requests for each ride
    //             $queryRequests = "SELECT 
    //                                 u.Name AS passengerName, 
    //                                 u.PhoneNo AS passengerContact, 
    //                                 b.seats AS seatsRequested
    //                               FROM 
    //                                 tb_booking b
    //                               JOIN 
    //                                 tb_user u ON b.PassengerID = u.User_ID
    //                               WHERE 
    //                                 b.BookingID = ? AND b.status = 'waiting'";
    
    //             $stmtRequests = $con->prepare($queryRequests);
    //             $stmtRequests->bindValue(1, $rideDetails['Bookid']);
    //             $stmtRequests->execute();
    //             $requests = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
    
    //             // Query to fetch accepted passengers for each ride
    //             $queryAcceptedPassengers = "SELECT 
    //                                           u.Name AS passengerName, 
    //                                           u.PhoneNo AS passengerContact, 
    //                                           b.seats AS seatsRequested
    //                                         FROM 
    //                                           tb_booking b
    //                                         JOIN 
    //                                           tb_user u ON b.PassengerID = u.User_ID
    //                                         WHERE 
    //                                           b.BookingID = ? AND b.status = 'accepted'";
    
    //             $stmtAcceptedPassengers = $con->prepare($queryAcceptedPassengers);
    //             $stmtAcceptedPassengers->bindValue(1, $rideDetails['Bookid']);
    //             $stmtAcceptedPassengers->execute();
    //             $acceptedPassengers = $stmtAcceptedPassengers->fetchAll(PDO::FETCH_ASSOC);
    
    //             // Build the final output array for each ride
    //             $output[] = [
    //                 'Bookid' => $rideDetails['Bookid'],
    //                 'departurePoint' => $rideDetails['departurePoint'],
    //                 'destinationPoint' => $rideDetails['destinationPoint'],
    //                 'date' => $rideDetails['date'],
    //                 'departureTime' => $rideDetails['departureTime'],
    //                 'destinationTime' => $rideDetails['destinationTime'],
    //                 'vehicleModel' => $rideDetails['vehicleModel'],
    //                 'seatCost' => $rideDetails['seatCost'],
    //                 'status' => $rideDetails['status'],
    //                 'availableSeats' => $rideDetails['availableSeats'],
    //                 'driver' => [
    //                     'name' => $rideDetails['driverName'],
    //                     'contact' => $rideDetails['driverContact']
    //                 ],
    //                 'requests' => $requests,
    //                 'acceptedPassengers' => $acceptedPassengers
    //             ];
    //         }
    
    //         return $output;
    //     } catch (PDOException $e) {
    //         error_log("getCurrentRide PDOException: " . $e->getMessage());
    //         return false;
    //     }
    // }
    
    // public function getCurrentRide($userID) {
    //     try {
    //         $dbcon = new DBconnector();
    //         $con = $dbcon->getConnection();
    
    //         // Query to fetch all rides associated with the passenger or driver
    //         $query = "SELECT            
    //          b.BookingID AS Bookid, 
    //                     r.rideID,
    //                     r.departurePoint, 
    //                     r.destinationPoint, 
    //                     r.date, 
    //                     r.departureTime, 
    //                     r.destinationTime, 
    //                     r.vehicleModel, 
    //                     r.seatCost, 
    //                     (r.seats - r.BookingSeats) AS availableSeats,  
    //                     u.Name AS driverName, 
    //                     u.PhoneNo AS driverContact,                     
    //                     b.status AS bookingStatus
    //                   FROM 
    //                     tb_ride r
    //                   LEFT JOIN 
    //                     tb_booking b ON r.rideID = b.RideID AND b.PassengerID = ?
    //                   JOIN 
    //                     tb_user u ON r.driverID = u.User_ID
    //                   WHERE 
    //                     r.driverID = ? OR b.PassengerID = ?";
    //                 // --   GROUP BY
    //                 // --     r.rideID, b.BookingID";
    
    //         $stmt = $con->prepare($query);
    //         $stmt->bindValue(1, $userID);
    //         $stmt->bindValue(2, $userID);
    //         $stmt->bindValue(3, $userID);
    //         $stmt->execute();
    //         $rideDetailsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //         $output = [];
    
    //         foreach ($rideDetailsList as $rideDetails) {
    //             // Initialize arrays
    //             $requests = [];
    //             $acceptedPassengers = [];
                
    //             // If the user is a driver, fetch ride requests and accepted passengers
    //             if (!empty($rideDetails['driverName'])) {
    //                 $queryRequests = "SELECT 
    //                                     u.Name AS passengerName, 
    //                                     u.PhoneNo AS passengerContact, 
    //                                     b.seats AS seatsRequested,
    //                                     b.BookingID AS requestID
    //                                   FROM 
    //                                     tb_booking b
    //                                   JOIN 
    //                                     tb_user u ON b.PassengerID = u.User_ID
    //                                   WHERE 
    //                                     b.RideID = ? AND b.status = 'waiting'";
    
    //                 $stmtRequests = $con->prepare($queryRequests);
    //                 $stmtRequests->bindValue(1, $rideDetails['rideID']);
    //                 $stmtRequests->execute();
    //                 $requests = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
    
    //                 // Query to fetch accepted passengers for each ride
    //                 $queryAcceptedPassengers = "SELECT 
    //                                               u.Name AS passengerName, 
    //                                               u.PhoneNo AS passengerContact, 
    //                                               b.seats AS seatsRequested
    //                                             FROM 
    //                                               tb_booking b
    //                                             JOIN 
    //                                               tb_user u ON b.PassengerID = u.User_ID
    //                                             WHERE 
    //                                               b.RideID = ? AND b.status = 'accepted'";
    
    //                 $stmtAcceptedPassengers = $con->prepare($queryAcceptedPassengers);
    //                 $stmtAcceptedPassengers->bindValue(1, $rideDetails['rideID']);
    //                 $stmtAcceptedPassengers->execute();
    //                 $acceptedPassengers = $stmtAcceptedPassengers->fetchAll(PDO::FETCH_ASSOC);
    //             }
    
    //             // If the user is a passenger, fetch their ride requests (waiting status)
    //             if (empty($rideDetails['driverName']) && $rideDetails['bookingStatus'] === 'waiting') {
    //                 $queryPassengerRequests = "SELECT 
    //                                             u.Name AS driverName, 
    //                                             u.PhoneNo AS driverContact, 
    //                                             b.seats AS seatsRequested
    //                                           FROM 
    //                                             tb_booking b
    //                                           JOIN 
    //                                             tb_user u ON b.driverID = u.User_ID
    //                                           WHERE 
    //                                             b.RideID = ? AND b.PassengerID = ? AND b.status = 'waiting'";
    
    //                 $stmtPassengerRequests = $con->prepare($queryPassengerRequests);
    //                 $stmtPassengerRequests->bindValue(1, $rideDetails['rideID']);
    //                 $stmtPassengerRequests->bindValue(2, $userID);
    //                 $stmtPassengerRequests->execute();
    //                 $requests = $stmtPassengerRequests->fetchAll(PDO::FETCH_ASSOC);
    //             }
    
    //             // Build the final output array for each ride
    //             $output[] = [

                    
    //                 'Bookid' => $rideDetails['Bookid'],
    //                 'departurePoint' => $rideDetails['departurePoint'],
    //                 'destinationPoint' => $rideDetails['destinationPoint'],
    //                 'date' => $rideDetails['date'],
    //                 'departureTime' => $rideDetails['departureTime'],
    //                 'destinationTime' => $rideDetails['destinationTime'],
    //                 'vehicleModel' => $rideDetails['vehicleModel'],
    //                 'seatCost' => $rideDetails['seatCost'],
    //                 'status' => $rideDetails['bookingStatus'],
    //                 'availableSeats' => $rideDetails['availableSeats'],
    //                 'driver' => [
    //                     'name' => $rideDetails['driverName'],
    //                     'contact' => $rideDetails['driverContact']
    //                 ],
    //                 'requests' => $requests,
    //                 'acceptedPassengers' => $acceptedPassengers
    //             ];
    //         }
    
    //         return $output;
    //     } catch (PDOException $e) {
    //         error_log("getCurrentRide PDOException: " . $e->getMessage());
    //         return false;
    //     }
    // }
    // public function getCurrentRide($userID) {
    //     try {
    //         $dbcon = new DBconnector();
    //         $con = $dbcon->getConnection();
    
    //         // Step 1: Check for rides in the tb_booking table for the user as a passenger or driver
    //         $query = "SELECT            
    //                      b.BookingID AS Bookid, 
    //                      r.rideID,
    //                      r.departurePoint, 
    //                      r.destinationPoint, 
    //                      r.date, 
    //                      r.departureTime, 
    //                      r.destinationTime, 
    //                      r.vehicleModel, 
    //                      r.seatCost, 
    //                      (r.seats - r.BookingSeats) AS availableSeats, 
    //                      u.Name AS driverName, 
    //                      u.PhoneNo AS driverContact,                     
    //                      b.status AS bookingStatus
    //                   FROM 
    //                      tb_booking b
    //                   JOIN 
    //                      tb_ride r ON b.RideID = r.rideID
    //                   JOIN 
    //                      tb_user u ON r.driverID = u.User_ID
    //                   WHERE 
    //                      b.PassengerID = ? OR b.driverId = ?
    //                   GROUP BY
    //                      r.rideID, b.BookingID";
            
    //         $stmt = $con->prepare($query);
    //         $stmt->bindValue(1, $userID);
    //         $stmt->bindValue(2, $userID);
    //         $stmt->execute();
    //         $rideDetailsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //         // Step 2: If no rides found in the booking table, check the tb_ride table for the driver
    //         if (empty($rideDetailsList)) {
    //             $query = "SELECT 
    //                          NULL AS Bookid, 
    //                          r.rideID,
    //                          r.departurePoint, 
    //                          r.destinationPoint, 
    //                          r.date, 
    //                          r.departureTime, 
    //                          r.destinationTime, 
    //                          r.vehicleModel, 
    //                          r.seatCost, 
    //                          (r.seats - COALESCE(SUM(b.seats), 0)) AS availableSeats, 
    //                          u.Name AS driverName, 
    //                          u.PhoneNo AS driverContact, 
    //                          'driver' AS bookingStatus
    //                       FROM 
    //                          tb_ride r
    //                       LEFT JOIN 
    //                          tb_booking b ON r.rideID = b.RideID
    //                       JOIN 
    //                          tb_user u ON r.driverID = u.User_ID
    //                       WHERE 
    //                          r.driverID = ?
    //                       GROUP BY 
    //                          r.rideID";
                
    //             $stmt = $con->prepare($query);
    //             $stmt->bindValue(1, $userID);
    //             $stmt->execute();
    //             $rideDetailsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         }
    
    //         $output = [];
    
    //         foreach ($rideDetailsList as $rideDetails) {
    //             // Initialize arrays
    //             $requests = [];
    //             $acceptedPassengers = [];
                
    //             // If the user is a driver, fetch ride requests and accepted passengers
    //             if (!empty($rideDetails['driverName'])) {
    //                 $queryRequests = "SELECT 
    //                                     u.Name AS passengerName, 
    //                                     u.PhoneNo AS passengerContact, 
    //                                     b.seats AS seatsRequested,
    //                                     b.BookingID AS requestID
    //                                   FROM 
    //                                     tb_booking b
    //                                   JOIN 
    //                                     tb_user u ON b.PassengerID = u.User_ID
    //                                   WHERE 
    //                                     b.RideID = ? AND b.status = 'waiting'";
    
    //                 $stmtRequests = $con->prepare($queryRequests);
    //                 $stmtRequests->bindValue(1, $rideDetails['rideID']);
    //                 $stmtRequests->execute();
    //                 $requests = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
    
    //                 // Query to fetch accepted passengers for each ride
    //                 $queryAcceptedPassengers = "SELECT 
    //                                               u.Name AS passengerName, 
    //                                               u.PhoneNo AS passengerContact, 
    //                                               b.seats AS seatsRequested
    //                                             FROM 
    //                                               tb_booking b
    //                                             JOIN 
    //                                               tb_user u ON b.PassengerID = u.User_ID
    //                                             WHERE 
    //                                               b.RideID = ? AND b.status = 'accepted'";
    
    //                 $stmtAcceptedPassengers = $con->prepare($queryAcceptedPassengers);
    //                 $stmtAcceptedPassengers->bindValue(1, $rideDetails['rideID']);
    //                 $stmtAcceptedPassengers->execute();
    //                 $acceptedPassengers = $stmtAcceptedPassengers->fetchAll(PDO::FETCH_ASSOC);
    //             }
    
    //             // Build the final output array for each ride
    //             $output[] = [
    //                 'Bookid' => $rideDetails['Bookid'],
    //                 'departurePoint' => $rideDetails['departurePoint'],
    //                 'destinationPoint' => $rideDetails['destinationPoint'],
    //                 'date' => $rideDetails['date'],
    //                 'departureTime' => $rideDetails['departureTime'],
    //                 'destinationTime' => $rideDetails['destinationTime'],
    //                 'vehicleModel' => $rideDetails['vehicleModel'],
    //                 'seatCost' => $rideDetails['seatCost'],
    //                 'status' => $rideDetails['bookingStatus'],
    //                 'availableSeats' => $rideDetails['availableSeats'],
    //                 'driver' => [
    //                     'name' => $rideDetails['driverName'],
    //                     'contact' => $rideDetails['driverContact']
    //                 ],
    //                 'requests' => $requests,
    //                 'acceptedPassengers' => $acceptedPassengers
    //             ];
    //         }
    
    //         return $output;
    //     } catch (PDOException $e) {
    //         error_log("getCurrentRide PDOException: " . $e->getMessage());
    //         return false;
    //     }
    // }
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
                                b.status AS bookingStatus
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
                             'driver' AS bookingStatus
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
                                        b.BookingID AS requestID
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
                                                  b.seats AS seatsRequested
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
    
            $query = "UPDATE tb_booking SET status=? WHERE BookingID=?";
            $status = 'rejected';
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
    
                    $query2 = "SELECT tb_user.* FROM tb_user JOIN tb_booking ON tb_user.User_ID = tb_booking.driverId WHERE tb_booking.BookingID = ?";
                    $stmt2 = $con->prepare($query2);
                    $stmt2->bindValue(1, $Bookid);
                    $stmt2->execute();
                    $Driver_res = $stmt2->fetch(PDO::FETCH_ASSOC);
    
                    if($Driver_res) {
                        $drivername = $Driver_res["Name"];
                        $phone = $Driver_res["PhoneNo"];
                        
                        if(RideDetails::sentRejectMail($UserEmail, $Username, $drivername, $phone)) {
                            return "Request Reject successfully";
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
    // public function deleteRide($rideId) {
    //     try {
    //         $dbcon = new DBconnector();
    //         $con = $dbcon->getConnection();
    
           
    
           
    //         $queryBookings = "DELETE FROM tb_booking WHERE RideID = ?";
    //         $stmtBookings = $con->prepare($queryBookings);
    //         $stmtBookings->bindValue(1, $rideId);
    
    //         if (!$stmtBookings->execute()) {
    //             $con->rollBack();
    //             return "Failed to delete bookings";
    //         }
    
           
    //         $queryRide = "DELETE FROM tb_ride WHERE RideID = ?";
    //         $stmtRide = $con->prepare($queryRide);
    //         $stmtRide->bindValue(1, $rideId);
    
    //         if ($stmtRide->execute()) {
                
    //             return "Ride and associated bookings deleted successfully";
    //         } else {
                
    //             return "Failed to delete the ride";
    //         }
    //     } catch(PDOException $e) {
    //         $con->rollBack();
    //         return "deleteRide PDOException: " . $e->getMessage();
    //     }
    // }

    public function SelectRide()
    {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $sql = "SELECT * FROM tb_ride WHERE rideID = :rideid LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':rideid', $this->rideID);
            $stmt->execute();
            $ride = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $ride;
        } catch (PDOException $e) {
            error_log("SelectRide PDOException: " . $e->getMessage());
            return false;
        }
    }
    
    
        public function deleteRide()
    {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $query = "DELETE FROM tb_ride WHERE rideID = :rideid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':rideid', $this->rideID, PDO::PARAM_INT);  // Ensures it's treated as an integer
      // Assuming Ride_ID is a property of the Ride class
            $res = $stmt->execute();
            return $res;
        } catch (PDOException $e) {
            error_log("Delete ride PDOException: " . $e->getMessage());
            return false;
        }
    }
    

    // public function editRide($rideID, $driverID, $date, $departureTime, $destinationTime, $availableSeats) {
        //     try {
        //       $dbcon = new DBconnector();
        //       $con = $dbcon->getConnection();
          
        //       $queryCheckDriver = "SELECT * FROM tb_ride WHERE rideID = ? AND driverID = ?";
        //       $stmtCheckDriver = $con->prepare($queryCheckDriver);
        //       $stmtCheckDriver->bindValue(1, $rideID);
        //       $stmtCheckDriver->bindValue(2, $driverID);
        //       $stmtCheckDriver->execute();
          
        //       if ($stmtCheckDriver->rowCount() == 0) {
        //         return ['status' => 0, 'message' => 'Unauthorized or Ride not found'];
        //       }
          
        //       $queryUpdateRide = "UPDATE tb_ride 
        //                           SET date = ?, departureTime = ?, destinationTime = ?, seats = ? 
        //                           WHERE rideID = ? AND driverID = ?";
          
        //       $stmtUpdateRide = $con->prepare($queryUpdateRide);
        //       $stmtUpdateRide->bindValue(1, $date);
        //       $stmtUpdateRide->bindValue(2, $departureTime);
        //       $stmtUpdateRide->bindValue(3, $destinationTime);
        //       $stmtUpdateRide->bindValue(4, $availableSeats);
        //       $stmtUpdateRide->bindValue(5, $rideID);
        //       $stmtUpdateRide->bindValue(6, $driverID);
          
        //       if ($stmtUpdateRide->execute()) {
        //         return ['status' => 1, 'message' => 'Ride updated successfully'];
        //       } else {
        //         return ['status' => 0, 'message' => 'Failed to update the ride'];
        //       }
        //     } catch (PDOException $e) {
        //       error_log("editRide PDOException: " . $e->getMessage());
        //       return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
        //     }
        //   }
          
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
                                return array("message" => "Rating submitted and updated successfully", "averageRating" => $averageRating);
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
        
        
    
}

