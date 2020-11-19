<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");

$query = "SELECT * FROM school_details WHERE id='1' ";	
$result = mysqli_query($conn,$query);

$num_rows = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

  $school_details_id = htmlentities($row['id']);
  $name = htmlentities($row['name']);
  $email = htmlentities($row['email']);
  $address = htmlentities($row['address']);
  $phone_number = htmlentities($row['phone_number']);
  $website = htmlentities($row['website']);
  $date = htmlentities($row['date']);
  $time = htmlentities($row['time']);
  

  echo"
 <form method='post' id='update_settings_form' name='update_settings_form'>
   <div class='update-settings-info'></div><!--where alerts are displayed-->            
   <div class='row'>
	 <div class='form-group col-md-4'>                     
	   <label for='name'>School Name</label>
	   <span class='help-block'>Name of school</span>
	   <input type='text' class='form-control' id='name' name='name' placeholder='School Name' value='".$name."'>
	 </div><!--end col-->		   
	 <div class='form-group col-md-4'>                     
	   <label for='email'>School Email</label>
	   <span class='help-block'>School email address</span>
	   <input type='text' class='form-control' id='email' name='email' placeholder='School Email' value='".$email."'>
	 </div><!--end col-->		   
	 <div class='form-group col-md-4'>                     
	   <label for='address'>School Address</label>
	   <span class='help-block'>Location of school</span>
	   <input type='text' class='form-control' id='address' name='address' placeholder='School Location' value='".$address."'>
	 </div><!--end col-->
   </div><!--end row-->		   
   <div class='row'>
	 <div class='form-group col-md-6'>                     
	   <label for='phone_number'>Phone Number</label>
	   <span class='help-block'>Phone Number</span>
	   <input type='text' class='form-control' id='phone_number' name='phone_number' placeholder='Phone Number' value='".$phone_number."'>
	 </div><!--end col-->		   
	 <div class='form-group col-md-6'>                     
	   <label for='website'>School Website</label>
	   <span class='help-block'>Official School Website</span>
	   <input type='text' class='form-control' id='website' name='website' placeholder='School Website' value='".$website."'>
	 </div><!--end col-->
 </div><!--end row-->
   <button type='submit' class='btn btn-outline-primary' name='update_settings' id='update_settings'>Update Settings
   <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
 </form><!--end update_settings_form-->

  ";
//end while loop
?>