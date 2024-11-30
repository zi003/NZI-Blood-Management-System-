<?php

session_start();

include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['donation_date'] = $_POST['donation_date']; 
}
if($_SESSION['person_type'] == 'Donor'){
$stmt = $con->prepare("select d.PID, p.Firstname, p.Lastname from donations as d join person as p on (d.PID = p.ID) where donation_date = ? and DID = ?");
$stmt->bind_param("si",$_SESSION['donation_date'],$_SESSION['id']);

}
else if($_SESSION['person_type'] == 'Patient'){
$stmt = $con->prepare("select d.DID, p.Firstname, p.Lastname from donations as d join person as p on (d.DID = p.ID) where donation_date = ? and PID = ?");
$stmt->bind_param("si",$_SESSION['donation_date'],$_SESSION['id']);
   
}

$stmt->execute();
$stmt->bind_result($person_id,$person_firstname, $person_lastname);
$stmt->fetch();
$_SESSION['receiver_id'] = $person_id;
$_SESSION['receiver_name'] = $person_firstname. " " .$person_lastname; 
$stmt->close();


$stmt =  $con->prepare("select RID, SID, message, time_stamp from messages where (RID = ? and SID = ?) or (SID = ? and RID = ?) order by time_stamp desc");
$stmt->bind_param("iiii",$_SESSION['id'],$person_id, $_SESSION['id'], $person_id);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    <style>
        /*styling the chats */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .chat-container {
            max-width: 100%;
            margin: 0px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 100vh; /* Full height of the viewport */
         display: flex;
            flex-direction: column;
        }

        .chat-box {
            flex: 1; /* Take up available space */
            padding: 20px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .message {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            position: relative;
            display: inline-block;
            flex-direction: column;
            max-width: 70%;
        }

        .sent {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            margin-left: auto; 
        }

        .received {
            background-color:  #ff4d4d;
            color: black;
            align-self: flex-start;
            margin-right: auto;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .message-body {
            margin-top: 5px;
            font-size: 1rem;
        }

        .time {
            font-size: 0.8rem;
            color: gray;
            margin-top: 5px;
            text-align: right;
        }

        .input-container {
            display: flex;
            padding: 15px;
            border-top: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .input-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
            font-size: 1rem;
        }

        .input-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
        }

        .input-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
        <div class="chat-container">
        <div class="chat-box">
            
            <?php
             if($result->num_rows>0){
                
                while($row = $result->fetch_assoc()){

                    if ($row['SID'] == $_SESSION['id'] && $row['RID']== $person_id) { 
                        echo "<div class='message sent'>"; 
                        echo "<div class='message-header'>";
                        echo "<span>You:</span>"; 
                        echo "<span class='time'>" . date('h:i A', strtotime($row['time_stamp'])) . "</span>";
                        echo "</div>";
                        echo "<div class='message-body'>" . htmlspecialchars($row['message']) . "</div>";
                        echo "</div>";
                    } else if($row['SID'] == $person_id && $row['RID']== $_SESSION['id']) {
                        echo "<div class='message received'>"; // 
                        echo "<div class='message-header'>";
                        echo "<span>" . htmlspecialchars($_SESSION['receiver_name']) . ":</span>"; // 
                        echo "<span class='time'>" . date('h:i A', strtotime($row['time_stamp'])) . "</span>";
                        echo "</div>";
                        echo "<div class='message-body'>" . htmlspecialchars($row['message']) . "</div>";
                        echo "</div>";
                    }
             }
            }else{
                echo 'No message to display';
            }
            $stmt->close();
            $con->close();
    ?>
    <div class="input-container">
        <form action = "store_message.php" method = "POST">
        <input type="text" name = "message" placeholder="Type a message...">
        <button>Send</button>
    </div>
</body>
</html>