<?php 
//set the number of output per page
$record_per_page = 30;
//$page = '';
//$output = '';
if(isset($_POST["page"]))
{
	$page = $_POST['page'];
	}
	else
	{
		$page = 1;
		}
	$start_from = ($page - 1) * $record_per_page;
//check to see which action to carry out: either edit, add, delete or load content
switch(true){
	//only load this if its NOT EDIT but ADDITION of new class teacher
	case !isset($_POST['edit_id'])&&isset($_POST['teacher_id'])&&isset($_POST['class_id']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	$form_teacher_id = htmlentities($_POST['teacher_id']);
	$form_class_id = htmlentities($_POST['class_id']);

	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if any empty fields
	if($form_teacher_id==''||$form_class_id==''){

	}else{
		
	//check if the class already has a class teacher
	$check_query = "SELECT * FROM class_teachers WHERE class_id='$form_class_id' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	
	if($check_num_rows==1){
		echo"class teacher exists";
	
		}else{
		//check if the teacher is already a class teacher
		$check_query2 = "SELECT * FROM class_teachers WHERE teacher_id='$form_teacher_id' AND removed='no'";	
		$check_result2 = mysqli_query($conn,$check_query2);
		$check_num_rows2=mysqli_num_rows($check_result2);
	
		if($check_num_rows2==1){
			echo"tt";
		
			}else{
			
			$insert_sql = mysqli_query($conn,"INSERT INTO class_teachers VALUES (default,'$form_teacher_id','$form_class_id','no','$date','$time')");
	        display_class_teachers($page,$start_from,$record_per_page,$conn);
			
		
			}//end if teacher is already a class teacher for another class
		}//end if teacher already exixts for the subject
	}//end check if form is empty
//security measure


	break;

	//only load this if its EDIT class teachers
	case isset($_POST['edit_id'])&&isset($_POST['teacher_id'])&&isset($_POST['class_id']):
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	$edit_id = htmlentities($_POST['edit_id']);

	$form_teacher_id = htmlentities($_POST['teacher_id']);
	$form_class_id = htmlentities($_POST['class_id']);

	$date= date('Y-m-d');
    $time= date('H:i:s');

	//check if any empty fields
	if($form_teacher_id==''||$form_class_id==''){

	}else{
		
	//check if the class already has a class teacher
	$check_query = "SELECT * FROM class_teachers WHERE class_id='$form_class_id' AND id!='$edit_id' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	
	if($check_num_rows==1){
		echo"class teacher exists";		 
		}else{
		//check if the teacher is already a class teacher
		$check_query2 = "SELECT * FROM class_teachers WHERE teacher_id='$form_teacher_id' AND removed='no'";	
		$check_result2 = mysqli_query($conn,$check_query2);
		$check_num_rows2=mysqli_num_rows($check_result2);

		if($check_num_rows2==1){
			echo"tt";

			}else{
			
		$update_sql = mysqli_query($conn,"UPDATE class_teachers SET teacher_id='$form_teacher_id',class_id='$form_class_id',date='$date',time='$time' WHERE id='$edit_id'");

			display_all_teachers($page,$start_from,$record_per_page,$conn);
			
		
		}//end if teacher is already a class teacher for another class
	
	}//end if teacher already exixts for the subject
	
	}//end check if form is empty
//security measure


    break;

	//only load this if its DELETE class teachers
	case isset($_POST['delete_type'])&&isset($_POST['delete_id']):
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');
	$delete_sql = mysqli_query($conn,"UPDATE class_teachers SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
    display_class_teachers($page,$start_from,$record_per_page,$conn);

	break;

	//load class teachers if its not delete, edit or add class teachers
	default:

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");	 		
    if($loggedin_user_type=='admin'){
		display_class_teachers($page,$start_from,$record_per_page,$conn);
	}
	break;

}

?>