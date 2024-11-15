<?php
   
   session_start();

   $donor_id = $_SESSION['id'];
   $patient_id = $_POST['patient_id'];
   $donation_date = $_POST['donation_date'];
   $donation_time = $_POST['donation_time'];

   
   include 'connect.php';

   $stmt = $con->prepare("insert into donations VALUES (?,?,?,?)");
   $stmt->bind_param("iiss",$donor_id, $patient_id, $donation_date, $donation_time);

   

   if($stmt->execute())
   {

    $stmt->close();

    $stmt = $con->prepare("update person set engaged = true where ID = ? or ID = ?");
    $stmt->bind_param("ii",$patient_id,$donor_id);

    
    if($stmt->execute()){
    
     echo'<script>
       
       alert("Successfully chosen donor for donation!");
       window.location.href = "DonorPortal.php";

     </script>';

     exit();
    }
   }
   else{
    die("Error!");
   }

   $stmt->close();
   $con->close();


?>