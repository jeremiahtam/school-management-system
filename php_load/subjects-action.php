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
	//only load this if its NOT EDIT but ADDITION of subjects
	case !isset($_POST['edit_id'])&&isset($_POST['subject_name'])&&isset($_POST['offered_by'])&&isset($_POST['session_intro']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	$form_subject_name = htmlentities($_POST['subject_name']);
	$form_offered_by = htmlentities($_POST['offered_by']);
	$form_session_intro = htmlentities($_POST['session_intro']);
	$form_session_removed = 'active';
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM subjects WHERE subject_name='$form_subject_name' AND offered_by='$form_offered_by' AND removed='no'";	
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_subject_name==''||$form_offered_by==''||$form_session_intro==''||$form_session_removed==''){
			
		}else{
			
		$insert_sql = mysqli_query($conn,"INSERT INTO subjects VALUES (default,'$form_subject_name','$form_offered_by','$form_session_intro','$form_session_removed','$loggedin_user','no','$date','$time')");
		display_subjects($page,$start_from,$record_per_page,$conn);
		 
    }//end else check 


	break;

	//only load this if its EDIT subjects
	case isset($_POST['edit_id'])&&isset($_POST['subject_name'])&&isset($_POST['offered_by'])&&isset($_POST['session_intro'])&&isset($_POST['session_removed']):
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");


	$edit_id = htmlentities($_POST['edit_id']);
	$form_subject_name = htmlentities($_POST['subject_name']);
	$form_offered_by = htmlentities($_POST['offered_by']);
	$form_session_intro = htmlentities($_POST['session_intro']);
	$form_session_removed = htmlentities($_POST['session_removed']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if data already exists
	$check_query = "SELECT * FROM subjects WHERE subject_name='$form_subject_name' AND offered_by='$form_offered_by' AND id!='$edit_id' AND removed='no'";
	$check_result = mysqli_query($conn,$check_query);
	$check_num_rows=mysqli_num_rows($check_result);
	if($check_num_rows==1){
		echo"error";
		
		}else
		if($form_subject_name==''||$form_offered_by==''||$form_session_intro==''||$form_session_removed==''){
			
		}else{
		//check if data the subject to be edited is already in use
		//if results have been entered using the subject id
		$subject_edit_query = mysqli_query($conn,"SELECT * FROM subject_result WHERE subject_id='$edit_id'");
		$subject_edit_rows = mysqli_num_rows($subject_edit_query);
		//if it does, do not change the name
		if($subject_edit_rows > 0){
			echo $subject_edit_rows;
			$insert_sql = mysqli_query($conn,"UPDATE subjects SET offered_by='$form_offered_by',session_intro='$form_session_intro',session_removed='$form_session_removed', admin_username='$loggedin_user',date='$date',time='$time' WHERE id='$edit_id' ");
		}else if($subject_edit_rows==0){
			//else the name can be changed
			$insert_sql = mysqli_query($conn,"UPDATE subjects SET subject_name='$form_subject_name', offered_by='$form_offered_by',session_intro='$form_session_intro',session_removed='$form_session_removed', admin_username='$loggedin_user',date='$date',time='$time' WHERE id='$edit_id' ");
		}
        display_subjects($page,$start_from,$record_per_page,$conn);
    }//end else check


    break;

	//only load this if its DELETE subjects
	case isset($_POST['delete_type'])&&isset($_POST['delete_id']):
    
    include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');

    $delete_sql = mysqli_query($conn,"UPDATE subjects SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");

    //check if the subect has a subject teacher
    $check_subject_teacher = mysqli_query($conn, "SELECT * FROM subject_teachers WHERE subject_id='$delete_id' AND removed='no'");
    $rows_subject_teacher = mysqli_num_rows($check_subject_teacher);
    if ($rows_subject_teacher>0) {
        //delete if deleted class has a subject teacher
        $delete_subject_teacher_sql = mysqli_query($conn,"UPDATE subject_teachers SET removed='yes',date='$date',time='$time' WHERE subject_id='$delete_id'");
        }
    display_subjects($page,$start_from,$record_per_page,$conn);

	break;

	//load subjects if its not delete, edit or add subjects
	default:

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
    include("../classes/develop-php-library.php");
    if($loggedin_user_type=='admin'){
        display_subjects($page,$start_from,$record_per_page,$conn);
    }
	break;

}

?>