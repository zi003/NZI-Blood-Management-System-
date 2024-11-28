<?php

    session_start();

    include "connect.php";

    $stmt =  $con->prepare("select count(*) from requestdonor where DID = ?");
    $stmt->bind_param("i",$_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($number_of_requests);

    $stmt->fetch();
    $stmt->close();
    $con->close();

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

                <div class="button-with-badge">
                <button type = "submit" name="action" value="donorRequest">Donation Requests</button>
                
                <?php if ($number_of_requests >= 0): ?>
                <span class="badge"><?= $number_of_requests?></span>
                <?php endif; ?>
                </div>

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
                <h2>Donor Profile</h2>
                <br> </br>
                <ul>
                    <li>Name:<?php echo $_SESSION['name'] ?></li>
                    <li>Phone Number: <?php echo $_SESSION['phone_num']; ?> 
                    <a href="edit_contact.html">
                    <button class="edit-phone-btn">Edit Phone</button>
                     </a>
                    </li>

                    <li>Blood Group:<?php echo $_SESSION['blood_grp'] ?></li>
                    <li>Location:<?php echo $_SESSION['location'] ?> </li>
                    <li>Last Blood Donation Date:</b><?php echo $_SESSION['last_dondate'] ?> </li>
                </ul>
              
                
                <br> <br>
                <h3>Upcoming Donations:</h3>
                <br></br>
                <?php 

                   include 'connect.php';

                   $stmt = $con->prepare("select p.Firstname, p.Lastname, p.phone_number, don.PID, br.donation_date, br.donation_time, br.location, br.blood_type from donor as d join donations as don on (d.id = don.DID) join bloodrequest as br on (br.PID = don.PID) join person as p on (br.PID = p.ID) where don.DID = ? and don.donation_date = br.donation_date");
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
                else{
                    echo "No upcoming donations.";
                }
                $stmt->close();
                $con->close();
                                
            ?>
           
            <!-- Donor Requests Page -->
            <?php elseif($page == "donorRequest"): ?>
                <h2>Patient Requests</h2>
                <br></br>
               
                <?php
                 
                 include "connect.php";

                 $stmt = $con->prepare("select p.ID, p.Firstname, p.Lastname, p.phone_number, br.donation_date, br.donation_time, br.location, br.blood_type, br.blood_group from requestdonor as rd join bloodrequest as br on (rd.PID = br.PID) join person as p on (rd.PID = p.ID) where rd.DID = ? and (select count(*) from donations where PID = p.ID and donation_date = br.donation_date) = 0 ");
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
                        echo "<input type='submit' name = 'choice' value='Accept' class = 'button-accept'>";
                        echo "<input type='submit' name = 'choice' value='Reject' class = 'button-reject' >";
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
                <br></br>
                
               
                <!--
                <ul>
                    <li>
                        <span>Jane Doe (Blood Group: O+)</span>
                        <button>Donate</button>
                    </li> -->
        <?php
         
          include 'connect.php';

          
          $stmt = $con->prepare("select p.ID,Firstname, Lastname, phone_number, r.blood_group, r.blood_type, r.location,r.donation_date, r.donation_time, 
                                  (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                                         sin(radians(?)) * sin(radians(latitude)))) AS distance from person as p
                                 join bloodrequest as r on p.ID = r.PID where (select count(*) from donations as d where d.PID = p.ID and d.donation_date = r.donation_date)=0 
                                 and r.blood_group = ? order by distance");
          $stmt->bind_param("ddds",$_SESSION['latitude'], $_SESSION['longitude'],$_SESSION['latitude'],$_SESSION['blood_grp']);
          $stmt->execute();

          $result = $stmt->get_result();

          if($result->num_rows>0)
          {
            if ($result->num_rows > 0) {
                // data in table format
                 echo "<table>";
                 echo "<tr><th> First Name </th><th>  Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>    Donation Location  </th><th>    Donation Date  </th><th>    Donation Time </th><th>    Blood Type  </th></tr>";
                
                // Fetch and output each row of data
                while ($row = $result->fetch_assoc()) {
                   // if($row['engaged']==true)
                     //  continue;
                   // echo "<table>";
                    //echo "<tr><th> First Name </th><th>  Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>    Donation Location  </th><th>    Donation Date  </th><th>    Donation Time </th><th>    Blood Type  </th></tr>";
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
                    echo "<input type='submit' name = 'choice' value='Donate'>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                
            }
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
    <

</body>
</html>
