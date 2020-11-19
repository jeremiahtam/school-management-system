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

	//only load this if its EDIT search students
	case isset($_POST['edit_id'])&&isset($_POST['gender'])&&isset($_POST['date_of_birth'])&&isset($_POST['reg_no'])&&isset($_POST['password'])&&isset($_POST['search_students_keyword']):

	//$page = htmlentities($_POST['page']);
	$edit_id = htmlentities($_POST['edit_id']);

	$form_search_students_keyword = htmlentities($_POST['search_students_keyword']);

	$form_fullname = htmlentities($_POST['fullname']);
	$form_gender = htmlentities($_POST['gender']);
	$form_date_of_birth = htmlentities($_POST['date_of_birth']);
	$form_reg_no = htmlentities($_POST['reg_no']);
	$form_password = htmlentities($_POST['password']);
	$form_status= htmlentities($_POST['status']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if any empty fields
	if($form_fullname==''||$form_gender==''||$form_date_of_birth==''||$form_reg_no==''||$form_password==''){		

	}else{
		//check if student's reg number already exists
		$check_reg_no_query = "SELECT * FROM students WHERE reg_no='$form_reg_no' AND id!='$edit_id' AND removed='no'";	
		$check_reg_no_result = mysqli_query($conn,$check_reg_no_query);
		$check_reg_no_num_rows=mysqli_num_rows($check_reg_no_result);
		
		if($check_reg_no_num_rows==1){
			echo"reg num exists";

		}else{

        $insert_sql = mysqli_query($conn,"UPDATE students SET fullname='$form_fullname', gender='$form_gender',date_of_birth='$form_date_of_birth',reg_no='$form_reg_no',password='$form_password',status='$form_status',date='$date',time='$time' WHERE id='$edit_id' ");
        display_search_students($page,$start_from,$record_per_page,$form_search_students_keyword,$conn);
      } //end check if teacher's username already exists
    }//end check if form is empty

    break;


    //only load this if its DELETE search students
	case isset($_POST['delete_type'])&&isset($_POST['delete_id'])&&isset($_POST['search_students_keyword']):
    
	$form_search_students_keyword = htmlentities($_POST['search_students_keyword']);

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$date= date('Y-m-d');
	$time= date('H:i:s');

	$delete_sql = mysqli_query($conn,"UPDATE students SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
    display_search_students($page,$start_from,$record_per_page,$form_search_students_keyword,$conn);

	break;

	//load this if its not delete, edit or add search students
	default:
    if(isset($_POST['search_students_keyword'])){
        $form_search_students_keyword = htmlentities($_POST['search_students_keyword']);
        if($loggedin_user_type=='admin'){
            display_search_students($page,$start_from,$record_per_page,$form_search_students_keyword,$conn);
        }
    }
	break;

}

?>