<?php
   session_start();
   

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone'])){
   $new_number = $_POST['phone'];
   include "connect.php";

   //updating the users phone number
   $stmt = $con->prepare("update person set phone_number = ? where id = ?");
   $stmt->bind_param("si",$new_number, $_SESSION['id']);

  if($stmt->execute()){

   $stmt->close();
   $con->close();    
    
   echo '<script>
        
         alert("Number updated successfully!");
         window.location.href = "DonorBio.php";
      </script>';
  }else{
     die("Error!");
  }
}

  ?>