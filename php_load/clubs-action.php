<?php   
//check to see which action to carry out: either edit, add, delete or load content
switch(true){
	//only load this if its NOT EDIT but ADDITION of clubs
	case !isset($_POST['edit_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['organization'])&&isset($_POST['office_held'])&&isset($_POST['significant_contribution']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

    $session = htmlentities($_POST['session']);
    $reg_no = htmlentities($_POST['reg_no']);
    $term = htmlentities($_POST['term']);
    
    $form_organization = htmlentities($_POST['organization']);
    $form_office_held = htmlentities($_POST['office_held']);
    $form_significant_contribution = htmlentities($_POST['significant_contribution']);
    $date= date('Y-m-d');
    $time= date('H:i:s');
    //get class teacher full name
    $class_teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
    $class_teacher_row = mysqli_fetch_assoc($class_teacher_sql);
    $class_teacher_name = htmlentities($class_teacher_row['fullname']);
    
    //get student details
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

    //check if data already exists
    $check = mysqli_query($conn,"SELECT * FROM clubs WHERE student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
    $num_rows=mysqli_num_rows($check);
    if($num_rows==2){
        echo"error";
        
        }elseif($form_organization==''||$form_office_held==''){
            
        }else{
            
          $insert_sql = mysqli_query($conn,"INSERT INTO clubs VALUES (default,'$studentId','$session','$term','$class_teacher_name','$form_organization','$form_office_held','$form_significant_contribution','no','$date','$time')");
          display_clubs($reg_no,$session,$term,$conn);
		 
    }//end else check 


	break;

	//only load this if its EDIT clubs
	case isset($_POST['edit_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['organization'])&&isset($_POST['office_held'])&&isset($_POST['significant_contribution']):
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

    $edit_id = htmlentities($_POST['edit_id']);
    $session = htmlentities($_POST['session']);
    $reg_no = htmlentities($_POST['reg_no']);
    $term = htmlentities($_POST['term']);
    
    $form_organization = htmlentities($_POST['organization']);
    $form_office_held = htmlentities($_POST['office_held']);
    $form_significant_contribution = htmlentities($_POST['significant_contribution']);
    $date= date('Y-m-d');
    $time= date('H:i:s');
    //get class teacher full name
    $class_teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
    $class_teacher_row = mysqli_fetch_assoc($class_teacher_sql);
    $class_teacher_name = htmlentities($class_teacher_row['fullname']);
    
    //get student details
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);
    
    //check if data already exists
    $check = mysqli_query($conn,"SELECT * FROM clubs WHERE student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
    $num_rows=mysqli_num_rows($check);
    
    if($form_organization==''||$form_office_held==''){
            
        }else{
            
          $update_sql = mysqli_query($conn,"UPDATE clubs SET class_teacher_name='$class_teacher_name',organization='$form_organization',office_held='$form_office_held',significant_contribution='$form_significant_contribution',date='$date',time='$time' WHERE id='$edit_id'");
        display_clubs($reg_no,$session,$term,$conn);
    }//end else check


    break;

	//only load this if its DELETE clubs
	case isset($_POST['delete_type'])&&isset($_POST['delete_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term']):
    
    include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$delete_type = $_POST['delete_type'];
	$delete_id = $_POST['delete_id'];

	$session = htmlentities($_POST['session']);
	$reg_no = htmlentities($_POST['reg_no']);
	$term = htmlentities($_POST['term']);

	$date= date('Y-m-d');
	$time= date('H:i:s');
	//delete club from database
	$update_sql = mysqli_query($conn,"UPDATE clubs SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
    display_clubs($reg_no,$session,$term,$conn);

	break;

	//load subjects if its not delete, edit or add subjects
    default :
    if(isset($_POST['reg_no'])&&isset($_POST['session'])&&isset($_POST['term'])){

        include("../inc/session.inc.php");
        include("../inc/db.inc.php");
        include("../classes/develop-php-library.php");

        $reg_no = htmlentities($_POST['reg_no']);
        $session = htmlentities($_POST['session']);
        $term = htmlentities($_POST['term']);

        if($loggedin_user_type=='class-teacher'){
            display_clubs($reg_no,$session,$term,$conn);
        }
    }
	break;

}

?>