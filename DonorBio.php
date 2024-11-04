<?php
 
 session_start();


 $con = new mysqli('localhost','root','','nzi blood management system');

 $stmt = $con->prepare("select ID, Firstname,Lastname, blood_group, phone_number from person where email_address = ?");
 $stmt->bind_param("s",$_SESSION['email']);

 $stmt->execute();

 $stmt->bind_result($ID, $firstname, $lastname,$blood_group, $phone_num);

 if($stmt->fetch())
 { 
    $_SESSION['name'] = $firstname . " " . $lastname;
    $_SESSION['blood_grp'] = $blood_group;
    $_SESSION['phone_num'] = $phone_num;

    header("Location: Portal(D).php");
    exit();
 
 }

 $stmt->close();
 $con->close();


?>