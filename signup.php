<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

  $firstname = $_POST['first-name'];
  $lastname = $_POST['last-name'];
  $password = $_POST['password'];
  //hashing the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $bloodgroup = $_POST['blood-group'];
  $phonenumber = $_POST['phone-number'];
  $emailaddress = $_POST['email'];
  

    $con = new mysqli('localhost','root','','nzi blood management system');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
      $stmt = $con->prepare("insert into person (Firstname, Lastname, password, blood_group, phone_number, email_address) VALUES (?,?,?,?,?,?)");
      $stmt->bind_param("ssssss",$firstname,$lastname,$hashed_password,$bloodgroup,$phonenumber,$emailaddress);

    if($stmt->execute()){
        

        $mail = new PHPMailer(true);
        $mail -> isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'zuhayer.islam@northsouth.edu';
        $mail-> Password = 'afbu erwb ekqd aqqu';
        $mail->SMTPSecure = 'ssl';
        $mail-> Port = 465;

        $mail->setFrom('zuhayer.islam@northsouth.edu');
        $mail->addAddress($emailaddress);

        $mail->isHTML(true);

        $mail->Subject = "NZI Blood Management System Registration Successful!";

        $mail->Body = "Thank You for registering into NZI Blood Management System!<br>
                       You are now ready to get blood or donate to save lives.<br><br><br>

                       Thank You.<br>
                       -NZI Team.<br>
                       ";
 
        $mail->send();
        
        echo '<script>
        
         alert("Registration Succesful, You have been mailed! Log in to continue");
         window.location.href = "Login.html";

        </script>';

        exit(); 
    }
    else{
         die("Error!");
    }
    $stmt->close();
    $con->close();
  
?>