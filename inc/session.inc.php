<?php 
session_start(); 
if(!isset($_SESSION["loggedin_user"])){
  }
  else
  {
  $loggedin_user = $_SESSION["loggedin_user"];
  }
if(!isset($_SESSION["loggedin_user_type"])){
  }
  else
  {
  $loggedin_user_type = $_SESSION['loggedin_user_type'];
  }



?>