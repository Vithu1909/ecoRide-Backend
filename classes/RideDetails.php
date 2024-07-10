<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;

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

    public function __construct($Ride_ID, $Driver_ID, $Passanger_ID, $StartLocation, $EndLocation, $StartTime, $EndTime, $vehicleNo, $vehicleModel, $seats, $airCondition, $Date, $cost, $gender, $vehicleImg, $route, $preferences, $publishedDate,$publishedTime) {
        $this->Ride_ID = $Ride_ID;
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

    public function getRide_ID() {
        return $this->Ride_ID;
    }

    public function setRide_ID($Ride_ID) {
        return $this->Ride_ID = $Ride_ID;
        
    }

    public function getDriver_ID() {
        return $this->Driver_ID;
    }

    public function setDriver_ID($Driver_ID) {
        return $this->Driver_ID = $Driver_ID;
       
    }

    public function getPassanger_ID() {
        return $this->Passanger_ID;
    }

    public function setPassanger_ID($Passanger_ID) {
        return  $this->Passanger_ID = $Passanger_ID;
        
    }

    public function getStartLocation() {
        return $this->StartLocation;
    }

    public function setStartLocation($StartLocation) {
        return $this->StartLocation = $StartLocation;
       
    }

    public function getEndLocation() {
        return $this->EndLocation;
    }

    public function setEndLocation($EndLocation) {
        return $this->EndLocation = $EndLocation;
       
    }

    public function getStartTime() {
        return $this->StartTime;
    }

    public function setStartTime($StartTime) {
        return $this->StartTime = $StartTime;
        
    }

    public function getEndTime() {
        return $this->EndTime;
    }

    public function setEndTime($EndTime) {
        return $this->EndTime = $EndTime;
        
    }

    public function getVehicleNo() {
        return $this->vehicleNo;
    }

    public function setVehicleNo($vehicleNo) {
        return $this->vehicleNo = $vehicleNo;
       
    }

    public function getVehicleModel() {
        return $this->vehicleModel;
    }

    public function setVehicleModel($vehicleModel) {
        return $this->vehicleModel = $vehicleModel;
        
    }

    public function getSeats() {
        return $this->seats;
    }

    public function setSeats($seats) {
        return $this->seats = $seats;
      
    }

    public function getAirCondition() {
        return $this->airCondition;
    }

    public function setAirCondition($airCondition) {
        return  $this->airCondition = $airCondition;
        
    }

    public function getDate() {
        return $this->Date;
    }

    public function setDate($Date) {
        return  $this->Date = $Date;
        
    }

    public function getCost() {
        return $this->cost;
    }

    public function setCost($cost) {
        return $this->cost = $cost;
       
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        return $this->gender = $gender;
        
    }

    public function getVehicleImg() {
        return $this->vehicleImg;
    }

    public function setVehicleImg($vehicleImg) {
        return $this->vehicleImg = $vehicleImg;
       
    }

    public function getRoute() {
        return $this->route;
    }

    public function setRoute($route) {
        return $this->route = $route;

    }

    public function getPreferences() {
        return $this->preferences;
    }

    public function setPreferences($preferences) {
        return $this->preferences = $preferences;
       
    }

    public function getPublishedDate() {
        return $this->publishedDate;
    }

    public function setPublishedDate($publishedDate) {
        return  $this->publishedDate = $publishedDate;
        
    }
    public function getpublishedTime() {
        return $this->publishedTime;
    }

    public function setpublishedTime($publishedTime) {
        return  $this->publishedTime = $publishedTime;
        
    }
   public static function DisplayRide() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();

            // $sql = "SELECT 
            //             r.*, 
            //             u.User_ID AS driver_ID, 
            //             u.Name AS driverName, 
            //             u.Email AS driverEmail, 
            //             u.PhoneNo AS driverPhoneNo, 
            //             u.NicNo AS driverNicNo, 
                        
            //             GROUP_CONCAT(b.PassengerID) AS passengers
            //         FROM  
            //             tb_ride r
            //         INNER JOIN
            //             tb_user u
            //         ON
            //             r.driverID = u.User_ID
            //         LEFT JOIN
            //             tb_booking b
            //         ON
            //             r.rideID = b.RideID
            //         GROUP BY
            //             r.rideID";
            $sql="SELECT 
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
    r.rideID, u.User_ID, u.Name, u.Email, u.PhoneNo, u.NicNo;
";
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
            }
            return true;
        } catch (PDOException $e) {
            error_log("addRide PDOException: " . $e->getMessage());
            return false;
        }
    }
    
}