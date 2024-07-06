<?php
namespace classes;

require_once "DBconnector.php";
use classes\DBconnector;
use PDOException;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User {
    private $User_ID;
    private $UserName;
    private $Name;
    private $Gender;
    private $NicNo;
    private $PhoneNo;
    private $Email;
    private $Password;
    private $userRole;
    private $otp;

    public function __construct($User_ID, $Name, $UserName, $NicNo, $PhoneNo, $Email, $Gender, $Password, $userRole, $otp) {
        $this->User_ID = $User_ID;
        $this->UserName = $UserName;
        $this->Name = $Name;
        $this->NicNo = $NicNo;
        $this->Gender = $Gender;
        $this->PhoneNo = $PhoneNo;
        $this->Email = $Email;
        $this->Password = $Password;
        $this->userRole = $userRole;
        $this->otp = $otp;
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
    public function getuserRole() {
        return $this->Password;
    }

    public function setuserRole($userRole) {
        $this->userRole = $userRole;
    }
    public function getotp() {
        return $this->Password;
    }

    public function setotp($otp) {
        $this->otp = $otp;
    }
   public function Updatepassword() {
    try {
        $dbcon = new DBconnector();
        $conn = $dbcon->getConnection();
        $hashedPassword = password_hash($this->Password, PASSWORD_BCRYPT);
        
        $query = "UPDATE tb_user SET Password = :password, otp = :otp WHERE User_ID = :userid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $defaultOtp = null; 
        $stmt->bindParam(':otp', $defaultOtp);
        $stmt->bindParam(':userid', $this->User_ID);
        
        $res = $stmt->execute();
        if ($res) {
            $query1 = "SELECT * FROM tb_user WHERE User_ID = :userID";
            $stmt1 = $conn->prepare($query1);
            $stmt1->bindParam(':userID', $this->User_ID);
            $stmt1->execute();
            
            if ($stmt1->rowCount() > 0) {
                $user = $stmt1->fetch(PDO::FETCH_ASSOC);
                return $user;
            } else {
                error_log("Failed to Change password.");
                return false;
            }
        } else {
            error_log("Failed to execute update query.");
            return false;
        }
    } catch (PDOException $e) {
        error_log("Change password PDOException: " . $e->getMessage());
        return false;
    }
}

    





    public function SignupUser() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            
            // Prepare query to check if username already exists
            $query1 = "SELECT * FROM tb_user WHERE UserName = :username";
            $stmt1 = $conn->prepare($query1);
            $stmt1->bindParam(':username', $this->UserName);
            $stmt1->execute();
            
            if ($stmt1->rowCount() > 0) {
                // User already added
                return false;
            } else {
                $hashedPassword = password_hash($this->Password, PASSWORD_BCRYPT);
                $query = "INSERT INTO tb_user (User_ID, UserName,Name, Email, PhoneNo, NicNo, Gender, Password) VALUES (null,:UserName, :Name, :Email, :PhoneNo, :NicNo, :Gender, :Password)";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':UserName', $this->UserName);
                $stmt->bindValue(':Name', $this->Name);
                $stmt->bindValue(':Email', $this->Email);
                $stmt->bindValue(':PhoneNo', $this->PhoneNo);
                $stmt->bindValue(':NicNo', $this->NicNo);
                $stmt->bindValue(':Gender', $this->Gender);
                $stmt->bindValue(':Password', $hashedPassword);
                $res = $stmt->execute();
                return true;
            }
        } catch (PDOException $e) {
            error_log("SignupUser PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function LoginUser() {
        try {
            $dbcon = new DBconnector();
            $conn = $dbcon->getConnection();
            $sql = "SELECT * FROM tb_user WHERE UserName = :username LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $this->UserName);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($this->Password, $user['Password'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("LoginUser PDOException: " . $e->getMessage());
            return false;
        }
    }
    public static function DisplayUser() {
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
            error_log("DisplayUser PDOException: " . $e->getMessage());
            return false;
        }
    }
    public function sendOTP($otp) {
        try {
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
           
            $query1 = "SELECT * FROM tb_user WHERE Email = :email";
            $stmt1 = $con->prepare($query1);
            $stmt1->bindParam(':email', $this->Email);
            $stmt1->execute();
            
            if ($stmt1->rowCount() > 0) {  
                $user = $stmt1->fetch(PDO::FETCH_ASSOC);
                $this->Name=$user['Name'];

                $query = "UPDATE tb_user SET otp = :otp WHERE Email = :email";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':otp', $otp);
                $stmt->bindParam(':email', $this->Email);
                $res = $stmt->execute();
                
                if ($res) {
                   
                    // Sending OTP email
                    $emailSent = $this->sendOTPEmail($this->Email, $this->Name,$this->otp);
                    if ($emailSent) {
                        return true;
                    } else {
                        error_log("sendOTP: Failed to send OTP email.");
                        return false;
                    }
                } else {
                    error_log("sendOTP: Failed to update OTP in the database.");
                    return false;
                }
            } else {
                error_log("sendOTP: Email not found in the database.");
                return "email_not_found";
            }
        } catch (PDOException $e) {
            error_log("sendOTP PDOException: " . $e->getMessage());
            return false;
        }
    }
    public function VerifyOTP() {
        try {
            $dbcon = new DBconnector();
            $con = $dbcon->getConnection();
           
            $query1 = "SELECT * FROM tb_user WHERE Email = :email";
            $stmt1 = $con->prepare($query1);
            $stmt1->bindParam(':email', $this->Email);
            $stmt1->execute();
            
            if ($stmt1->rowCount() > 0) {  
                $user = $stmt1->fetch(PDO::FETCH_ASSOC);
                $this->otp=$user['otp'];
               
                return $user;
               

            } else {
                error_log("sendOTP: Email not found in the database.");
                return "email_not_found";
            }
        } catch (PDOException $e) {
            error_log("sendOTP PDOException: " . $e->getMessage());
            return false;
        }
    }


    
    
    
    
    public static function sendOTPEmail($email,$name,$otp) {
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
            $mail->Subject = 'Your OTP Code';
            
    
             $message = "Dear $name<br><br>";
            $message .= "Your OTP code is<br>";
            $message .= "<span style='font-weight: bold;'text-align: center;''font-size: 16px;'>$otp</span><br>";
            $message .= "<hr><br>";
            //$message .= "Thank you for contacting us. We will get back to you shortly.<br><br>";
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
