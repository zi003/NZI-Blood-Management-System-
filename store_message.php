<?php

  session_start();

  include "connect.php";

  $stmt = $con->prepare("insert into messages (RID, SID, message) values(?,?,?)");
  $stmt->bind_param("iis",$_SESSION['receiver_id'], $_SESSION['id'], $_POST['message']);
  $stmt->execute();

  header("location: message(D).php");
  exit;
?>