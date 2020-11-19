<?php 
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['principal_comment'])){

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$session = htmlentities($_POST['session']);
	$reg_no = htmlentities($_POST['reg_no']);
	$term = htmlentities($_POST['term']);

	$form_principal_comment = htmlentities($_POST['principal_comment']);
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
	$check = mysqli_query($conn,"SELECT * FROM principal_comment WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
	$num_rows = mysqli_num_rows($check);
	if($num_rows==0){
		//insert into attendance
		$sql = mysqli_query($conn,"INSERT INTO principal_comment VALUES(default,'$studentId','$session','$term','$class_teacher_name','$form_principal_comment','$date','$time')");
		if($sql){
			echo"<div class='text-success bg-success'>Successfully Inserted</div>";
		}
	}if($num_rows==1){
		//update attendance
		$sql = mysqli_query($conn,"UPDATE principal_comment SET class_teacher_name='$class_teacher_name',comment='$form_principal_comment',date='$date',time='$time' WHERE student_id='$studentId' AND term='$term' AND session='$session'");
		if($sql){
		echo"<div class='text-success bg-success'>Successfully Updated</div>";
		}
	}
//security measure
}//end security measure
?>