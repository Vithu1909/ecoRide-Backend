<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;

class User {
    private $User_ID;
    private $UserName;
    private $Name;
    private $Gender;
    private $NicNo;
    private $PhoneNo;
    private $Email;
    private $Password;

    public function __construct($Name, $UserName, $NicNo, $PhoneNo, $Email, $Gender, $Password) {
        $this->UserName = $UserName;
        $this->Name = $Name;
        $this->NicNo = $NicNo;
        $this->Gender = $Gender;
        $this->PhoneNo = $PhoneNo;
        $this->Email = $Email;
        $this->Password = $Password;
    }

    public function getUser_ID() {
        return $this->User_ID;
    }

    public function setUser_ID($value) {
        $this->User_ID = $value;
    }

    public function getUserName() {
        return $this->UserName;
    }

    public function setUserName($value) {
        $this->UserName = $value;
    }

    public function getName() {
        return $this->Name;
    }

    public function setName($value) {
        $this->Name = $value;
    }

    public function getNicNo() {
        return $this->NicNo;
    }

    public function setNicNo($value) {
        $this->NicNo = $value;
    }

    public function getGender() {
        return $this->Gender;
    }

    public function setGender($value) {
        $this->Gender = $value;
    }

    public function getPhoneNo() {
        return $this->PhoneNo;
    }

    public function setPhoneNo($value) {
        $this->PhoneNo = $value;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($value) {
        $this->Email = $value;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setPassword($value) {
        $this->Password = $value;
    }

    public function SignupUser() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $hashedPassword = password_hash($this->Password, PASSWORD_DEFAULT);
            $query = "INSERT INTO tb_user (User_ID, Name, UserName, Email, PhoneNo, NicNo, Gender, Password) VALUES (null, :Name, :UserName, :Email, :PhoneNo, :NicNo, :Gender, :Password)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':UserName', $this->UserName);
            $stmt->bindValue(':Name', $this->Name);
            $stmt->bindValue(':Email', $this->Email);
            $stmt->bindValue(':PhoneNo', $this->PhoneNo);
            $stmt->bindValue(':NicNo', $this->NicNo);
            $stmt->bindValue(':Gender', $this->Gender);
            $stmt->bindValue(':Password', $hashedPassword);
            $res = $stmt->execute();
            if ($res) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
