<?php
   session_start();
   
   $id = $_SESSION['id'];
   $con = new mysqli('localhost','root','','nzi blood management system');
   $stmt = $con->prepare("select b.donation_date from bloodrequest as b where PID = ? and (select count(*) from donations as d join bloodrequest as br on d.PID = br.PID where d.donation_date = b.donation_date) = 0");
   $stmt->bind_param("s",$id);

   $stmt->execute();
   $result = $stmt->get_result();
   
   $stmt->close();
   $con->close();

   $current_date = date('Y-m-d');

   $min_date = date('Y-m-d',strtotime('+1 day'));
   $max_date = date('Y-m-d',strtotime('+3 months'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Patient Dashboard</title>
    <link rel="stylesheet" href="demo.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Blood Donation</h2>
            <form method = "post">
            <button type = "submit" name="action" value="dashboard">My Profile</button>
            <?php if($result->num_rows==0 || $_SESSION['patient_type'] == "Regular"):?>
            <button type = "submit" name="action" value="createRequest">Create Requests</button>
            <?php endif; ?>
            <?php if($result->num_rows!=0):?>
            <button type = "submit" name="action" value="donorList">Donor List</button>
            <?php endif; ?>
            <button type = "button" onclick="logout()">Logout</button>
            </form>
        </div>

        <?php 
        $page = isset($_POST['action'])? $_POST['action']:'dashboard';
       ?>

        <!-- Content Area -->
        <div class="content">
            <!-- My Profile Page -->
            <!--<div id="patientProfile" class="page">-->
            <?php if($page == "dashboard"): ?>
                <h2>My Profile</h2>
                <br> </br>
                <ul>
                    <li>Name: <?php echo $_SESSION['name']  ?></li>
                    <li>Phone Number:<?php echo $_SESSION['phone_num']?> </li>
                    <li>Blood Group:<?php echo $_SESSION['blood_grp']?> </li>
                    <li>Location:<?php echo $_SESSION['location']?> </li>
                    <li>Patient Type:<?php echo $_SESSION['patient_type']?> </li>
                    <li></li>
                </ul>
                <h3>Pending Requests:</h3>
                <br></br>
                <?php
                 include 'connect.php';

                 $stmt = $con->prepare("select p.ID, p.Firstname, p.Lastname, p.phone_number, p.blood_group, br.donation_date, br.donation_time, br.location, br.blood_type from requestdonor as rd join person as p on (rd.DID = p.ID) join bloodrequest as br on (br.PID = rd.PID) where rd.PID = ? and (select count(*) from donations where PID = rd.PID and donation_date = br.donation_date) = 0 ");
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

              }else{
               
                echo "\n No Pending Requests. \n";
              }
              $stmt->close();
              $con->close();
                 
            ?>
                <br></br>
                <br></br>
                <h3>Upcoming Donations:</h3>
                <br></br>
                <?php 

                   include 'connect.php';

                   $stmt = $con->prepare("select p.ID, p.Firstname, p.Lastname, p.phone_number, don.PID, br.donation_date, br.donation_time, br.location, br.blood_type from donor as d join donations as don on (d.id = don.DID) join bloodrequest as br on (br.PID = don.PID) join person as p on (don.DID = p.ID) where don.PID = ? and don.donation_date = br.donation_date ");
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
                  
                
                    echo "<td>";
                    echo "<form action='complete.php' method='post'>";
                    echo "<input type='hidden' name='donor_id' value='" . htmlspecialchars($row['ID']) . "'>";
                    echo "<input type='hidden' name='donation_date' value='" . htmlspecialchars($row['donation_date']) . "'>";
                    echo "<input type='hidden' name='blood_type' value='" . htmlspecialchars($row['blood_type']) . "'>";
                    echo "<input type='submit' value='Mark Complete'>";
                    echo "</form>";
                    echo "</td>";
                   
                    echo "</tr>";

                   }
                   echo "</table>";

                }else{
               
                    echo "\n No Upcoming Donations. \n";
                  }
                $stmt->close();
                $con->close();
                                
            ?>
                
           <!-- </div>-->

            <!-- Create Request Page -->
             
            <?php elseif($page == "createRequest"): ?>
            <!--<div id="createRequest" class="page" style="display:none;">-->
                <h2>Create Request</h2>
                <form action = "requestBlood.php" method = "POST">
                    <!-- added to identify the form -->
                    <input type="hidden" name="form_type" value="create_request">

                    <label for="requestType">Blood Type:</label>
                    <select id="requestType" name="requestType">
                        <option value="blood">Blood</option>
                        <option value="platelet">Platelet</option>
                    </select>

                    <label for="bloodGroup">Blood Group:</label>
                    <select id="bloodGroup" name="bloodGroup">
                        <option value="<?= $_SESSION['blood_grp'] ?>" selected>
                            
                        <?= $_SESSION['blood_grp'] ?>
                        </option>
                        
                    </select>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" 
                       
                    min = <?php echo $min_date; ?>
                    max = <?php echo $max_date; ?>

                    required>

                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" required>

                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" placeholder="Enter location" required>

                    <button type="submit" name = "submit_request">Submit Request</button>
                </form>
           <!-- </div>-->
        
            <!-- Donor List Page -->
            <?php elseif($page == "donorList"): ?>
           <!-- <div id="donorList" class="page" style="display:none;">-->
                <h2>Donor List</h2>
               
                <br></br>
                <?php

                  include "connect.php";

                  $stmt = $con->prepare("select p.ID, Firstname, Lastname, phone_number, location, blood_group from person as p join donor as d on (p.ID = d.id) where p.engaged = 0 and ((last_btype_donated = 'blood' and last_donation_date< DATE_SUB(CURDATE(), INTERVAL 3 month)) or (last_btype_donated = 'platelet' and last_donation_date< DATE_SUB(CURDATE(), INTERVAL 2 week))) and (select count(*) from requestdonor as rd where (p.ID = rd.DID) and PID = ?)=0 and p.blood_group = ?");
                  $stmt->bind_param("is",$_SESSION['id'], $_SESSION['blood_grp']);
                  $stmt-> execute();

                  $res = $stmt->get_result();

                  if($res->num_rows >0)
                  {
                    echo "<table>";
                    echo "<tr><th> First Name </th><th>  Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th><th>   Location  </th></tr>";
                    
                    // Fetch and output each row of data
                    while ($row = $res->fetch_assoc()) {
                       
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
    
                        echo "<td>";
                        echo "<form action='request.php' method='post'>";
                        echo "<input type='hidden' name='donor_id' value='" . htmlspecialchars($row['ID']) . "'>";
                       // echo "<input type='hidden' name='donation_date' value='" . htmlspecialchars($row['donation_date']) . "'>";
                        //echo "<input type='hidden' name='donation_time' value='" . htmlspecialchars($row['donation_time']) . "'>";
                        echo "<input type='submit' value='Request'>";
                        echo "</form>";
                        echo "</td>";
    
                        echo "</tr>";
                    


                  }
                  echo "</table>";
                }else {
                    echo "No Available Donors.";
                }
                $stmt->close();
                $con->close();

                ?>

            <!--</div>-->
            <?php endif; ?>
        </div>
   <!-- </div>-->

    <script>
      /*  function showPage(pageId) {
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

