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
                <?php 

                   include 'connect.php';

                   $stmt = $con->prepare("select p.Firstname, p.Lastname, p.phone_number, don.PID, br.donation_date, br.donation_time, br.location, br.blood_type from donor as d join donations as don on (d.id = don.DID) join bloodrequest as br on (br.PID = don.PID) join person as p on (br.PID = p.ID) where don.DID = ?");
                   $stmt->bind_param("i",$_SESSION['id']);
                   $stmt->execute();
                   $result = $stmt->get_result();

                   if($result->num_rows>0)
                   {
                    echo "<table>";
                    echo "<tr><th> First Name </th><th> Last Name </th><th> Contact Number </th><th> Donation Date  </th><th>  Donation Time  </th><th>  Donation Location  </th><th>  Blood Type </th></tr>";
                
                // Fetch and output each row of data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['donation_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['donation_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                    echo "</tr>";
                

                   }
                   echo "</table>";

                }
                $stmt->close();
                $con->close();
                                
            ?>
           
            <!-- Donor Requests Page -->
            <?php elseif($page == "donorRequest"): ?>
                <h2>Patient Requests</h2>
                <p>Patient Requests:</p>
               
                <?php
                 
                 include "connect.php";

                 $stmt = $con->prepare("select p.ID, p.Firstname, p.Lastname, p.phone_number, br.donation_date, br.donation_time, br.location, br.blood_type, br.blood_group from requestdonor as rd join bloodrequest as br on (rd.PID = br.PID) join person as p on (rd.PID = p.ID) where rd.DID = ? ");
                 $stmt->bind_param("i",$_SESSION['id']);
                 $stmt->execute();
                 $result = $stmt->get_result();

                 if($result->num_rows>0){
                    echo "<table>";
                    echo "<tr><th> First Name </th><th>  Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>    Donation Location  </th><th>    Donation Date  </th><th>    Donation Time </th><th>    Blood Type  </th></tr>";
                    
                    // Fetch and output each row of data
                    while ($row = $result->fetch_assoc()) {
                       
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['donation_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['donation_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
    
                        echo "<td>";
                        echo "<form action='donate.php' method='post'>";
                        echo "<input type='hidden' name='patient_id' value='" . htmlspecialchars($row['ID']) . "'>";
                        echo "<input type='hidden' name='donation_date' value='" . htmlspecialchars($row['donation_date']) . "'>";
                        echo "<input type='hidden' name='donation_time' value='" . htmlspecialchars($row['donation_time']) . "'>";
                        echo "<input type='submit' value='Accept'>";
                        echo "</form>";
                        echo "<td";
    
                        echo "</tr>";
                    
                }
                echo "</table>";
             } else {
                    echo "No Requests.";
                }

                $stmt->close();
                $con->close();
                 
 
                 ?>

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
         
          include 'connect.php';

          
          $stmt = $con->prepare("select p.ID,Firstname, Lastname, phone_number, engaged, r.blood_group, r.blood_type, r.location,r.donation_date, r.donation_time from person as p join bloodrequest as r on p.ID = r.PID");
          $stmt->execute();

          $result = $stmt->get_result();

          if($result->num_rows>0)
          {
            //if ($result->num_rows > 0) {
                // Output the data in a table format
                echo "<table>";
                echo "<tr><th> First Name </th><th>  Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>    Donation Location  </th><th>    Donation Date  </th><th>    Donation Time </th><th>    Blood Type  </th></tr>";
                
                // Fetch and output each row of data
                while ($row = $result->fetch_assoc()) {
                    if($row['engaged']==true)
                       continue;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['donation_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['donation_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";

                    echo "<td>";
                    echo "<form action='donate.php' method='post'>";
                    echo "<input type='hidden' name='patient_id' value='" . htmlspecialchars($row['ID']) . "'>";
                    echo "<input type='hidden' name='donation_date' value='" . htmlspecialchars($row['donation_date']) . "'>";
                    echo "<input type='hidden' name='donation_time' value='" . htmlspecialchars($row['donation_time']) . "'>";
                    echo "<input type='submit' value='Donate'>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                
                
                echo "</table>";
            }
         } else {
                echo "No results found.";
            }
            $stmt->close();
            $con->close();
        
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
