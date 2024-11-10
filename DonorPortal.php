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
            <button onclick="showPage('dashboard')">My Profile</button>
            <button onclick="showPage('donorRequest')">Donor Requests</button>
            <button onclick="showPage('patientList')">Patient List</button>
            <button onclick="logout()">Logout</button>
        </div>

        <!-- Content Area -->
        <div class="content">
            <!-- My Profile (Dashboard) Page -->
            <div id="dashboard" class="page">
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
                    <li>Donated on: </li>
                    <!-- Add more history records as needed -->
                </ul>
            </div>

            <!-- Donor Requests Page -->
            <div id="donorRequest" class="page" style="display:none;">
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
            </div>

            <!-- Patient List Page -->
            <div id="patientList" class="page" style="display:none;">
                <h2>Patient List</h2>
                <p>Available Patients:</p>
                <ul>
                    <li>
                        <span>Jane Doe (Blood Group: O+)</span>
                        <button>Donate</button>
                    </li>
                    <!-- More donors can be added similarly -->
                </ul>
            </div>
        </div>
    </div>

    <script>
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => page.style.display = 'none');
            document.getElementById(pageId).style.display = 'block';
        }

        function logout() {
            alert("You have logged out.");
            window.location.href = "Login.html";
        }
    </script>
</body>
</html>
