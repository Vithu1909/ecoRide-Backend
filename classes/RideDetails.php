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

    public function __construct($Ride_ID, $Driver_ID, $Passanger_ID, $StartLocation, $EndLocation, $StartTime, $EndTime, $vehicleNo, $vehicleModel, $seats, $airCondition, $Date, $cost, $gender, $vehicleImg, $route, $preferences, $publishedDate) {
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
   public static function DisplayRide() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();

            $sql = "SELECT 
            r.*,u.User_ID,u.Name,u.Email,u.PhoneNo,u.NicNo,u.Gender
            FROM  
                tb_ride r
            INNER JOIN
                tb_user u
            ON
                r.driverID = u.User_ID";
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
}
?>
