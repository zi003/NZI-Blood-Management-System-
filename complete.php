<?php
 
  session_start();

  $patient_id = $_SESSION['id'];
  $donor_id = $_POST['donor_id'];
  $donation_date = $_POST['donation_date'];
  $blood_type = $_POST['blood_type'];
  include "connect.php";
 
  //deleting the donations record
  $stmt = $con->prepare("delete from donations where DID = ? and PID = ? and donation_date = ?");
  $stmt->bind_param("iis",$donor_id,$patient_id,$donation_date);
  $stmt->execute();
  $stmt->close();

  //deleting blood request
  $stmt = $con->prepare("delete from bloodrequest where PID = ? and donation_date = ?");
  $stmt->bind_param("is",$patient_id,$donation_date);
  $stmt->execute();
  $stmt->close();


  //engaged is set to false since donation is complete
  $stmt = $con->prepare("update person set engaged = false where ID = ? or ID = ?");
  $stmt->bind_param("is",$patient_id,$donor_id);
  $stmt->execute();
  $stmt->close();


  //donors last donation date/ last blood type is updated
  $stmt = $con->prepare("update donor set last_donation_date = ?, last_btype_donated = ? where id = ?");
  $stmt->bind_param("ssi",$donation_date, $blood_type,$donor_id);
  $stmt->execute();
  $stmt->close();

  //the donor and patients messages are deleted since the donation is complete
  $stmt = $con->prepare("delete from messages where (SID = ? and RID = ?) or (RID = ? and SID = ?)");
  $stmt->bind_param("iiii",$patient_id, $donor_id,$patient_id, $donor_id);
  $stmt->execute();
  $stmt->close();


  $con->close();
  header("location: PatientPortal.php");
?>