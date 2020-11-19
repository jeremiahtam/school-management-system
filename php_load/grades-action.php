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
	//only load this if its NOT EDIT but ADDITION of grades
	case !isset($_POST['edit_id'])&&isset($_POST['minimum'])&&isset($_POST['maximum'])&&isset($_POST['grade_letter'])&&isset($_POST['remark'])&&isset($_POST['class_group']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	$form_minimum = htmlentities($_POST['minimum']);
	$form_maximum = htmlentities($_POST['maximum']);
	$form_grade_letter = htmlentities($_POST['grade_letter']);
	$form_remark = htmlentities($_POST['remark']);
	$form_class_group = htmlentities($_POST['class_group']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM grades WHERE (minimum='$form_minimum' AND maximum='$form_maximum' AND grade_letter='$form_grade_letter') AND class_group='$form_class_group' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_minimum==''||$form_maximum=='' || $form_grade_letter=='' || $form_remark=='' || $form_class_group==''){
			
		}else{
		$insert_sql = mysqli_query($conn,"INSERT INTO grades VALUES (default,'$form_minimum','$form_maximum','$form_grade_letter','$form_remark','$form_class_group','no','$date','$time')");
		display_grades($page,$start_from,$record_per_page,$conn);
		 
    }//end else check 


	break;

	//only load this if its EDIT grades
	case isset($_POST['edit_id'])&&isset($_POST['minimum'])&&isset($_POST['maximum'])&&isset($_POST['grade_letter'])&&isset($_POST['remark'])&&isset($_POST['class_group']):
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");


	//$page = htmlentities($_POST['page']);
	$edit_id = htmlentities($_POST['edit_id']);
	$form_minimum = htmlentities($_POST['minimum']);
	$form_maximum = htmlentities($_POST['maximum']);
	$form_grade_letter = htmlentities($_POST['grade_letter']);
	$form_remark = htmlentities($_POST['remark']);
	$form_class_group = htmlentities($_POST['class_group']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM grades WHERE (minimum='$form_minimum' AND maximum='$form_maximum' AND grade_letter='$form_grade_letter') AND id!='$edit_id' AND class_group='$form_class_group' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_minimum==''||$form_maximum=='' || $form_grade_letter=='' || $form_remark=='' || $form_class_group==''){
			
		}else{

      $insert_sql = mysqli_query($conn,"UPDATE grades SET maximum='$form_maximum', minimum='$form_minimum', grade_letter='$form_grade_letter',remark='$form_remark',class_group='$form_class_group',date='$date',time='$time' WHERE id='$edit_id' ");
	  display_grades($page,$start_from,$record_per_page,$conn);
    }//end else check


    break;

	//only load this if its DELETE grades
	case isset($_POST['delete_type'])&&isset($_POST['delete_id']):
    
    include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');

	$delete_sql = mysqli_query($conn,"UPDATE grades SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
    display_grades($page,$start_from,$record_per_page,$conn);

	break;

	//load grades if its not delete, edit or add grades
	default:

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
    include("../classes/develop-php-library.php");
    if($loggedin_user_type=='admin'){
        display_grades($page,$start_from,$record_per_page,$conn);
    }
	break;

}

?>