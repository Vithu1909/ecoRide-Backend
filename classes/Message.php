<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;

class Message{
    private $Msg_ID;
    private $name;
    private $email;
    private $messages;
    private $Date;

        public function __construct($Msg_ID,$name,$messages,$email)
        {
            $this->Msg_ID=$Msg_ID;
            $this->name=$name;
            $this->messages=$messages;
            $this->email=$email;

        }
        public function getMeg_ID() {
            return $this->Msg_ID;
        }
    
        public function setMsg_ID($Msg_ID) {
            $this->Msg_ID = $Msg_ID;
        }
        public function getname() {
            return $this->name;
        }
    
        public function setname($name) {
            $this->name = $name;
        }
        public function getmessages() {
            return $this->messages;
        }
    
        public function setmessages($messages) {
            $this->messages = $messages;
        }
        public function getemail() {
            return $this->email;
        }
    
        public function setemail($email) {
            $this->email = $email;
        }
        public function getDate()
	{
		return $this->Date;
	}

	public function setDate($value)
	{
		$this->Date = $value;
	}
        public function Addmessage()
        {
            try {
                $dbcon = new DBconnector();
                 $conn = $dbcon->getConnection();
                 $currentDate = date("Y-m-d");
                $query = "INSERT INTO tb_message ( message, emailAdress, name,date) VALUES ( :message, :email,  :name,:currentDate)";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':message', $this->messages);
                $stmt->bindValue(':email', $this->email);
                $stmt->bindValue(':name', $this->name);
                $stmt->bindValue(':currentDate', $currentDate);
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
        public static function Displaymessage()
        {
            try {
                $dbcon = new DBconnector();
                $conn = $dbcon->getConnection();
    
                $sql = "SELECT * from tb_message";
    
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
