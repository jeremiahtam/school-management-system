<?php 
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['indoor_games'])&&isset($_POST['balls_games'])&&isset($_POST['combat_games'])&&isset($_POST['track'])&&isset($_POST['jumps'])&&isset($_POST['throws'])&&isset($_POST['swimming'])&&isset($_POST['comment'])&&isset($_POST['weight_lifting'])){
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$session = htmlentities($_POST['session']);
	$reg_no = htmlentities($_POST['reg_no']);
	$term = htmlentities($_POST['term']);

	$form_indoor_games = htmlentities($_POST['indoor_games']);
	$form_balls_games = htmlentities($_POST['balls_games']);
	$form_combat_games = htmlentities($_POST['combat_games']);
	$form_track = htmlentities($_POST['track']);
	$form_jumps = htmlentities($_POST['jumps']);
	$form_throws = htmlentities($_POST['throws']);
	$form_swimming = htmlentities($_POST['swimming']);
	$form_comment = htmlentities($_POST['comment']);
	$form_weight_lifting = htmlentities($_POST['weight_lifting']);
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
	$check = mysqli_query($conn,"SELECT * FROM sporting_activities WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
	$num_rows = mysqli_num_rows($check);
	if($num_rows==0){
		//insert into attendance
		$sql = mysqli_query($conn,"INSERT INTO sporting_activities VALUES(default,'$studentId','$class_teacher_name','$session','$term','$form_indoor_games','$form_balls_games','$form_combat_games','$form_track','$form_jumps','$form_throws','$form_swimming','$form_weight_lifting','$form_comment','','$date','$time')");
		if($sql){
			echo"<div class='text-success bg-success'>Successfully Inserted</div>";
		}
	}if($num_rows==1){
		//update attendance
		$sql = mysqli_query($conn,"UPDATE sporting_activities SET class_teacher_name='$class_teacher_name',indoor_games='$form_indoor_games',balls_games='$form_balls_games',combat_games='$form_combat_games',track='$form_track',jumps='$form_jumps',throws='$form_throws',swimming='$form_swimming',weight_lifting='$form_weight_lifting',comment='$form_comment',date='$date',time='$time' WHERE student_id='$studentId' AND term='$term' AND session='$session'");
		if($sql){
		echo"<div class='text-success bg-success'>Successfully Updated</div>";
		}
	}
//security measure
}//end security measure

?>