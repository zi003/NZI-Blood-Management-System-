<?php   
  session_start();
  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Portal</title>
    <link rel="stylesheet" href="Portal(D).css">
</head>
<body>
    <div class="portal-container">
        <h1>Donor Portal</h1>

        <!-- Profile Section -->
        <div class="profile-section">
            <h2>Your Profile</h2>
            <div class="profile-bio">
                <p><strong>Name:</strong> <?php echo $_SESSION['name'] ?></p>
                <p><strong>Blood Group:</strong> <?php echo $_SESSION['blood_grp'] ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['email'] ?></p>
                <p><strong>Phone:</strong> <?php echo $_SESSION['phone_num'] ?></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">

            <form method="post">
                <button name="action" value="viewPatientList">View Patient List</button>
                <button name="action" value="viewUpcomingDonations">Upcoming Donations</button>
                <button name="action" value="viewDonationRequests">Donation Requests</button>
            </form>

            </div>
        </div>

        <div class = "action result">
         <?php  

          $con = new mysqli('localhost','root','','nzi blood management system');
          
          function viewPatientList($con){
            
          $stmt = $con->prepare("select * from person");
          $stmt->execute();

          $result = $stmt->get_result();

          if($result->num_rows>0)
          {
            if ($result->num_rows > 0) {
                // Output the data in a table format
                echo "<table>";
                echo "<tr><th> First Name </th><th>   Last Name  </th><th>    Blood Group  </th><th>    Contact Number  </th></tr>";
                
                // Fetch and output each row of data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "No results found.";
            }
        }
    }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = $_POST['action'] ?? '';
                switch ($action) {
                    case 'viewPatientList':
                        viewPatientList($con);
                        break;
                    case 'viewUpcomingDonations':
                        // Call upcoming donations function
                        break;
                    case 'viewDonationRequests':
                        // Call donation requests function
                        break;
                
                    }



          }
        
        
        ?>
       <!-- </div>
         Script for buttons (for demonstration purposes) -->
        <!--<script>
            function viewPatientList() {
                alert("Viewing Patient List...");
            }
            function viewUpcomingDonations() {
                alert("Viewing Upcoming Donations...");
            }
            function viewDonationRequests() {
                alert("Viewing Donation Requests...");
            }
        </script>
    </div>-->
       
</body>
</html>
