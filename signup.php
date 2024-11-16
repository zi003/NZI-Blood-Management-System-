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
  $person_type = $_POST['person_type'];
  $location = $_POST['location'];

    $con = new mysqli('localhost','root','','nzi blood management system');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
      $stmt = $con->prepare("insert into person (Firstname, Lastname, password, blood_group, phone_number, email_address, person_type,location) VALUES (?,?,?,?,?,?,?,?)");
      $stmt->bind_param("ssssssss",$firstname,$lastname,$hashed_password,$bloodgroup,$phonenumber,$emailaddress,$person_type,$location);
      $stmt->execute();

      $id = $con->insert_id;
      if($person_type == "Donor"){
        $last_donation_date = $_POST['DOD'];
        $last_bloodtype_donate = $_POST['bloodType'];

        $stmt = $con->prepare("insert into donor (id,last_donation_date,last_btype_donated) values(?,?,?)");
        $stmt->bind_param("iss",$id,$last_donation_date, $last_bloodtype_donate);
      }
      else if($person_type == "Patient"){
        $patient_type = $_POST['frequency'];
        $stmt = $con->prepare("insert into patient (id,patient_type) values(?,?)");
        $stmt->bind_param("is",$id,$patient_type);
      }

     if($stmt->execute()){
        
        /*
        //mail to user for successfully logging in
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

        if($person_type == "Donor"){
        $mail->Subject = "NZI Blood Management Donor Registration Successful!";
        
        
        $mail->Body = "Thank You for registering into NZI Blood Management System!<br>
                       You can now donate blood to save lives.<br><br><br>
                        
                       Thank You.<br>
                       -NZI Team.<br>
                       ";
        
        }
        else if($person_type == "Patient"){
            $mail->Subject = "NZI Blood Management Patient Registration Successful!";
            
            
            $mail->Body = "Thank You for registering into NZI Blood Management System!<br>
                           You can now receive blood through registered donors.<br><br><br>
    
                           Thank You.<br>
                           -NZI Team.<br>
                           ";
            
            }

        $mail->send();
        */
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