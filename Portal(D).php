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
                <button onclick="viewPatientList()">View Patient List</button>
                <button onclick="viewUpcomingDonations()">Upcoming Donations</button>
                <button onclick="viewDonationRequests()">Donation Requests</button>
            </div>
        </div>

        <!-- Script for buttons (for demonstration purposes) -->
        <script>
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
    </div>
</body>
</html>
