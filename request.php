<?php

   session_start();

   $patient_id = $_SESSION['id'];
   $donor_id = $_POST['donor_id'];
   
   include "connect.php";

   $stmt = $con->prepare("select donation_date, donation_time, blood_group, location, blood_type from bloodrequest where PID = ?");
   $stmt->bind_param("i",$donor_id);

   $stmt->execute();
   $stmt->bind_result($donation_date, $donation_time, $blood_group, $location, $blood_type);

   $stmt->close();

   $stmt =  $con->prepare("insert into requestdonor VALUES (?,?)");
   $stmt->bind_param("ii",$patient_id, $donor_id);

   $stmt->execute();
   $stmt->close();
   $con->close();



?>