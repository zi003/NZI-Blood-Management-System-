<?php
   
   session_start();

   $donor_id = $_SESSION['id'];
   $patient_id = $_POST['patient_id'];
   $donation_date = $_POST['donation_date'];
   $donation_time = $_POST['donation_time'];

   
   include 'connect.php';

   $page = $_POST['choice'];

   // handling if a donor chooses to accept a request or chooses to donate
   if($page == 'Accept' || $page == 'Donate'){
   $stmt =  $con->prepare("select br.donation_date, br.blood_type from donations as d join bloodrequest as br on (d.PID = br.PID) where DID = ? ");
   $stmt->bind_param("i",$donor_id);
   $stmt->execute();
   $res1 =  $stmt->get_result();
   $flag = true;
   while($row = $res1->fetch_assoc()){
     
     //checking if the donation I want to choose overlap with any scheduled donation of mine
      $scheduled_don_bloodtype = $row['blood_type'];
      $scheduled_don_date = $row['donation_date'];
      $difference = abs(strtotime($scheduled_don_date)-strtotime($donation_date));
      $difference_days = $difference / (60*60*24);

      if(($scheduled_don_bloodtype == 'blood' && $difference_days >= 120) || ($scheduled_don_bloodtype == 'platelet' && $difference_days >= 14))
      {
        continue;
      }
      else{
        $flag = false;
        break;
      }
     
   }
   $stmt->close();
   if($flag){
   
   //checking if I am eligible to donate based on last date of donation
   $stmt = $con->prepare("select count(*) from person as p join donor as d on (p.ID = d.id) where ((last_btype_donated = 'blood' and last_donation_date< DATE_SUB(?, INTERVAL 3 month)) or (last_btype_donated = 'platelet' and last_donation_date< DATE_SUB(?, INTERVAL 2 week))) and p.id = ?");
   $stmt->bind_param("ssi",$donation_date, $donation_date, $donor_id,);
   $stmt->execute();
   $stmt->bind_result($num_rows);
   $stmt->fetch();

   //if eligible insertng into donations table the donor-patient info
   if($num_rows>0){
   $stmt->close();
   $stmt = $con->prepare("insert into donations VALUES (?,?,?,?)");
   $stmt->bind_param("iiss",$donor_id, $patient_id, $donation_date, $donation_time);

   

   if($stmt->execute())
   {

    $stmt->close();
    //setting engaged to be true once a donation is chosen
    $stmt = $con->prepare("update person set engaged = true where ID = ? or ID = ?");
    $stmt->bind_param("ii",$patient_id,$donor_id);

    
    if($stmt->execute()){
     $stmt->close();
     //removing the request sent to this donor if a request is accepted
     $stmt = $con->prepare("delete from requestdonor where PID = ? and DID = ?");
     $stmt->bind_param("ii",$patient_id,$donor_id);
     $stmt->execute();
     $stmt->close();
     
     //checking if any bloodrequest is yet to be accepted
     $stmt = $con->prepare("select count(*) from bloodrequest as br left join donations as d on (br.PID = d.PID) where br.PID = ? and br.donation_date = d.donation_date and DID is null");
     $stmt->bind_param("i",$patient_id);
     $stmt->execute();
     $stmt->bind_result($res);
     $stmt->fetch();
     $stmt->close();

     //if all the bloodrequests have been accepted, the patients requests to donors are removed
     if($res==0)
     {
      $stmt = $con->prepare("delete from requestdonor where PID = ?");
      $stmt->bind_param("i",$patient_id);
      $stmt->execute();
     }
    
     $stmt->close();
     $con->close();
     echo'<script>
       
       alert("Successfully chosen donor for donation!");
       window.location.href = "DonorPortal.php";

     </script>';



     exit();
    }
  }
   }
   else{
    
    echo'<script>
       
    alert("You cannot donate!");
    window.location.href = "DonorPortal.php";

     </script>';
   }
   }else{
  
    $stmt = $con->prepare("delete from requestdonor where PID = ? and DID = ?");
    $stmt->bind_param("ii",$patient_id,$donor_id);
    $stmt->execute();
    $stmt->close();
    $con->close();

    echo'<script>
       
    alert("You are not eligible to donate!");
    window.location.href = "DonorPortal.php";

     </script>';
   }
  }
  else{
    //if request is rejected then that request is deleted from request donor table
    $stmt = $con->prepare("delete from requestdonor where PID = ? and DID = ?");
    $stmt->bind_param("ii",$patient_id,$donor_id);
    $stmt->execute();
    $stmt->close();
    $con->close();

    echo'<script>
       
    alert("Patient Request Deleted Succesfully!");
    window.location.href = "DonorPortal.php";

     </script>';

  }
  


?>