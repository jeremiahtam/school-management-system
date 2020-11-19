<?php   
include("../classes/develop-php-library.php");
include("../inc/session.inc.php");
include("../inc/db.inc.php");

//check to see which action to carry out: either edit, add, delete or load content
switch(true){
	//only load this if its NOT EDIT but ADDITION of subject result
    case !isset($_POST['edit_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['subject'])&&isset($_POST['ca_1'])&&isset($_POST['exam']):
    //make sure ther session, reg number and term is not empty
    if(!empty($_POST['session'])&&!empty($_POST['reg_no'])&&!empty($_POST['term'])){


        $session = htmlentities($_POST['session']);
        $reg_no = htmlentities($_POST['reg_no']);
        $term = htmlentities($_POST['term']);
        
        $form_subject = htmlentities($_POST['subject']);
        $form_ca_1 = htmlentities($_POST['ca_1']);
        $form_exam = htmlentities($_POST['exam']);
        $date= date('Y-m-d');
        $time= date('H:i:s');

        //get student details
        $studentDetails = new studentDetails();
        list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

        //check if data already exists
        $check = mysqli_query($conn,"SELECT * FROM subject_result WHERE subject_id='$form_subject' AND student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
        $num_rows=mysqli_num_rows($check);
        if($num_rows==1){
            echo"error";
            
            }elseif($form_subject==''){
                
            }else{
                //if ca_1 field is empty, equate it to zero
                if($form_ca_1==''){
                    $form_ca_1=0;
                    $empty_ca1=true;
                }
                //if exam field is empty, equate it to zero
                if($form_exam==''){
                    $form_exam=0;
                    $empty_exam=true;
                }
                //get total score
                $total = $form_ca_1 + $form_exam;
        //get grade from total score
        $grades_query = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
        while($grades_row = mysqli_fetch_assoc($grades_query)){	  
            $minimum = htmlentities($grades_row['minimum']);
            $maximum = htmlentities($grades_row['maximum']);
            //find a grade that fits and get the grade letter and remark 
            if($total>=$minimum && $total<=$maximum){
                $grade_letter = htmlentities($grades_row['grade_letter']);
                $remark = htmlentities($grades_row['remark']);
                break;
                }
            }
            //if the grade is not available, produce an error
            if(!isset($grade_letter)||!isset($remark)){
                echo 'gna';
                }else{
            //get class teacher full name
            $teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
            while($teacher_row = mysqli_fetch_assoc($teacher_sql)){
                $teacher_id = htmlentities($teacher_row['id']);
                $teacher_name = htmlentities($teacher_row['fullname']);    
            }
            //initialise subject teacher name
            $subject_teacher_name = '';

            //get the class this class teacher takes so we can use in determining the subject teacher for that class 
            $class_teacher_sql = mysqli_query($conn,"SELECT * FROM class_teachers WHERE teacher_id='$teacher_id' AND removed='no'");
            while($class_teacher_row = mysqli_fetch_assoc($class_teacher_sql)){
                $class_id = htmlentities($class_teacher_row['class_id']);
            }
            
            //get subject teacher name using the class id and subject id
            $subject_teacher_sql = mysqli_query($conn,"SELECT * FROM subject_teachers WHERE subject_id='$form_subject' AND class_id='$class_id' AND removed='no' ");
            while($subject_teacher_row = mysqli_fetch_assoc($subject_teacher_sql)){
              $subject_teacher_id = htmlentities($subject_teacher_row['teacher_id']);

              $subject_teacher_name_query = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$subject_teacher_id' AND removed='no'");
              while($subject_teacher_name_row = mysqli_fetch_assoc($subject_teacher_name_query)){
                $subject_teacher_name = htmlentities($subject_teacher_name_row['fullname']);
              }
  
            }
            
                //before updating
                if(isset($empty_ca1)){
                    $form_ca_1 ="";
                }
                if(isset($empty_exam)){
                    $form_exam="";
                }
                if(isset($empty_exam) && isset($empty_ca1)){
                    $total="";
                }
            
                //insert into school result
                $insert_sql = mysqli_query($conn,"INSERT INTO subject_result VALUES (default,'$studentId','$session','$term','$teacher_name','$subject_teacher_name','$form_subject','$form_ca_1','$form_exam','$total','$grade_letter','$remark','no','$date','$time')");
                //update the student percentage average
                $percent_ave = new percentTotal();
                $percent_ave->result_info($conn,$session,$term,$studentId,$loggedin_user);
                //display result  
                display_subject_result($studentId,$session,$term,$conn);
            }//end else if for grades availability

        }//end else check 

    }//end if not empty


	break;

	//only load this if its EDIT subject result
	case isset($_POST['edit_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term'])&&isset($_POST['subject'])&&isset($_POST['ca_1'])&&isset($_POST['exam']):
    //make sure ther session, reg number and term is not empty
    if(!empty($_POST['session'])&&!empty($_POST['reg_no'])&&!empty($_POST['term'])){

        $edit_id = htmlentities($_POST['edit_id']);

        $session = htmlentities($_POST['session']);
        $reg_no = htmlentities($_POST['reg_no']);
        $term = htmlentities($_POST['term']);
        
        $form_subject = htmlentities($_POST['subject']);
        $form_ca_1 = htmlentities($_POST['ca_1']);
        $form_exam = htmlentities($_POST['exam']);
        $date= date('Y-m-d');
        $time= date('H:i:s');

        //get student details
        $studentDetails = new studentDetails();
        list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

        //check if data already exists
        $check = mysqli_query($conn,"SELECT * FROM subject_result WHERE id!='$edit_id' AND subject_id='$form_subject' AND student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
        $num_rows=mysqli_num_rows($check);
        if($num_rows==1){
            echo"error";
            
            }elseif($form_subject==''){
                
            }else{
            //if ca_1 field is empty, equate it to zero
            if($form_ca_1==''){
                $form_ca_1=0;
                $empty_ca1=true;
            }
            //if exam field is empty, equate it to zero
            if($form_exam==''){
                $form_exam=0;
                $empty_exam=true;
            }
            //get total score
            $total = $form_ca_1 + $form_exam;
        //get grade from total score
        $grades_query = mysqli_query($conn,"SELECT * FROM grades WHERE removed='no'");
        while($grades_row = mysqli_fetch_assoc($grades_query)){	  
            $minimum = htmlentities($grades_row['minimum']);
            $maximum = htmlentities($grades_row['maximum']);
            //find a grade that fits and get the grade letter and remark 
            if($total>=$minimum && $total<=$maximum){
                $grade_letter = htmlentities($grades_row['grade_letter']);
                $remark = htmlentities($grades_row['remark']);
                break;
                }
        }
        //if the grade is not available, produce an error
        if(!isset($grade_letter)||!isset($remark)){
            echo 'gna';
            }else{

            //get class teacher full name
            $teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
            while($teacher_row = mysqli_fetch_assoc($teacher_sql)){
              $teacher_id = htmlentities($teacher_row['id']);
              $teacher_name = htmlentities($teacher_row['fullname']);
            }
        
            //get the class this class teacher takes so we can use in determining the subject teacher for that class 
            $class_teacher_sql = mysqli_query($conn,"SELECT * FROM class_teachers WHERE teacher_id='$teacher_id' AND removed='no'");
            while($class_teacher_row = mysqli_fetch_assoc($class_teacher_sql)){
                $class_id = htmlentities($class_teacher_row['class_id']);
            }
            //initialise subject teacher name
            $subject_teacher_name = '';
            //get subject teacher name using the class id and subject id
            $subject_teacher_sql = mysqli_query($conn,"SELECT * FROM subject_teachers WHERE subject_id='$form_subject' AND class_id='$class_id' AND removed='no' ");
            while($subject_teacher_row = mysqli_fetch_assoc($subject_teacher_sql)){
              $subject_teacher_id = htmlentities($subject_teacher_row['teacher_id']);

              $subject_teacher_name_query = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$subject_teacher_id' AND removed='no'");
              while($subject_teacher_name_row = mysqli_fetch_assoc($subject_teacher_name_query)){
                $subject_teacher_name = htmlentities($subject_teacher_name_row['fullname']);
              }
  
            }
            
            
            //before updating
            if(isset($empty_ca1)){
                $form_ca_1 ="";
            }
            if(isset($empty_exam)){
                $form_exam="";
            }
            if(isset($empty_exam) && isset($empty_ca1)){
                $total="";
            }
            //update school result
            $update_sql = mysqli_query($conn,"UPDATE subject_result SET class_teacher_name='$teacher_name',subject_teacher_name='$subject_teacher_name',subject_id='$form_subject',ca_1='$form_ca_1',exam='$form_exam',total='$total',grade='$grade_letter',remark='$remark',date='$date',time='$time' WHERE id='$edit_id'");            
            
            $percent_ave = new percentTotal();
            $percent_ave->result_info($conn,$session,$term,$studentId,$loggedin_user);
            //display result  
            display_subject_result($studentId,$session,$term,$conn);
            }//end else if for grades availability

        }//end else check

    }//end if not empty

    break;

	//only load this if its DELETE subject result
	case isset($_POST['delete_type'])&&isset($_POST['delete_id'])&&isset($_POST['session'])&&isset($_POST['reg_no'])&&isset($_POST['term']):
    
    //make sure ther session, reg number and term is not empty
    if(!empty($_POST['session'])&&!empty($_POST['reg_no'])&&!empty($_POST['term'])){
        
	  $delete_type = $_POST['delete_type'];
	  $delete_id = $_POST['delete_id'];

	  $session = htmlentities($_POST['session']);
	  $reg_no = htmlentities($_POST['reg_no']);
	  $term = htmlentities($_POST['term']);

	  $date= date('Y-m-d');
	  $time= date('H:i:s');
      
      //get student details
      $studentDetails = new studentDetails();
      list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

      //delete school result
	  $update_sql = mysqli_query($conn,"UPDATE subject_result SET removed='yes',date='$date',time='$time' WHERE id='$delete_id'");
      //update the student percentage average
      $percent_ave = new percentTotal();
      $percent_ave->result_info($conn,$session,$term,$studentId,$loggedin_user);
      //display result  
      display_subject_result($studentId,$session,$term,$conn);
    }//end if not empty

	break;

	//load subjects if its not delete, edit or add subject result
    default :
    if(isset($_POST['reg_no'])&&isset($_POST['session'])&&isset($_POST['term'])){
      //make sure ther session, reg number and term is not empty
      if(!empty($_POST['session'])&&!empty($_POST['reg_no'])&&!empty($_POST['term'])){
        
        $reg_no = htmlentities($_POST['reg_no']);
        $session = htmlentities($_POST['session']);
        $term = htmlentities($_POST['term']);
        //get student details
        $studentDetails = new studentDetails();
        list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

        if($loggedin_user_type=='class-teacher'){
            //check if it is moc exam or regular term 
            //in order to set the modal button to either add mock exam or add subject result
            if($term > 3){
                echo"<a class='btn btn-md btn-info modal-action' id='add-mock-exam-result' data-toggle='modal' data-target='#modal'>Add Mock Exam Result</a>";
            }else{
                echo"<a class='btn btn-md btn-info modal-action' id='add-subject-result' data-toggle='modal' data-target='#modal'>Add Subject Result</a>";
            }
            //update the student percentage average
           // $percent_ave = new percentTotal();
            //$percent_ave->result_info($conn,$session,$term,$studentId,$loggedin_user);
            //display result  
            display_subject_result($studentId,$session,$term,$conn);
        }
      }//end if not empty

    }
	break;

}

?>