<?php
   session_start();
   $email = '';
   $password = '';
   $email = $_POST['email'];
   $password = $_POST['password'];
   $_SESSION['email'] = $email;


   $con = new mysqli('localhost','root','','nzi blood management system'); 
   
   if($con->connect_error)
   {
        die("Connection Error!");
   }
    else{

    $stmt = $con->prepare("select person_type, password from person where email_address = ?"); //selecting passwords for the email
    $stmt->bind_param("s",$email);

    if($stmt->execute())
    {
        $stmt->bind_result($person_type, $hashedpass); //retrieving hashedpassword from table
        $stmt->fetch();

        if($hashedpass){ //checks if any password was retrieved
        if(password_verify($password,$hashedpass))
        {
            ?>


          <script> 
           alert("Login Successful!"); 

          <?php if($person_type == "Donor") { ?>
             window.location.href = "DonorBio.php"; 
           <?php } else if($person_type == "Patient") { ?>
             window.location.href = "PatientBio.php"; 
        
        <?php } ?>
      </script>

   

       <?php 
        }
        else{
            echo'<script> alert("Incorrect Password!!"); 
            window.location.href = "Login.html"
            </script>';
        }
        } 
        else{
            echo'<script> alert("You havent signed up, please sign up before logging in!");
             window.location.href = "Login.html"
             </script>';
        }
    }
    else{
        die("Error!!");
    }
    }
    $stmt->close();
    $con->close();
?>