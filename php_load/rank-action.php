<?php
//check if the $_post['session'] and $_post['term'] exists
switch(true){
    case isset($_POST['term'])&&isset($_POST['session']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
  include("../inc/db.inc.php");
    
  //get the class the logged in class teacher takes
  $class = new classTeacherDetails();
  list($class_name,$class_arm) = $class->get_arm($conn,$loggedin_user);    
    
	$form_session = htmlentities($_POST['session']);
	$form_term = htmlentities($_POST['term']);

	$date= date('Y-m-d');
	$time= date('H:i:s');

		
	//list all the students by rank in that particular class and arm
    $check_query = mysqli_query($conn,"SELECT * FROM percent_total WHERE session='$form_session' AND term='$form_term' AND class_name='$class_name' AND class_arm='$class_arm' ORDER BY total DESC");

    $num_rows = mysqli_num_rows($check_query);
    //initialise conditional count
    $cond_count = 0;
    //initialise unconditional count
    $uncond_count = 0;
    //initialise scores array that will contain all scores
    $scores = array();

    echo"
    <table class='table table-hover'>
      <tbody>
        <tr>
        <th class=''>No.</th>
          <th class=''>Position</th>
          <th class=''>Student Name</th>
          <th class=''>Reg. No.</th>
          <th class=''>Total</th>
        </tr>";

    while($row = mysqli_fetch_assoc($check_query)){
        $session = $row['session'];
        $term = $row['term'];
        $student_id = $row['student_id'];
        $class_teacher_username = $row['class_teacher_username'];
        $total = $row['total'];


        //get student details
        $studentDetails = new studentDetails();
        $reg_no = $studentDetails->get_student_reg_no($conn,$student_id);
        list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

        //check if the $total for a student is already in the $scores array
        if(!in_array($total, $scores, true)){
            //if its not, increase the conditional count by 1
           $cond_count = $cond_count + 1;
        }

        //add to the student $total score to $scores array
        $scores[$uncond_count] = $total;

        //get the class the logged in class teacher takes
       // $student = new studentDetails();
        //list($student_id,$fullname,$guardian_name,$gender,$date_of_birth,$reg_no,$password,$status,$removed) = $student->student_info($conn,$student_reg_no);
        //increase the unconditional count 
        while($uncond_count < $num_rows){
          $uncond_count = $uncond_count + 1;
          break;
        }
        echo"
        <tr>
          <td class=''>$uncond_count</td>
          <td class=''>$cond_count</td>
          <td class=''>$studentFullname</td>
          <td class=''>$reg_no</td>
          <td class=''>$total</td>
        </tr>";
    }
    echo"
      </tbody>
    </table>
    ";
    
	break;
}
?>