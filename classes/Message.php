<?php

namespace classes;

require_once "DBconnector.php";

use classes\DBconnector;
use PDOException;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Message {
    private $Msg_ID;
    private $name;
    private $email;
    private $messages;
    private $Date;

    public function __construct($Msg_ID, $name, $email, $messages, $Date) {
        $this->Msg_ID = $Msg_ID;
        $this->name = $name;
        $this->messages = $messages;
        $this->email = $email;
        $this->Date = $Date ?? date("Y-m-d");
    }

    public function getMsg_ID() {
        return $this->Msg_ID;
    }

    public function setMsg_ID($Msg_ID) {
        $this->Msg_ID = $Msg_ID;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getDate() {
        return $this->Date;
    }

    public function setDate($Date) {
        $this->Date = $Date;
    }

    public function addMessage() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $currentDate = date("Y-m-d");
            $query = "INSERT INTO tb_message (message, emailAdress, name, date) VALUES (:message, :email, :name, :currentDate)";            $stmt = $conn->prepare($query);
            $stmt->bindValue(':message', $this->messages);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':currentDate', $currentDate);
            $res = $stmt->execute();
           
    
            if ($res) {
                // Uncomment the line below to send email after successful database insert
                Message::SendMail($this->name, $this->email);
               
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
            return false;
        }
    }
    

    public static function displayMessage() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $sql = "SELECT * FROM tb_message";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function SendMail($name, $email) {
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
            $mail->Password = 'efro alij itud xeqm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('ecoridecst@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Message Sent Successfully!';
    
            $message = "Dear " . $name . "<br><br>";
            $message .= "<span style='color: green;'>Your message has been sent successfully.</span><br>";
            $message .= "<hr><br>";
            $message .= "Thank you for contacting us. We will get back to you shortly.<br><br>";
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
