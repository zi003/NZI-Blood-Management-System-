<?php
   $email = '';
   $password = '';
   $email = $_POST['email'];
   $password = $_POST['password'];


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
            echo"Login Successful!";
        }
        else{
            echo"Incorrect Password!";
        }
        } 
        else{
            echo"Email not signed up! Please sign up!";
        }
    }
    else{
        die("Error!!");
    }
    }
    $stmt->close();
    $con->close();
?>