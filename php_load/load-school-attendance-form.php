<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");
include("../classes/develop-php-library.php");

$reg_no = htmlentities($_POST['reg_no']);
$session = htmlentities($_POST['session']);
$term = htmlentities($_POST['term']);

//if $reg_no, $session or $term is empty
if($reg_no==''||$session==''||$term==''){

  }else{
	//get student details
	$studentDetails = new studentDetails();
	list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

	//else if neither $reg_no, $session or $term is empty
  //check if this data already exixts
  $check = mysqli_query($conn,"SELECT * FROM attendance WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
  $num_rows = mysqli_num_rows($check);
  if($num_rows==1){
	while($row = mysqli_fetch_assoc($check)){
  	$id = htmlentities($row['id']);
  	$days_present = htmlentities($row['days_present']);
  	$total_days = htmlentities($row['total_days']);
  	$punctual = htmlentities($row['punctual']);
	$absent = htmlentities($row['absent']);
	}
  }

  echo"
	<form method='post' id='add_school_attendance_form' name='add_school_attendance_form'>
	<div class='school-attendance-info'></div><!--where alerts are displayed-->            
	<div class='row'>
		<div class='form-group col-md-4'>                     
			<label for='total_days'>Total Days</label>
			<input class='form-control'"; if(isset($total_days)){echo "value='$total_days'";} echo"id='total_days' name='total_days' placeholder='Total number of days'/>
		</div><!--end col-->		   
		<div class='form-group col-md-4'>                     
			<label for='days_present' class='col-form-label'>Days Present</label>
			<input class='form-control'"; if(isset($days_present)){echo "value='$days_present'";} echo" id='days_present' name='days_present' placeholder='Days Present'/>
		</div><!--end col-->
		<div class='form-group col-md-4'>                     
			<label for='days_punctual' class='col-form-label'>Days Punctual</label>
			<input class='form-control'"; if(isset($punctual)){echo "value='$punctual'";} echo"  id='days_punctual' name='days_punctual' placeholder='Days Punctual'/>
		</div><!--end col-->
	</div><!--end row-->
	<button class='btn btn-outline-primary pull-right' type='submit' id='add_school_attendance' name='add_school_attendance'>Add School Attendance
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
	</button>
	</form>
  ";	  
 }
?>