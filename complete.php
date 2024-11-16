<?php
 
  session_start();

  $patient_id = $_SESSION['id'];
  $donor_id = $_POST['donor_id'];
  $donation_date = $_POST['donation_date'];

  include "connect.php";

  $stmt = $con->prepare("delete from donations where DID = ? and PID = ? and donation_date = ?");
  $stmt->bind_param("iis",$donor_id,$patient_id,$donation_date);
  $stmt->execute();


  $stmt = $con->prepare("delete from bloodrequest where PID = ? and donation_date = ?");
  $stmt->bind_param("is",$patient_id,$donation_date);
  $stmt->execute();


  $stmt = $con->prepare("update person set engaged = false where ID = ? or ID = ?");
  $stmt->bind_param("is",$patient_id,$donor_id);
  $stmt->execute();


  header("location: PatientPortal.php");
?>