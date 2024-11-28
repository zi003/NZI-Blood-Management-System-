<?php
 
 session_start();


 $con = new mysqli('localhost','root','','nzi blood management system');

 $stmt = $con->prepare("select p.ID, Firstname,Lastname, blood_group, phone_number,location,last_donation_date,engaged, latitude, longitude from person as p join donor as d on p.ID = d.id where email_address = ?");
 $stmt->bind_param("s",$_SESSION['email']);

 $stmt->execute();

 $stmt->bind_result($ID, $firstname, $lastname,$blood_group, $phone_num, $location, $last_dondate, $engaged, $latitude, $longitude);

 if($stmt->fetch())
 { 
    $_SESSION['id'] = $ID;
    $_SESSION['name'] = $firstname . " " . $lastname;
    $_SESSION['blood_grp'] = $blood_group;
    $_SESSION['phone_num'] = $phone_num;
    $_SESSION['location'] = $location;
    $_SESSION['last_dondate'] = $last_dondate;
    $_SESSION['engaged'] = $engaged;
    $_SESSION['latitude'] = $latitude;
    $_SESSION['longitude'] = $longitude;

    header("Location: DonorPortal.php");
    exit();
 
 }

 $stmt->close();
 $con->close();


?>