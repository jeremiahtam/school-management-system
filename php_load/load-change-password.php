<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");

echo"
 <form method='post' id='change_password_form' name='change_password_form'>
   <div class='change-password-info'></div><!--where alerts are displayed-->            
	 <div class='form-group'>                     
	   <label for='old_password'>Old Password</label>
	   <span class='help-block'>Enter your old password</span>
	   <input type='password' class='form-control' id='old_password' name='old_password' placeholder='Old Password'>
	 </div><!--end form-group-->		   
	 <div class='form-group'>                     
	   <label for='new_password'>New Password</label>
	   <span class='help-block'>Enter your new password</span>
	   <input type='password' class='form-control' id='new_password' name='new_password' placeholder='New Password'>
	 </div><!--end form-group-->		   
	 <div class='form-group'>                     
	   <label for='repeat_new_password'>Re-enter Password</label>
	   <span class='help-block'>Re-enter your new password</span>
	   <input type='password' class='form-control' id='re_new_password' name='repeat_new_password' placeholder='Repeat New Password'>
	 </div><!--end form-group-->		   
   <button type='submit' class='btn btn-outline-primary' name='change_password' id='change_password'>Change Password
   <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
 </form><!--end change_password_form-->
  ";
//end while loop
?>