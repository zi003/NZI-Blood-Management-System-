<?php

   $con = new mysqli('localhost', 'root', '', 'nzi blood management system');

   if($con->connect_error)
   {
    die("Connection Faied!");
   }


?>