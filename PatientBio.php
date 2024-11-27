<?php


session_start();


 $con = new mysqli('localhost','root','','nzi blood management system');

 $stmt = $con->prepare("select p.ID, Firstname,Lastname, blood_group, phone_number,location, latitude, longitude, patient_type, engaged from person as p join patient as pt on p.ID = pt.id where email_address = ?");
 $stmt->bind_param("s",$_SESSION['email']);

 $stmt->execute();

 $stmt->bind_result($ID, $firstname, $lastname,$blood_group, $phone_num, $location, $latitude, $longitude, $patient_type, $engaged);

 if($stmt->fetch())
 { 
    $_SESSION['id'] = $ID;
    $_SESSION['name'] = $firstname . " " . $lastname;
    $_SESSION['blood_grp'] = $blood_group;
    $_SESSION['phone_num'] = $phone_num;
    $_SESSION['location'] = $location;
    $_SESSION['latitude'] = $latitude;
    $_SESSION['longitude'] = $longitude;
    $_SESSION['patient_type'] = $patient_type;
    $_SESSION['engaged'] = $engaged;

    header("Location: PatientPortal.php");
    exit();
 
 }

 $stmt->close();
 $con->close();



?>