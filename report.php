<?php

   session_start();
   include "connect.php";

   $report_id = $_POST['report_id'];

   $stmt = $con->prepare("update donor set blocked = 1 where id = ?");
   $stmt->bind_param("i",$report_id);
   $stmt->execute();
   $stmt->close();

   $stmt = $con->prepare("delete from donations where DID = ?");
   $stmt->bind_param("i",$report_id);
   $stmt->execute();
   $stmt->close();

   $stmt = $con->prepare("update person set engaged = 0 where ID = ?");
   $stmt->bind_param("i",$SESSION['id']);
   $stmt->execute();
   $stmt->close();
   
   echo '<script>

        alert("The Donor has been blocked");
        window.location.href = "PatientPortal.php"
       </script>';
?>