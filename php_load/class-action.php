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
	//only load this if its NOT EDIT but ADDITION of new class
	case !isset($_POST['edit_id'])&&isset($_POST['class_name'])&&isset($_POST['arm_name']):

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$form_class_name = htmlentities($_POST['class_name']);
	$form_arm_name = htmlentities($_POST['arm_name']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM classes WHERE class='$form_class_name' AND arm='$form_arm_name' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_arm_name==''||$form_class_name==''){
			
		}else{
			
			$insert_sql = mysqli_query($conn,"INSERT INTO classes VALUES (default,'$form_class_name','$form_arm_name','$loggedin_user','no','$date','$time')");
			
			display_classes($page,$start_from,$record_per_page,$conn);
		}//end else check 

	break;

	//only load this if its EDIT class
	case isset($_POST['edit_id'])&&isset($_POST['class_name'])&&isset($_POST['arm_name']):
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$edit_id = htmlentities($_POST['edit_id']);
	$form_class_name = htmlentities($_POST['class_name']);
	$form_arm_name = htmlentities($_POST['arm_name']);
	$date = date('Y-m-d');
	$time = date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM classes WHERE class='$form_class_name' AND arm='$form_arm_name' AND id!='$edit_id' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_arm_name==''||$form_class_name==''){
			
		}else{

			$insert_sql = mysqli_query($conn,"UPDATE classes SET class='$form_class_name', arm='$form_arm_name', admin_username='$loggedin_user',date='$date',time='$time' WHERE id='$edit_id' ");			
			display_classes($page,$start_from,$record_per_page,$conn);
			}//end else check 

	break;

	//only load this if its DELETE class
	case isset($_POST['delete_type'])&&isset($_POST['delete_id']):
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');

	$delete_sql = mysqli_query($conn,"UPDATE classes SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");

	//check if the class has a registered class teacher
	$check_class_teacher = mysqli_query($conn, "SELECT * FROM class_teachers WHERE class_id='$delete_id' AND removed='no'");
	$rows_class_teacher = mysqli_num_rows($check_class_teacher);
	if ($rows_class_teacher>0) {
		//delete if deleted class has a class teacher
		$delete_class_teacher_sql = mysqli_query($conn,"UPDATE class_teachers SET removed='yes',date='$date',time='$time' WHERE class_id='$delete_id'");
		}
		
		display_classes($page,$start_from,$record_per_page,$conn);

	break;

	//load class if its not delete, edit or add class
	default:

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");	 		
    if($loggedin_user_type=='admin'){
		display_classes($page,$start_from,$record_per_page,$conn);
	}
	break;

}

?>