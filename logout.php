<?php

  //destroys the session and the variables when user logs out
  session_start();
  session_unset();
  session_destroy();


  header("Location: Login.html");
  exit();

?>