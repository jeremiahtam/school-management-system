<?php   
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
    include("../inc/db.inc.php");
    
switch(true){
    //load school fees if its not delete, edit or add subjects
    case isset($_POST['search_reg_number']):
    //set what type of display to make
    //this will display the ADD button at the initial search result
    $type = 'search';
    //check if the user exists
    $reg_no = $_POST['search_reg_number'];
    
    //
    $user_sql = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$reg_no' AND removed='no'");
    $suer_rows = mysqli_num_rows($user_sql);
    //check if the reg number exist
    if($suer_rows==1){
      //display all school fees history
      display_school_fees($reg_no,$conn,$type);
    }else{
        echo"<div class='text-danger'>That registration number does not exist!</div>";
        }        
    break;

    
    //only load this if its ADDITION of school fees and NOT EDIT 
    case !isset($_POST['edit_id'])&&isset($_POST['reg_no'])&&isset($_POST['session'])&&isset($_POST['term'])&&isset($_POST['status']):
    $type = 'add-fees';

    $session = htmlentities($_POST['session']);
    $reg_no = htmlentities($_POST['reg_no']);
    $term = htmlentities($_POST['term']);
    $status = htmlentities($_POST['status']);
    
    $date= date('Y-m-d');
    $time= date('H:i:s');

    //get student id of the desired student via reg no and insert into student_reg_no
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

    //check if the session, term and reg_no already has a school fees entry
    $check = mysqli_query($conn,"SELECT * FROM school_fees WHERE student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
    $num_rows=mysqli_num_rows($check);
    if($num_rows>0){
        echo"error";
        
        }else if($reg_no=='' || $status=='' || $session=='' || $term==''){
            
        }else{
            
        $insert_sql = mysqli_query($conn,"INSERT INTO school_fees VALUES (default,'$studentId','$session','$term','$status','no','$date','$time')");
      //display all school fees history
      if($insert_sql){
            display_school_fees($reg_no,$conn,$type);
        }
    }//end else check 
    break;


    //only load this if its EDIT school_fees
    case isset($_POST['edit_id'])&&isset($_POST['reg_no'])&&isset($_POST['session'])&&isset($_POST['term']):
    $type = 'edit-fees';

    $edit_id = htmlentities($_POST['edit_id']);

    $session = htmlentities($_POST['session']);
    $reg_no = htmlentities($_POST['reg_no']);
    $term = htmlentities($_POST['term']);
    $status = htmlentities($_POST['status']);

    $date= date('Y-m-d');
    $time= date('H:i:s');

    //get student id of the desired student via reg no 
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

    //check if the session, term and reg_no already has a school fees entry
    $check = mysqli_query($conn,"SELECT * FROM school_fees WHERE  id!='$edit_id' AND student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
    $num_rows=mysqli_num_rows($check);
    if($num_rows>0){
        echo"<a class='text-danger bg-danger'>Error! Entery already exists!</a>";
        
        }else if($reg_no=='' || $status=='' || $session=='' || $term==''){
            
        }else{
            
        $update_sql = mysqli_query($conn,"UPDATE school_fees SET session='$session',term='$term',status='$status',date='$date',time='$time' WHERE id='$edit_id'");
      //display all school fees history
      if($update_sql){
          display_school_fees($reg_no,$conn,$type);
        }
      }//end else check


    break;

    //only load this if its DELETE school_fees
    case isset($_POST['delete_type'])&&isset($_POST['delete_id']):
    $type = 'delete-fees';

    $delete_type = $_POST['delete_type'];
    $delete_id = $_POST['delete_id'];
    $reg_no = $_POST['reg_no'];

    $date= date('Y-m-d');
    $time= date('H:i:s');
    //delete school fees entry from database
    $update_sql = mysqli_query($conn,"UPDATE school_fees SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
    //display all school fees history
      if($update_sql){
        display_school_fees($reg_no,$conn,$type);
      }

    break;

    default:
    break;
}//end switch statement
?>