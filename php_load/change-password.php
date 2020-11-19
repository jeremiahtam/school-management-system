<?php

include("../inc/session.inc.php");
include("../inc/db.inc.php");
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['old_password'])&&isset($_POST['new_password'])&&isset($_POST['repeat_new_password'])){

  $old_password=$_POST['old_password'];
  $new_password=$_POST['new_password'];	
  $repeat_new_password=$_POST['repeat_new_password'];
  $md5_old_password = md5($old_password);

  //check if old passwordd matches the one in database
  $sql= mysqli_query($conn,"SELECT password FROM admin WHERE id='1'");
  $row = mysqli_fetch_assoc($sql);
  //database password
  $db_password = htmlentities($row['password']);
  if($md5_old_password==$db_password){
    $new_password=md5($new_password);
    $sql2= mysqli_query($conn,"UPDATE admin SET password='$new_password' WHERE id='1'");
    echo "<p class='alert-success'>Your password has been changed successfully!</p>";
    }else{
    echo "<p class='alert-danger'>Your old password is incorrect!</p>";
    }
//security measure
}//end security measure

?>