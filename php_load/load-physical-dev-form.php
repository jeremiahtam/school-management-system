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
  $check = mysqli_query($conn,"SELECT * FROM physical_dev WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
  $num_rows = mysqli_num_rows($check);

	if($num_rows==1){
		//if the data does not already exixts
		while($row = mysqli_fetch_assoc($check)){	
			$id = htmlentities($row['id']);
			$start_height = htmlentities($row['start_height']);
			$end_height = htmlentities($row['end_height']);
			$start_weight = htmlentities($row['start_weight']);
			$end_weight = htmlentities($row['end_weight']);
			$illness_days = htmlentities($row['illness_days']);
			$nature_of_illness = htmlentities($row['nature_of_illness']);
		}
	}
	  echo"
	<form method='post' id='add_physical_dev_form' name='add_physical_dev_form'>
	<div class='physical-dev-info'></div><!--where alerts are displayed-->            
	<div class='row'>
		<div class='form-group col-md-3'>                     
			<label for='start_height'>Start of term height (m)</label>
			<input class='form-control' id='start_height' name='start_height' "; if(isset($start_height)){echo "value='$start_height'";} echo" placeholder='Start of term heigh (m)'/>
		</div><!--end col-->		   
		<div class='form-group col-md-3'>                     
			<label for='end_height' class='col-form-label'>End of term height (m)</label>
			<input class='form-control' id='end_height' name='end_height' "; if(isset($end_height)){echo "value='$end_height'";} echo" placeholder='End of term height (m)'/>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='start_weight' class='col-form-label'>Start of term weight (kg)</label>
			<input class='form-control' id='start_weight' name='start_weight' "; if(isset($start_weight)){echo "value='$start_weight'";} echo" placeholder='Start of term weight (kg)'/>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='end_weight'>End of term weight (kg)</label>
			<input class='form-control' id='end_weight' name='end_weight' "; if(isset($end_weight)){echo "value='$end_weight'";} echo" placeholder='End of term weight(kg)'/>
		</div><!--end col-->		   
	</div><!--end row-->
	<div class='row'>
		<div class='form-group col-md-6'>                     
			<label for='days_of_illness' class='col-form-label'>Days absent due to illness</label>
			<input class='form-control' id='days_of_illness' name='days_of_illness'"; if(isset($illness_days)){echo "value='$illness_days'";} echo" placeholder='Days absent due to illness'/>
		</div><!--end col-->
		<div class='form-group col-md-6'>                     
			<label for='nature_of_illness' class='col-form-label'>Nature of illness</label>
			<input class='form-control' id='nature_of_illness' name='nature_of_illness' "; if(isset($nature_of_illness)){echo "value='$nature_of_illness'";} echo" placeholder='Nature of illness'/>
		</div><!--end col-->
	</div><!--end row-->
	<button class='btn btn-outline-primary pull-right' type='submit' id='add_physical_dev' name='add_physical_dev'>Add Physical Development
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
	</button>
	</form>
";
	
}
?>