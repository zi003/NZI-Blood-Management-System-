<?php
   
   session_start();

   $donor_id = $_SESSION['id'];
   $patient_id = $_POST['patient_id'];
   $donation_date = $_POST['donation_date'];
   $donation_time = $_POST['donation_time'];

   
   include 'connect.php';
   
   $stmt = $con->prepare("select count(*) from person as p join donor as d on (p.ID = d.id) where ((last_btype_donated = 'blood' and last_donation_date< DATE_SUB(CURDATE(), INTERVAL 3 month)) or (last_btype_donated = 'platelet' and last_donation_date< DATE_SUB(CURDATE(), INTERVAL 2 week))) and p.id = ?");
   $stmt->bind_param("i",$donor_id);
   $stmt->execute();
   $stmt->bind_result($num_rows);
   $stmt->fetch();


   if($num_rows>0){
   $stmt->close();
   $stmt = $con->prepare("insert into donations VALUES (?,?,?,?)");
   $stmt->bind_param("iiss",$donor_id, $patient_id, $donation_date, $donation_time);

   

   if($stmt->execute())
   {

    $stmt->close();

    $stmt = $con->prepare("update person set engaged = true where ID = ? or ID = ?");
    $stmt->bind_param("ii",$patient_id,$donor_id);

    
    if($stmt->execute()){
     $stmt->close();

     $stmt = $con->prepare("delete from requestdonor where PID = ? and DID = ?");
     $stmt->bind_param("ii",$patient_id,$donor_id);
     $stmt->execute();
     $stmt->close();
     $con->close();

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
   }


?>