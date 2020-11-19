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
  $check = mysqli_query($conn,"SELECT * FROM sporting_activities WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
  $num_rows = mysqli_num_rows($check);
  //if it does not exist, show add form
if($num_rows==1){
  //if the data does not already exists
  while($row = mysqli_fetch_assoc($check)){

	$id = htmlentities($row['id']);
	$indoor_games = htmlentities($row['indoor_games']);
	$balls_games = htmlentities($row['balls_games']);
	$combat_games = htmlentities($row['combat_games']);
	$track = htmlentities($row['track']);
	$jumps = htmlentities($row['jumps']);
	$throws = htmlentities($row['throws']);
	$swimming = htmlentities($row['swimming']);
	$weight_lifting = htmlentities($row['weight_lifting']);
	$comment = htmlentities($row['comment']);
  }
}
	//trying to get the grade for the student's particular class
	//get the teacher's class
	$classTeacherDetails = new classTeacherDetails();
	list($class_name,$class_arm)= $classTeacherDetails -> get_arm($conn,$loggedin_user);
	$subjectResult = new subjectResult();
	
	echo"
	<form method='post' id='add_sports_form' name='add_sports_form'>
	<div class='sports-info'></div><!--where alerts are displayed-->            
	<div class='row'>
		<div class='form-group col-md-3'>                     
			<label for='indoor_games'>Indoor Games </label>
			<select class='form-control' id='indoor_games' name='indoor_games'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";
				 //check if content exist in the database
				 if(isset($indoor_games)){ 				 
					//select db content
				   if($indoor_games==$grade_letter_db){echo "selected='true'";}	
				 }			 
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->		   
		<div class='form-group col-md-3'>                     
			<label for='balls_games'>Balls Games</label>
			<select class='form-control' id='balls_games' name='balls_games'>
				<option value=''>-select grade-</option>";
				//$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
				list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
				while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";
				 //check if content exist in the database
				 if(isset($balls_games)){ 				 
					//select db content
				   if($balls_games==$grade_letter_db){echo "selected='true'";}				 
				 }
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='combat_games'>Combative Games</label>
			<select class='form-control' id='combat_games' name='combat_games'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";				 
				 //check if content exist in the database
				 if(isset($combat_games)){ 				 
					//select db content
				   if($combat_games==$grade_letter_db){echo "selected='true'";}				 
				 }
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='track'>Track</label>
			<select class='form-control' id='track' name='track'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";				 
				 //check if content exist in the database
				 if(isset($track)){ 				 
					//select db content
				   if($track==$grade_letter_db){echo "selected='true'";}
				}				 
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
	</div><!--end row-->
	<div class='row'>
		<div class='form-group col-md-3'>                     
			<label for='jumps'>Jumps</label>
			<select class='form-control' id='jumps' name='jumps'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";				 
				 //check if content exist in the database
				 if(isset($jumps)){ 				 
					//select db content
				   if($jumps==$grade_letter_db){echo "selected='true'";}				 
				 }
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->		   
		<div class='form-group col-md-3'>                     
			<label for='throws'>Throws</label>
			<select class='form-control' id='throws' name='throws'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";				 
				 //check if content exist in the database
				 if(isset($throws)){ 				 
					//select db content
				   if($throws==$grade_letter_db){echo "selected='true'";}				 
				 }
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='swimming'>Swimming</label>
			<select class='form-control' id='swimming' name='swimming'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";				 
				 //check if content exist in the database
				 if(isset($swimming)){ 				 
					//select db content
				   if($swimming==$grade_letter_db){echo "selected='true'";}				 
				 }
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
			<label for='weight_lifting'>Weight Lifting</label>
			<select class='form-control' id='weight_lifting' name='weight_lifting'>
				<option value=''>-select grade-</option>";
			 //$grades_sql = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
			 list($subjects_query,$grades_query) = $subjectResult->subjects_by_class($conn,$class_name,$session);
			 while($grades_row = mysqli_fetch_assoc($grades_query)){
				 $grade_letter_db = htmlentities($grades_row['grade_letter']);
				 echo "<option value='$grade_letter_db'";
				 //check if content exist in the database
				 if(isset($combat_games)){ 				 
					//select db content
				   if($weight_lifting==$grade_letter_db){echo "selected='true'";}				 
				}
				  echo" >$grade_letter_db</option>";
				 }
			 echo"
			</select>
		</div><!--end col-->
	</div><!--end row-->
	<div class='form-group'>
		<label for='comment' class='col-form-label'>Comment</label>
		<textarea class='form-control' rows='1' id='comment' name='comment' placeholder='Comment'>"; if(isset($comment)){echo $comment;} echo"</textarea>
	</div><!--end form-group-->
	<button class='btn btn-outline-primary pull-right' type='submit' id='add_sports' name='add_sports'>Add Sporting Activity
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
	</button>
	</form>
	";
}

?>