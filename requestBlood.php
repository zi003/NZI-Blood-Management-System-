<?php

session_start();

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['form_type']) && $_POST['form_type'] == "create_request"){
             
    $patient_id = $_SESSION['id'];
    $blood_grp = $_POST['bloodGroup'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['place'];
    $blood_type = $_POST['requestType'];

    $con = new mysqli('localhost','root','','nzi blood management system');

    if($con->connect_error){
       die("connection failed!");
    }
    $stmt = $con->prepare("insert into bloodrequest (PID, blood_group, donation_date, donation_time, location, blood_type) values (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $patient_id, $blood_grp, $date, $time, $location, $blood_type);

    if(!$stmt->execute())
       die("Error!!");


   $stmt->close();
   $con->close();

    echo '<script>
       
        alert("Request Form Submitted Successfully");
        window.location.href = "PatientPortal.php";
       </script>';
    }
    
?>