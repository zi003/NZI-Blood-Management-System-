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
                    <li>Location:<?php echo $_SESSION['location'] ?> </li>
                    <li>Last Blood Donation Date:<?php echo $_SESSION['last_dondate'] ?> </li>
                </ul>
                <h3>Upcoming Donations:</h3>
                <ul>
                   <!-- <li>Donated on: </li> -->
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
               
                <!--
                <ul>
                    <li>
                        <span>Jane Doe (Blood Group: O+)</span>
                        <button>Donate</button>
                    </li> -->
        <?php
          $con = new mysqli('localhost','root','','nzi blood management system');
            
          $stmt = $con->prepare("select * from person where person_type = 'Patient'");
          $stmt->execute();

          $result = $stmt->get_result();

          if($result->num_rows>0)
          {
            if ($result->num_rows > 0) {
                // Output the data in a table format
                echo "<table>";
                echo "<tr><th> First Name </th><th>   Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>    Location  </th></tr>";
                
                // Fetch and output each row of data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Location']) . "</td>";

                    echo "<td>";
                    echo "<form action='donate.php' method='post'>";
                    echo "<input type='hidden' name='patient_id' value='" . htmlspecialchars($row['ID']) . "'>";
                    echo "<input type='submit' value='Donate'>";
                    echo "</form>";
                    echo "<td";

                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "No results found.";
            }
        }
       ?>
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
            window.location.href = "Logout.php";
            
        }
    </script>
</body>
</html>
