<?php 
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['reg_no'])&&isset($_POST['session'])&&isset($_POST['term'])){

  include("../inc/session.inc.php");
  include("../inc/db.inc.php");
  include("../classes/develop-php-library.php");

  $reg_no = htmlentities($_POST['reg_no']);
  $session = htmlentities($_POST['session']);
  $term = htmlentities($_POST['term']);
  $year = date('Y');
  $date= date('Y-m-d');
  $time= date('H:i:s');

  //if $reg_no, $session or $term is empty
  if($reg_no==''||$session==''||$term==''){

    }else{
    //get student details
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

    //get class arm and name
    $class_arm_and_class = new classTeacherDetails();
    list($class_name,$class_arm) = $class_arm_and_class->get_arm($conn,$loggedin_user);

    //else if neither $reg_no, $session or $term is empty
    //check if this data already exists
    $check = mysqli_query($conn,"SELECT * FROM available_result WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
    $num_rows = mysqli_num_rows($check);
    //if it does not exist, show insert into availble_result
  if($num_rows==0){
      //try and get resumptio date
      //check if this data already exists
      $resumption_sql = mysqli_query($conn,"SELECT * FROM resumption");
      $resumption_sql_row = mysqli_fetch_assoc($resumption_sql);
      $resumption_date = htmlentities($resumption_sql_row['resumption_date']);

      //insert into available result
      $insert_sql = mysqli_query($conn,"INSERT INTO available_result VALUES (default,'$studentId','$session','$term','$class_name','$class_arm','$year','$date','$time') ");
      if($insert_sql){
          echo"<a class='text-success pull-right'>This Result Has Been Submitted</a>";
      }

      //check if this session and term result is availabble for locking in database
      $check_lock_result = mysqli_query($conn,"SELECT * FROM lock_result WHERE session='$session' AND term='$term' ");
      $lock_result_rows = mysqli_num_rows($check_lock_result);
      //if it is not available, insert into lock_result
      if($lock_result_rows==0){
        //insert into lock_result
        $insert_sql = mysqli_query($conn,"INSERT INTO lock_result VALUES (default,'$session','$term','no','$date','$time') ");
      }//end if lock result

    }
  }
//security measure
}//end security measure

?>