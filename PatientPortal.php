<?php
   session_start();
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
            <button type = "submit" name="action" value="createRequest">Create Requests</button>
            <button type = "submit" name="action" value="donorList">Donor List</button>
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
                <p>Account Information:</p>
                <ul>
                    <li>Name: <?php echo $_SESSION['name']  ?></li>
                    <li>Phone Number:<?php echo $_SESSION['phone_num']?> </li>
                    <li>Blood Group:<?php echo $_SESSION['blood_grp']?> </li>
                    <li>Location:<?php echo $_SESSION['location']?> </li>
                    <li>Patient Type:<?php echo $_SESSION['patient_type']?> </li>
                    <li></li>
                </ul>
            </div>

            <!-- Create Request Page -->
             
            <?php elseif($page == "createRequest"): ?>
            <!--<div id="createRequest" class="page" style="display:none;">-->
                <h2>Create Request</h2>
                <form>
                    <label for="requestType">Request Type:</label>
                    <select id="requestType" name="requestType">
                        <option value="blood">Blood</option>
                        <option value="plasma">Plasma</option>
                    </select>

                    <label for="bloodGroup">Blood Group:</label>
                    <select id="bloodGroup" name="bloodGroup">
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" required>

                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" placeholder="Enter location" required>

                    <button type="submit">Submit Request</button>
                </form>
            </div>

            <!-- Donor List Page -->
            <?php elseif($page == "donorList"): ?>
           <!-- <div id="donorList" class="page" style="display:none;">-->
                <h2>Donor List</h2>
                <p>Available Donors:</p>
                <ul>
                    <li>
                        <span>John Smith (Blood Group: B+)</span>
                        <button>Request Donation</button>
                    </li>
                    <!-- More donors can be added similarly -->
                </ul>

            </div>
            <?php endif; ?>
        </div>
    </div>

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

