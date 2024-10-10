<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

  $firstname = $_POST['first-name'];
  $lastname = $_POST['last-name'];
  $password = $_POST['password'];
  //hashing the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $bloodgroup = $_POST['blood-group'];
  $phonenumber = $_POST['phone-number'];
  $emailaddress = $_POST['email'];
  

  if(empty($firstname)||empty($lastname)||empty($password)||empty($bloodgroup)||empty($phonenumber)||empty($emailaddress))
  {
    $error_message = "Please fill up all fields!";
  }
  else{

    $con = new mysqli('localhost','root','','nzi blood management system');

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
    $stmt = $con->prepare("insert into person (Firstname, Lastname, password, blood_group, phone_number, email_address) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss",$firstname,$lastname,$hashed_password,$bloodgroup,$phonenumber,$emailaddress);

    if($stmt->execute()){
        echo "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f0f0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .message {
                    background-color: #4CAF50; /* Green */
                    color: white;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    text-align: center;
                }
                .timer {
                    font-weight: bold;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class='message'>
                <h2>Registration Successful!</h2>
                <p>You will be redirected to the login page shortly.</p>
                <p class='timer'>Redirecting in <span id='countdown'>2</span> seconds...</p>
            </div>
            <script>
                let countdownElement = document.getElementById('countdown');
                let countdown = 2;
                setInterval(function() {
                    countdown--;
                    countdownElement.innerHTML = countdown;
                    if (countdown <= 0) {
                        clearInterval(this);
                        window.location.href = 'login.html';  // Change to your actual login page path
                    }
                }, 1000);
            </script>
        </body>
        </html>
        ";
        exit(); // Make sure to exit after displaying the message
    }
    else{
         die("Error!");
    }
    $stmt->close();
    $con->close();
  }

?>