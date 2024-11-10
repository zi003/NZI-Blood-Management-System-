<?php

    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Dashboard</title>
    <link rel="stylesheet" href="demo(P).css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Blood Donation</h2>
            <form method="post">
                <button type = "submit" name="action" value="dashboard">My Profile</button>
                <button type = "submit" name="action" value="donorRequest">Donor Requests</button>
                <button type = "submit" name="action" value="patientList">Patient List</button>
                <button type = "button" onclick="logout()">Logout</button>
            </form>
           
        </div>

        <?php 
         $page = isset($_POST['action'])? $_POST['action']:'dashboard';

        ?>
        <!-- Content Area -->
    
        <div class="content">
            <div id = "dashboard">

        
            <!-- My Profile (Dashboard) Page -->
             <?php if($page == "dashboard"): ?>
                <h2>My Profile</h2>
                <p>Account Information:</p>
                <ul>
                    <li>Name:<?php echo $_SESSION['name'] ?> </li>
                    <li>Phone Number: <?php echo $_SESSION['phone_num'] ?></li>
                    <li>Blood Group: <?php echo $_SESSION['blood_grp'] ?></li>
                   <!-- <li>Last Blood Donation Date: 2023-11-08</li>-->
                </ul>
                <h3>Blood Donation History:</h3>
                <ul>
                    <li>Donated on: </li>
                    <!-- Add more history records as needed -->
                </ul>
           
            <!-- Donor Requests Page -->
            <?php elseif($page == "donorRequest"): ?>
                <h2>Donor Requests</h2>
                <p>Patient Requests:</p>
                <ul>
                    <li>
                        <span>John Smith (Blood Group: B+)</span>
                        <button>Accept</button>
                        <button>Decline</button>
                    </li>
                    <!-- More patients can be added similarly -->
                </ul>

            <!-- Patient List Page -->
            <?php elseif($page == "patientList"): ?>
                <h2>Patient List</h2>
                <p>Available Patients:</p>
                <ul>
                    <li>
                        <span>Jane Doe (Blood Group: O+)</span>
                        <button>Donate</button>
                    </li>
                    <!-- More donors can be added similarly -->
                </ul>
            <?php endif; ?>
           
            </div>
         </div>

       
    <script>
       /* function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => page.style.display = 'none');
            document.getElementById(pageId).style.display = 'block';
        }*/

        function logout() {
            alert("You have logged out.");
            window.location.href = "Login.html";
        }
    </script>
</body>
</html>
