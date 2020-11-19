<?php
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['start_height'])&&isset($_POST['end_height'])&&isset($_POST['start_weight'])&&isset($_POST['end_weight'])&&isset($_POST['days_of_illness'])&&isset($_POST['nature_of_illness'])){

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$session = htmlentities($_POST['session']);
	$reg_no = htmlentities($_POST['reg_no']);
	$term = htmlentities($_POST['term']);

	$form_start_height = htmlentities($_POST['start_height']);
	$form_end_height = htmlentities($_POST['end_height']);
	$form_start_weight = htmlentities($_POST['start_weight']);
	$form_end_weight = htmlentities($_POST['end_weight']);
	$form_days_of_illness = htmlentities($_POST['days_of_illness']);
	$form_nature_of_illness = htmlentities($_POST['nature_of_illness']);
	$date = date('Y-m-d');
	$time = date('H:i:s');
	//get class teacher full name
	$class_teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
	$class_teacher_row = mysqli_fetch_assoc($class_teacher_sql);
	$class_teacher_name = htmlentities($class_teacher_row['fullname']);

	//get student details
	$studentDetails = new studentDetails();
	list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);
	
	//check if data already exixts
	$check = mysqli_query($conn,"SELECT * FROM physical_dev WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
	$num_rows = mysqli_num_rows($check);
	if($num_rows==0){
		//insert into attendance
		$sql = mysqli_query($conn,"INSERT INTO physical_dev VALUES(default,'$class_teacher_name','$studentId','$session','$term','$form_start_height','$form_end_height','$form_start_weight','$form_end_weight','$form_days_of_illness','$form_nature_of_illness','$date','$time')");
		if($sql){
			echo"<div class='text-success bg-success'>Successfully Inserted</div>";
		}
	}if($num_rows==1){
		//update attendance
		$sql = mysqli_query($conn,"UPDATE physical_dev SET class_teacher_name='$class_teacher_name',start_height='$form_start_height',end_height='$form_end_height',start_weight='$form_start_weight',end_weight='$form_end_weight',illness_days='$form_days_of_illness',nature_of_illness='$form_nature_of_illness',date='$date',time='$time' WHERE student_id='$studentId' AND term='$term' AND session='$session'");
		if($sql){
		echo"<div class='text-success bg-success'>Successfully Updated</div>";
		}
	}
//security measure
}//end security measure
?>