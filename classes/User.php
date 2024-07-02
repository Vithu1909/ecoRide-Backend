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

    public function setUser_ID($User_ID) {
        $this->User_ID = $User_ID;
    }

    public function getUserName() {
        return $this->UserName;
    }

    public function setUserName($UserName) {
        $this->UserName = $UserName;
    }

    public function getName() {
        return $this->Name;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function getNicNo() {
        return $this->NicNo;
    }

    public function setNicNo($NicNo) {
        $this->NicNo = $NicNo;
    }

    public function getGender() {
        return $this->Gender;
    }

    public function setGender($Gender) {
        $this->Gender = $Gender;
    }

    public function getPhoneNo() {
        return $this->PhoneNo;
    }

    public function setPhoneNo($PhoneNo) {
        $this->PhoneNo = $PhoneNo;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setPassword($Password) {
        $this->Password = $Password;
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
    public function LoginUser($username, $password) {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $sql = "SELECT * FROM tb_user WHERE UserName = :username LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['Password'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function DisplayUser()
    {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();

            $sql = "SELECT * from tb_user";

            $stmt = $conn->prepare($sql);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }

    }
}
?>
