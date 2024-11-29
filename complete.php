<?php
 
  session_start();

  $patient_id = $_SESSION['id'];
  $donor_id = $_POST['donor_id'];
  $donation_date = $_POST['donation_date'];
  $blood_type = $_POST['blood_type'];
  include "connect.php";

  $stmt = $con->prepare("delete from donations where DID = ? and PID = ? and donation_date = ?");
  $stmt->bind_param("iis",$donor_id,$patient_id,$donation_date);
  $stmt->execute();
  $stmt->close();

  $stmt = $con->prepare("delete from bloodrequest where PID = ? and donation_date = ?");
  $stmt->bind_param("is",$patient_id,$donation_date);
  $stmt->execute();
  $stmt->close();



  $stmt = $con->prepare("update person set engaged = false where ID = ? or ID = ?");
  $stmt->bind_param("is",$patient_id,$donor_id);
  $stmt->execute();
  $stmt->close();


  $stmt = $con->prepare("update donor set last_donation_date = ?, last_btype_donated = ? where id = ?");
  $stmt->bind_param("ssi",$donation_date, $blood_type,$donor_id);
  $stmt->execute();
  $stmt->close();


  $stmt = $con->prepare("delete from message where (SID = ? and RID = ?) or (RID = ? and DID = ?)");
  $stmt->bind_param("ssi",$patient_id, $donor_id,$patient_id, $donor_id);
  $stmt->execute();
  $stmt->close();


  $con->close();
  header("location: PatientPortal.php");
?>