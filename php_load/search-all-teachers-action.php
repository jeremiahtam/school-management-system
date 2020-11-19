<?php 
include("../classes/develop-php-library.php");
include("../inc/session.inc.php");
include("../inc/db.inc.php");

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

	//only load this if its EDIT search all-teachers
	case isset($_POST['edit_id'])&&isset($_POST['fullname'])&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['email'])&&$_POST['search_all_teachers_keyword']:

	//$page = htmlentities($_POST['page']);
	$edit_id = htmlentities($_POST['edit_id']);

	$form_search_all_teachers_keyword = htmlentities($_POST['search_all_teachers_keyword']);

	//$page = htmlentities($_POST['page']);
	$edit_id = htmlentities($_POST['edit_id']);

	$form_fullname = htmlentities($_POST['fullname']);
	$form_username = htmlentities($_POST['username']);
	$form_password = htmlentities($_POST['password']);
	$form_email = htmlentities($_POST['email']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if any empty fields
	if($form_fullname==''||$form_username==''||$form_password==''||$form_email==''){		

	}else{
	//check if teacher's username already exists
	$check_username_query = "SELECT * FROM all_teachers WHERE username='$form_username' AND id!='$edit_id' AND removed='no'";	
	$check_username_result = mysqli_query($conn,$check_username_query);
	$check_username_num_rows=mysqli_num_rows($check_username_result);
	
	if($check_username_num_rows==1){
	  echo"username exists";
	}else{
		//check if teacher's email already exists
		$check_email_query = "SELECT * FROM all_teachers WHERE email='$form_email' AND removed='no'";	
		$check_email_result = mysqli_query($conn,$check_email_query);
		$check_email_num_rows=mysqli_num_rows($check_email_result);
		
		if($check_username_num_rows>0){
				echo"ee";
		}else{
		$insert_sql = mysqli_query($conn,"UPDATE all_teachers SET fullname='$form_fullname', username='$form_username', password='$form_password',email='$form_email',date='$date',time='$time' WHERE id='$edit_id' ");		  
		display_search_all_teachers($page,$start_from,$record_per_page,$form_search_all_teachers_keyword,$conn);
		} //end check email
      } //end check if teacher's username already exists
	}//end check if form is empty

    break;


    //only load this if its DELETE search students
	case isset($_POST['delete_type'])&&isset($_POST['delete_id'])&&isset($_POST['search_all_teachers_keyword']):
    
	$form_search_all_teachers_keyword = htmlentities($_POST['search_all_teachers_keyword']);
	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');

	$delete_sql = mysqli_query($conn,"UPDATE all_teachers SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");

	//check if teacher is registered as a subject teacher
	$check_subject_teacher = mysqli_query($conn, "SELECT * FROM subject_teachers WHERE teacher_id='$delete_id' AND removed='no'");
	$rows_subject_teacher = mysqli_num_rows($check_subject_teacher);
	if ($rows_subject_teacher>0) {
		//delete if teacher is a subject teacher
		$delete_subject_teacher_sql = mysqli_query($conn,"UPDATE subject_teachers SET removed='yes',date='$date',time='$time' WHERE teacher_id='$delete_id'");
		}

	//check if teacher is registered as a class teacher
	$check_class_teacher = mysqli_query($conn, "SELECT * FROM class_teachers WHERE teacher_id='$delete_id' AND removed='no'");
	$rows_class_teacher = mysqli_num_rows($check_class_teacher);
	if ($rows_class_teacher>0) {
		//delete if teacher is a class teacher
		$delete_class_teacher_sql = mysqli_query($conn,"UPDATE class_teachers SET removed='yes',date='$date',time='$time' WHERE teacher_id='$delete_id'");
		}
	display_search_all_teachers($page,$start_from,$record_per_page,$form_search_all_teachers_keyword,$conn);

	break;

	//load this if its not delete, edit or add search students
	default:
    if(isset($_POST['search_all_teachers_keyword'])){
        $form_search_all_teachers_keyword = htmlentities($_POST['search_all_teachers_keyword']);
        if($loggedin_user_type=='admin'){
            display_search_all_teachers($page,$start_from,$record_per_page,$form_search_all_teachers_keyword,$conn);
        }
    }
	break;

}

?>