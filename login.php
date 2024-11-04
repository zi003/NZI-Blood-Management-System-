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

    $stmt = $con->prepare("select password from person where email_address = ?"); //selecting passwords for the email
    $stmt->bind_param("s",$email);

    if($stmt->execute())
    {
        $stmt->bind_result($hashedpass); //retrieving hashedpassword from table
        $stmt->fetch();

        if($hashedpass){ //checks if any password was retrieved
        if(password_verify($password,$hashedpass))
        {
            echo'<script> alert("Login Successful!"); 
                 window.location.href = "DonorBio.php"
            </script>';

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