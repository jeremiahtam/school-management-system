<?php 
include("../classes/develop-php-library.php");
include("../inc/session.inc.php");
include("../inc/db.inc.php");

	//only allow this file to execute if the submit button was clicked 
if(isset($loggedin_user)&&isset($_POST['pin_no']) && isset($_POST['session']) && isset($_POST['term'])){

  
  $form_pin_no = htmlentities($_POST['pin_no']);
  $form_session = htmlentities($_POST['session']);
  $form_term = htmlentities($_POST['term']);
  $date= date('Y-m-d');
  $time= date('H:i:s');
  
	//get student details
	$studentDetails = new studentDetails();
	list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$loggedin_user);

	//check if the student's registration number exists
  $check_reg_no = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$loggedin_user' AND removed='no' ");
  $check_reg_no_num_rows = mysqli_num_rows($check_reg_no);
  if($check_reg_no_num_rows==1){

	//check if student status is active
	$check_student_status = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$loggedin_user' AND removed='no' ");
	$check_student_status_rows = mysqli_num_rows($check_student_status);

	$student_status_details = mysqli_fetch_assoc($check_student_status);
	$student_status = $student_status_details['status'];
	if($student_status=="active"){

	  //check if the card serial number exists
	  $check_pin_no = mysqli_query($conn,"SELECT * FROM cards WHERE card_no='$form_pin_no' ");
	  ///////get card serial no///////
	  $card_details = mysqli_fetch_assoc($check_pin_no);
	  $card_serial_no = $card_details['serial_number'];
	  $card_generator_pin = $card_details['generator_pin'];
	  $card_status = $card_details['used'];
	  ////////////////////////////////
	  $check_pin_no_num_rows = mysqli_num_rows($check_pin_no);
	  if($check_pin_no_num_rows==1){
		//check if the card serial number has been used by another student 
		$check_used_card = mysqli_query($conn,"SELECT * FROM used_cards WHERE card_no='$form_pin_no' AND student_id!='$studentId'");
		$check_used_card_num_rows = mysqli_num_rows($check_used_card);	
		if($check_used_card_num_rows==0){
		  
		  //check if the card has been used by this student for another term or session
		  $check_student_used_card = mysqli_query($conn,"SELECT * FROM used_cards WHERE card_no='$form_pin_no' AND student_id='$studentId' AND (session!='$form_session' OR term!='$form_term')");
		  $check_student_used_card_num_rows = mysqli_num_rows($check_student_used_card);	
		  if($check_student_used_card_num_rows==0){
  
			//check if the card has been used by this student for this term and session up to 3 times
			$check_card_validity = mysqli_query($conn,"SELECT * FROM used_cards WHERE card_no='$form_pin_no' AND student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
			$check_card_validity_num_rows = mysqli_num_rows($check_card_validity);	
			if($check_card_validity_num_rows<5){
				
				//check if the student has paid school fees!
				//first check if it is compulsory to pay for that exam result
				$term_exam_fees = mysqli_query($conn,"SELECT * FROM terms WHERE term='$form_term' AND removed='no'");
				$school_fees_details = mysqli_fetch_assoc($term_exam_fees);
				$school_fees_compulsory = $school_fees_details['school_fees_compulsory'];
				//choose a query depending on if the term's exam result has compulsory school fees or not
				if($school_fees_compulsory=='yes'){
					//if it is, make the query based on both term and session 
					$check_school_fees = mysqli_query($conn,"SELECT * FROM school_fees WHERE status='Full Payment' AND student_id='$studentId' AND session='$form_session' AND term='$form_term' AND removed='no'");
					//check if student is not bound by school fees
					$check_school_fees_num_rows = mysqli_num_rows($check_school_fees);	
				}else{
					//if it is not comulsory, allow the student if he has made at least one term payment in that session
					//$check_school_fees = mysqli_query($conn,"SELECT * FROM school_fees WHERE status='Full Payment' AND student_reg_no='$loggedin_user' AND session='$form_session' AND removed='no' LIMIT 1");
					//check if student is not bound by school fees
					$check_school_fees_num_rows = 1;	
				}

			  if($check_school_fees_num_rows>0){
				
			    //check if this student's result is ready
			    $check_result_ready = mysqli_query($conn,"SELECT * FROM available_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
			    $check_result_ready_num_rows = mysqli_num_rows($check_result_ready);
			    if($check_result_ready_num_rows>0){
  
			  echo"
			  <a id='print' class='btn btn-sm btn-primary'><span class='ion-ios-printer-outline'></span> Print</a><br/><br/>
			  <div class='view-student-result-body' id='print-area'>
				<!--heading section-->
				<table width='100%'>
				  <tr>
					<td align='center' valign='top' width='30%'>
					  <h5><strong>MINISTRY OF EDUCATION</strong></h5>
					  <h5><strong>REPORT CARD</strong></h5>
						<h5><strong>FOR</strong></h5>";
						//get information of already submitted results
						$available_result = mysqli_query($conn,"SELECT * FROM available_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term'");
						while($available_result_row = mysqli_fetch_assoc($available_result)){
						//$student_reg_no = htmlentities($available_result_row['student_reg_no']);
						//$year = htmlentities($available_result_row['year']);
						$year = htmlentities($available_result_row['year']);
						$student_class = htmlentities($available_result_row['class']);
						$arm = htmlentities($available_result_row['arm']);
						}
						echo"
					<h4><strong>";
					if($student_class=="JS1"||$student_class=="JS2"||$student_class=="JS3"){
						echo"JUNIOR SECONDARY SCHOOLS";
					}else if($student_class=="SS1"||$student_class=="SS2"||$student_class=="SS3"){
						echo"SENIOR SECONDARY SCHOOLS";
					}else if($student_class=="PRY1"||$student_class=="PRY2"||$student_class=="PRY3"||$student_class=="PRY4"||$student_class=="PRY5"){
						echo"PRIMARY SCHOOLS";
					}
					echo"</strong></h4>
					</td>
					<td align='center' width='25%' valign='top'><h5><strong>";
					if($form_term=="1"){
						echo"FIRST TERM";
					}else if($form_term=="2"){
						echo"SECOND TERM";
				  }else if($form_term=="3"){
				  	echo"THIRD TERM";
				  }else if($form_term=="4"){
				  	echo"MOCK EXAM";
				  }
					
					echo"</strong></h5></td>
					<td  width='45%' valign='top'>
					  <table width='100%'>
						<tr>
						  <td width=''><strong>SCHOOL</strong></td>
						  <td width='50%' align='left' class='broken-bottom-border'>";
						  $school_details = mysqli_query($conn,"SELECT * FROM school_details");
						  $school_details_row = mysqli_fetch_assoc($school_details);
						  $school_name = htmlentities($school_details_row['name']);
						  echo"
						  $school_name
						  </td>
						  <td width=''><strong>CODE NO</strong></td>
						  <td width='15%' class='broken-bottom-border'></td>
						</tr>
					  </table>
					  <table width='100%'>
						<tr>
						  <td width=''><strong>LED</strong></td>
						  <td width='57%' class='broken-bottom-border'></td>
						  <td width=''><strong>SEX</strong></td>
						  <td width='25%' class='broken-bottom-border'>";
						  $student_details = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$loggedin_user' AND removed='no' ");
						  $student_details_row = mysqli_fetch_assoc($student_details);
						  $student_fullname = htmlentities($student_details_row['fullname']);
						  $student_reg_no = htmlentities($student_details_row['reg_no']);
						  $student_guardian_name = ($student_details_row['guardian_name']);
						  $student_gender = htmlentities($student_details_row['gender']);
						  $student_date_of_birth = htmlentities($student_details_row['date_of_birth']);
						  $student_date_of_birth = date("d-m-Y", strtotime($student_date_of_birth));
						  echo"
						  $student_gender
						  </td>
						</tr>
					  </table>
					  <table width='100%'>
						<tr>
						  <td width=''><strong>STUDENT'S NAME</strong></td>
						  <td width='70%' class='broken-bottom-border'>$student_fullname</td>
						</tr>
					  </table>
					  <table width='100%'>
						<tr>
						  <td width=''><strong>DATE OF BIRTH</strong></td>
						  <td width='40%' class='broken-bottom-border'>$student_date_of_birth</td>
						  <td width=''><strong>CLASS</strong></td>
							<td width='20%' class='broken-bottom-border'>";
							//from $available_result query
						  if(isset($student_class)){echo $student_class;}
						  if(isset($arm)){echo " $arm";}
						  echo"						
						  </td>
						</tr>
					  </table>
					  <table width='100%'>
						<tr>
						  <td width=''><strong>ADMISSION NUMBER</strong></td>
						  <td width='38%' class='broken-bottom-border'>";if(isset($student_reg_no)){echo $student_reg_no;}echo"</td>
						  <td width=''><strong>YEAR</strong></td>
						  <td width='12%' class='broken-bottom-border'>";if(isset($year)){echo $year;}echo"</td>
						</tr>
					  </table>
					</td>
				  </tr>
				</table>";
  
				$attendance_query = mysqli_query($conn,"SELECT * FROM attendance WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$attendance_query_row = mysqli_fetch_assoc($attendance_query);
				$total_days = htmlentities($attendance_query_row['total_days']);
				$days_present = htmlentities($attendance_query_row['days_present']);
				$punctual = htmlentities($attendance_query_row['punctual']);
				$absent = htmlentities($attendance_query_row['absent']);
				
				echo"
				<!--attendance section-->
				<h5>1. ATTENDANCE</h5>
				<div>(Regularity & Punctuality)</div>
				<table width='100%' border='1' class='bordered'>
				  <tr>
					<td width='55%'></td>
					<td width='15%' align='center'>School</td>
					<td width='15%' align='center'>Sports</td>
					<td width='15%' align='center'>Other Organised Activities</td>
				  </tr>
				  <tr>
					<td>No of Time School Opened/Activities Held</td>
					<td align='center'>";
					if($total_days==''){echo "-";}else{echo $total_days;}
					echo"
					</td>
					<td></td>
					<td></td>
				  </tr>
				  <tr>
					<td>No of Times Present</td>
					<td align='center'>";
					if($days_present==''){echo "-";}else{echo $days_present;}				  
					echo"
					</td>
					<td></td>
					<td></td>
				  </tr>
				  <tr>
					<td>No of Times Punctual</td>
					<td align='center'>";
					if($punctual==''){echo "-";}else{echo $punctual;}
					echo"
					</td>
					<td></td>
					<td></td>
				  </tr>
				  <tr>
					<td>No of Times Absent</td>
					<td align='center'>";
					if($absent==''){echo "-";}else{echo $absent;}
					echo"
					</td>
					<td></td>
					<td></td>
				  </tr>
				</table>";
				
				//conduct section 
				$conduct_query = mysqli_query($conn,"SELECT * FROM conduct WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$conduct_query_row = mysqli_fetch_assoc($conduct_query);
				$conduct_comment = htmlentities($conduct_query_row['comment']);
				$conduct_remark = htmlentities($conduct_query_row['remark']);
				//select only the first 70 characters of the remark
				$conduct_remark_1 = substr($conduct_remark, 0, 70);
				//select only the first 70 characters of the comment
				$conduct_comment_1 = substr($conduct_comment, 0, 70);
				$conduct_comment_2 = substr($conduct_comment, 71, 140);
				$conduct_comment_3 = substr($conduct_comment, 141, 210);
				$conduct_comment_4 = substr($conduct_comment, 211, 280);
				echo"			
				<!--conduct section-->
				<div>2. CONDUCT (Special Reports During The Term)</div>
				<table width='100%' border='1'>
				  <tr>
					<td width='50%'>
					  <table width='100%' class='n-bordered'>
						<tr>
						  <td width='70%' align='center'>GREEN for Examplary conduct</td>
						  <td width='30%' align='center'>RED for Bad conduct</td>
						</tr>
					  </table>
					  <table width='100%' class='bordered' border='1'>
						<tr>
						  <td width='30%' align='center'>Number</td>
						  <td width='40%' align='center'>Deed</td>
						  <td width='30%' align='center'>Number</td>
						</tr>
						<tr height='26px'>
						  <td width='30%' align='center'></td>
						  <td width='40%' align='center'></td>
						  <td width='30%' align='center'></td>
						</tr>
					  </table>
					  <table width='100%' class='n-bordered'>
						<tr>
						  <td width='40%' align='center'>Cleanliness Rating</td>
						  <td width='15%' align='center'>Good</td>
						  <td width='15%' align='center'>Fair</td>
						  <td width='25%' align='center'>Poor</td>
						</tr>
					  </table>
					</td>                            
					<td width='50%'>
					  <table width='100%'>
						<tr>
						  <td width='' align='center' valign='top'>Comments</td>
						  <td width='80%' align='left' valign='top'>
							<table width='100%'>
							  <tr height='16px'><td class='broken-bottom-border'>$conduct_comment_1</td></tr>
							  <tr height='16px'><td class='broken-bottom-border'>$conduct_comment_2</td></tr>
							  <tr height='16px'><td class='broken-bottom-border'>$conduct_comment_3</td></tr>
							  <tr height='16px'><td class='broken-bottom-border'>$conduct_comment_4</td></tr>
							</table>
						  </td>
						</tr>
						<tr>
						  <td width='' align='center' valign='top'>Remarks</td>
						  <td width='80%' align='left' valign='top' class='broken-bottom-border'>$conduct_remark_1</td>
						</tr>
					  </table>
					</td>
				  </tr>
				</table>";
  
				$physical_dev_query = mysqli_query($conn,"SELECT * FROM physical_dev WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$physical_dev_query_row = mysqli_fetch_assoc($physical_dev_query);
				$start_height = htmlentities($physical_dev_query_row['start_height']);
				$end_height = htmlentities($physical_dev_query_row['end_height']);
				$start_weight = htmlentities($physical_dev_query_row['start_weight']);
				$end_weight =htmlentities($physical_dev_query_row['end_weight']);
				$illness_days = htmlentities($physical_dev_query_row['illness_days']);
				$nature_of_illness = htmlentities($physical_dev_query_row['nature_of_illness']);
				
				echo"
			  
				<!--physical development, health and hygiene section-->
				<div>3. PHYSICAL DEVELOPMENT, HEALTH & HYGIENE</div>
				  <table width='100%' border='1' class='bordered'>
					<tr>
					  <td width='48%'>
						<table width='100%' class='n-bordered'>
						  <tr>
							<td width='25%' align='center' valign='top'>HEIGHT</td>
							<td width='25%' align='center' valign='top'></td>
							<td width='25%' align='center' valign='top'>WEIGHT</td>
							<td width='25%' align='center' valign='top'></td>
						  </tr>                                  
						  <tr>
							<td width='25%' align='center' valign='top'>Beginning of Term</td>
							<td width='25%' align='center' valign='top'>End of Term</td>
							<td width='25%' align='center' valign='top'>Beginning of Term</td>
							<td width='25%' align='center' valign='top'>End of Term</td>
						  </tr>                                  
						</table>
					  </td>                           
					  <td width='52%' valign='top'>
						<table width='100%' class='n-bordered'>
						  <tr>
							<td width='25%' align='center' valign='top'><div>No of Days</div><div>Absent due</div><div>to illness</div></td>
							<td width='25%' align='center' valign='top'><div>Nature of </div><div>illness</div></td>
						  </tr>                                  
						</table>
					  </td>
					</tr>                         
					<tr>
					  <td width='48%'>
						<table width='100%' class='n-bordered'>
						  <tr>
							<td width='25%' align='right' valign='top'>";if($start_height==''){echo'-';}else{echo $start_height;} echo" M</td>
							<td width='25%' align='right' valign='top'>";if($end_height==''){echo'-';}else{echo $end_height;} echo" M</td>
							<td width='25%' align='right' valign='top'>";if($start_weight==''){echo'-';}else{echo $start_weight;} echo" kg</td>
							<td width='25%' align='right' valign='top'>";if($end_weight==''){echo'-';}else{echo $end_weight;} echo" kg</td>
						  </tr>                                  
						</table>
					  </td>
					  <td width='52%'>
						<table width='100%' class='n-bordered'>
						  <tr>
							<td width='25%' align='center' valign='top'>";if($illness_days==''){echo'-';}else{echo $illness_days;} echo"</td>
							<td width='25%' align='center' valign='top'>";if($nature_of_illness==''){echo'-';}else{echo $nature_of_illness;} echo"</td>
						  </tr> 
						</table>
					  </td>
					</tr>
				  </table>";
  
				$attendance_query = mysqli_query($conn,"SELECT * FROM attendance WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$attendance_query_row = mysqli_fetch_assoc($attendance_query);
				$total_days = htmlentities($attendance_query_row['total_days']);
				$days_present = htmlentities($attendance_query_row['days_present']);
				$punctual = htmlentities($attendance_query_row['punctual']);
				$absent = htmlentities($attendance_query_row['absent']);
				echo"
				<!--PERFORMANCE IN SUBJECTS-->
				<div>4. PERFORMANCE IN SUBJECTS</div>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);

				//initialize total_average value as zero
				  $total_average = 0;
				  $total_out_of = 0;
				  //while loop to get all available subjects
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_id = htmlentities($subjects_query_row['id']);
				  $subject_name = nl2br(htmlentities($subjects_query_row['subject_name']));
				  //while going through each available subjects, 
				  //check those of the student that has a score inputed in the subject_result
				  $get_result_total_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND subject_id='$subject_id' AND removed='no'");
				  //get the total scores for only those with scores available
				  while($total_score_row = mysqli_fetch_assoc($get_result_total_score)){
					  $total_score = htmlentities($total_score_row['total']);
					  //if the result exists
					  if(isset($total_score)){
						  //if result exists and empty, make it equal to zero(0)
						  if($total_score==''){
							  $total_score=0;
							  }
							  $total_average = $total_average + $total_score;
							  $total_out_of = $total_out_of + 100;
						  }//end if isset($total_score)
					  }//end while loop for available total scores
				}//end while loop for available subjects
				//make sure $total_out_of is defined (>0)
				if($total_out_of>0){
					$percentage = number_format((float)(($total_average*100)/$total_out_of),2,'.','');
					//$percentage = round(($total_average*100)/$total_out_of);
					//////////////////////////////////////////////////
					//get the overall grade of the percentage score
					//the grades_query is dependent on which class is being considered
					while($grades_row = mysqli_fetch_assoc($grades_query)){ 
					  $minimum = htmlentities($grades_row['minimum']);
						$maximum = htmlentities($grades_row['maximum']);
						//round up the percentage to a whole number and use for comparison
						$round_percentage = round($percentage);
					  //find a grade that fits the percentage score and get the grade letter
					  if(($round_percentage>=$minimum) ){
								if(($round_percentage<=$maximum)){
									$percentage_grade_letter = htmlentities($grades_row['grade_letter']);
									//echo $percentage_grade_letter;
									break;
								}
							}
						}
					  //if the grade is not available, produce an error
					  if(!isset($percentage_grade_letter)){
						  $percentage_grade_letter = '-';
						  }
				}else{
					$percentage = '';
					$percentage_grade_letter = '';
					$total_out_of = '';
					}//end $total_out_of is defined (>0)
				
				
				echo"			  
				<table width='100%' border='1' class='bordered'>
				  <tr>
					<td width='20%'>
					  <table width='100%' valign='top' class='n-bordered'>
						<tr width='100%'>
						  <td width='' align='center'>Total</td>
						  <td width='60%' align='center'>$total_average/$total_out_of</td>
						</tr>
						<tr width='100%'>
						  <td width='' align='center'>Grade</td>
						  <td width='60%' align='center'>$percentage_grade_letter</td>
						</tr>
						<tr width='100%'>
						  <td width='' align='center'>%</td>
						  <td width='60%' align='center'>$percentage %</td>
						</tr>
						<tr width='100%'>
						  <td width='' align='center'>Position</td>
						  <td width='60%'></td>
						</tr>
					  </table>
					</td>";
				echo"
					<td width='4%'><div class='vertical-text'></div></td>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);

				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				$subject_name = nl2br(htmlentities($subjects_query_row['subject_name']));
				echo"
					<td width='4%'><div class='vertical-text'>$subject_name</div></td>
					";			  
				}
				//display senior secondary result 
				echo"
				  </tr>
				  <tr>
					<td width=''>Attendance (&#37;)</td>";
				//call for the $subjects_query and $grades_query
					$subjects_by_class = new subjectResult();
					list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);
					while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
						echo"
						<td width='' align='center'></td>
							";			  
						}
						echo"
				  </tr>
				  <tr>
					<td width=''>Cont. Asses. Scores</td>
					<td width='' align='center'>";
					//display the total score for Continuous Assessment
					//depending on which result is to be checked
					if($form_term==4){
						echo"-";
					}else{
						echo"40";
					}
					echo"
					</td>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);

				//$number_of_subjects = mysqli_num_rows($subjects_query);
  
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_name = htmlentities($subjects_query_row['subject_name']);
				  $subject_id = htmlentities($subjects_query_row['id']);
				  $get_result_ca_1_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND subject_id='$subject_id' AND removed='no'");
					  echo"
					  <td width='4%' align='center'>
					  ";
					  //check those  results that have scores in them or that were inputed in them
					  //...and display the CA score
					  while($ca_1_score_row = mysqli_fetch_assoc($get_result_ca_1_score)){
					  $ca_1_score = htmlentities($ca_1_score_row['ca_1']);
							  if(isset($ca_1_score) && $ca_1_score==''){
							  echo"-";			  
							  }else{
							  echo"$ca_1_score";
							  }
						  }
					  echo"
					  </td>
						  ";			  
				}
				echo"
				  </tr>				
				  <tr>
					<td width=''>Exam Scores</td>
					<td width='' align='center'>";
					//display the total score for Exam Score
					//depending on which result is to be checked
					if($form_term==4){
						echo"100";
					}else{
						echo"40";
					}
					echo"
					</td>";
					//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);
				//$number_of_subjects = mysqli_num_rows($subjects_query);
  
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_name = htmlentities($subjects_query_row['subject_name']);
				  $subject_id = htmlentities($subjects_query_row['id']);
				  $get_result_exam_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND subject_id='$subject_id' AND removed='no'");
					  echo"
					  <td width='4%' align='center'>
					  ";			  
					  //check those  results that have scores in them or that were inputed in them
					  //...and display the EXAM score
					  while($exam_score_row = mysqli_fetch_assoc($get_result_exam_score)){
					  $exam_score = htmlentities($exam_score_row['exam']);
						  if(isset($exam_score) && $exam_score==''){
						  echo"-";			  
						  }else{
						  echo"$exam_score";
						  }
					  }
					  echo"
					  </td>
						  ";			  
				}
				echo"			  
				  </tr>
				  <tr>
					<td width=''>Weighted Average</td>
					<td width='' align='center'>100</td>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);
				//$number_of_subjects = mysqli_num_rows($subjects_query);
  
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_name = htmlentities($subjects_query_row['subject_name']);
				  $subject_id = htmlentities($subjects_query_row['id']);
				  $get_result_total_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND subject_id='$subject_id' AND removed='no'");
					  echo"
					  <td width='4%' align='center'>
					  ";
					  //check those  results that have scores in them or that were inputed in them
					  //...and display the TOTAL score
					  while($total_score_row = mysqli_fetch_assoc($get_result_total_score)){
						  $total_score = htmlentities($total_score_row['total']);
						  if(isset($total_score) && $total_score==''){
						  echo"-";
						  }else{
						  echo"$total_score";
						  }
					  }
					  echo"
					  </td>
						  ";			  
				}
				echo"
				  </tr>";
				  //only show this section for third term
				  if($form_term == '3'){
					  
				  echo"
				  <tr>
					<td width=''>Second Term Scores</td>
					<td width='' align='center'>100</td>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);
				//$number_of_subjects = mysqli_num_rows($subjects_query);
  
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_name = htmlentities($subjects_query_row['subject_name']);
				  $subject_id = htmlentities($subjects_query_row['id']);
					$get_result_second_term_total_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='2' AND subject_id='$subject_id' AND removed='no'");
					  echo"
					  <td width='4%' align='center'>
						";
					//check if the subject has a score entry
					while($second_term_total_score_row = mysqli_fetch_assoc($get_result_second_term_total_score)){
						$second_term_total_score = htmlentities($second_term_total_score_row['total']);
						//if a score entry exists for subject 
							//if the score is empty, put a dash
				  		if($second_term_total_score==''){
					  		echo"-";			  
							}else{
					  		echo"$second_term_total_score";
							}
					}
					echo"
					  </td>
						  ";			  
				}
				echo"
				  </tr>
				  <tr>
					<td width=''>First Term Scores</td>
					<td width='' align='center'>100</td>";
				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);
				//$number_of_subjects = mysqli_num_rows($subjects_query);
  
				while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
				  $subject_name = htmlentities($subjects_query_row['subject_name']);
				  $subject_id = htmlentities($subjects_query_row['id']);
				  $get_result_first_term_total_score = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$loggedin_user' AND session='$form_session' AND term='1' AND subject_id='$subject_id' AND removed='no'");
					  echo"
					  <td width='4%' align='center'>
						";
					//check if the subject has a score entry
					while($first_term_total_score_row = mysqli_fetch_assoc($get_result_first_term_total_score)){
						$first_term_total_score = htmlentities($first_term_total_score_row['total']);
							//if the score is empty, put a dash
				  		if($first_term_total_score==''){
					  		echo"-";			  
							}else{
					  		echo"$first_term_total_score";
							}
					}
					echo"
					  </td>
						  ";			  
				}
			  }//end third term section
				echo"
					</tr>					
				  <tr>
					<td width=''>Teacher's Signature</td>
					<td width='' align='center'></td>";

				//call for the $subjects_query and $grades_query
				$subjects_by_class = new subjectResult();
				list($subjects_query,$grades_query) = $subjects_by_class->subjects_by_class($conn,$student_class,$form_session);

					while($subjects_query_row = mysqli_fetch_assoc($subjects_query)){
						$subject_name = htmlentities($subjects_query_row['subject_name']);
						$subject_id = htmlentities($subjects_query_row['id']);
						$get_result_grade = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND subject_id='$subject_id' AND removed='no'");
							echo"
							<td width='4%' align='center'>
							";			  
							//check those  results that have scores in them or that were inputed in them
							//...and display the EXAM score
							while($result_grade_row = mysqli_fetch_assoc($get_result_grade)){
							$score_grade = htmlentities($result_grade_row['grade']);
							echo $score_grade ;
							}
							echo"
							</td>
								";			  
					}
					echo"
						</tr>
				</table>";
				//}
				
				echo"			
				<!--SPORTING ACTIVITIES-->
				<div>5. SPORTING ACTIVITIES</div>
				<table width='100%' border='1' class='bordered'>
				  <tr>
					<td align='left'>Events</td>
					<td align='center'>Indoor Games</td>
					<td align='center'>Balls Games</td>
					<td align='center'>Combative Games</td>
					<td align='center'>Track</td>
					<td align='center'>Jumps</td>
					<td align='center'>Throws</td>
					<td align='center'>Swimming</td>
					<td align='center'>Weight Lifting</td>
				  </tr>
				  <tr >
					<td align='left'>Level Attained</td>";
  
				$sporting_activities_query = mysqli_query($conn,"SELECT * FROM sporting_activities WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$sporting_activities_query_row = mysqli_fetch_assoc($sporting_activities_query);
				$indoor_games = htmlentities($sporting_activities_query_row['indoor_games']);
				$balls_games = htmlentities($sporting_activities_query_row['balls_games']);
				$combat_games = htmlentities($sporting_activities_query_row['combat_games']);
				$track = htmlentities($sporting_activities_query_row['track']);
				$jumps = htmlentities($sporting_activities_query_row['jumps']);
				$throws = htmlentities($sporting_activities_query_row['throws']);
				$swimming = htmlentities($sporting_activities_query_row['swimming']);
				$weight_lifting = htmlentities($sporting_activities_query_row['weight_lifting']);
				$sport_comment = htmlentities($sporting_activities_query_row['comment']);
				$signature = htmlentities($sporting_activities_query_row['signature']);
				
				echo"
					<td align='center'>";if($indoor_games==''){echo'-';}else{echo $indoor_games;} echo"</td>
					<td align='center'>";if($balls_games==''){echo'-';}else{echo $balls_games;} echo"</td>
					<td align='center'>";if($combat_games==''){echo'-';}else{echo $combat_games;} echo"</td>
					<td align='center'>";if($track==''){echo'-';}else{echo $track;} echo"</td>
					<td align='center'>";if($jumps==''){echo'-';}else{echo $jumps;} echo"</td>
					<td align='center'>";if($throws==''){echo'-';}else{echo $throws;} echo"</td>
					<td align='center'>";if($swimming==''){echo'-';}else{echo $swimming;} echo"</td>
					<td align='center'>";if($weight_lifting==''){echo'-';}else{echo $weight_lifting;} echo"</td>
				  </tr>
				</table>";
				//sport comment section 
				$sport_query = mysqli_query($conn,"SELECT * FROM sporting_activities WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$sport_query_row = mysqli_fetch_assoc($sport_query);
				$sport_comment = htmlentities($sport_query_row['comment']);
				//select only the first 70 characters of the comment
				$sport_comment_1 = substr($sport_comment, 0, 120);
				$sport_comment_2 = substr($sport_comment, 121,220);
				echo"
				<table width='100%'>
				  <tr>
					<td align='left'>Comments </td>
					<td align='left' width='92%' class='broken-bottom-border'> $sport_comment_1</td>
				  </tr>
				</table>
				<table width='100%'>
				  <tr>
					<td align='left' width='80%' class='broken-bottom-border'>$sport_comment_2</td>
					<td align='left'>Signature</td>
					<td align='left' width='15%' class='broken-bottom-border'></td>
				  </tr>
				</table>
			  
				<!--CLUBS, YOUTH ORGANIZATIONS, ETC-->
				<div>6. CLUBS, YOUTH ORGANIZATIONS, ETC</div>
				<table width='100%' border='1' class='bordered'>
				  <tr>
					<td align='center' width='20%'>Organization</td>
					<td align='center' width='20%'>Office Held</td>
					<td align='center'>Significant Contribution</td>
					</tr>";
					
					$clubs_query = mysqli_query($conn,"SELECT * FROM clubs WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' AND removed='no'");
					$clubs_num_rows = mysqli_num_rows($clubs_query);
					
					if($clubs_num_rows==0){
						for($i=0;$i<2;$i++){
							echo"
							<tr height='20px'>
								<td align='center'>-</td>
								<td align='center'>-</td>
								<td align='center'>-</td>
							</tr>";
						}	//end for loop
					}elseif($clubs_num_rows==1){
							while($clubs_query_row = mysqli_fetch_assoc($clubs_query)){
								$organization = htmlentities($clubs_query_row['organization']);
								$office_held = htmlentities($clubs_query_row['office_held']);
								$significant_contribution = htmlentities($clubs_query_row['significant_contribution']);
								
								echo"
									<tr height='20px'>
										<td align='center'>$organization</td>
										<td align='center'>$office_held</td>
										<td align='center'>$significant_contribution</td>
									</tr>";	
								}//end while loop	
								echo"
								<tr height='20px'>
									<td align='center'>-</td>
									<td align='center'>-</td>
									<td align='center'>-</td>
								</tr>";
						}else{
						while($clubs_query_row = mysqli_fetch_assoc($clubs_query)){
							$organization = htmlentities($clubs_query_row['organization']);
							$office_held = htmlentities($clubs_query_row['office_held']);
							$significant_contribution = htmlentities($clubs_query_row['significant_contribution']);
							
							echo"
								<tr height='20px'>
									<td align='center'>$organization</td>
									<td align='center'>$office_held</td>
									<td align='center'>$significant_contribution</td>
								</tr>";	
						}//end while loop
					}//end else
				echo"
				</table>";			  
				//class_teacher comment section 
				$class_teacher_comment_query = mysqli_query($conn,"SELECT * FROM class_teacher_comment WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$class_teacher_comment_query_row = mysqli_fetch_assoc($class_teacher_comment_query);
				$class_teacher_comment = htmlentities($class_teacher_comment_query_row['comment']);
				//select only the first 70 characters of the comment
				$class_teacher_comment_1 = substr($class_teacher_comment, 0, 120);
				$class_teacher_comment_2 = substr($class_teacher_comment, 121,220);
				echo"
				<table width='100%'>
				  <tr>
					<td align='left'>Class Master's Comments</td>
					<td align='left' width='80%' class='broken-bottom-border'>$class_teacher_comment_1</td>
				  </tr>
				</table>
				<table width='100%'>
				  <tr>
					<td align='left' width='80%' class='broken-bottom-border'>$class_teacher_comment_2</td>
					<td align='left'>Signature</td>
					<td align='left' width='15%' class='broken-bottom-border'></td>
				  </tr>
				</table>";
				
				//principal_teacher comment section 
				$principal_comment_query = mysqli_query($conn,"SELECT * FROM principal_comment WHERE student_id='$studentId' AND session='$form_session' AND term='$form_term' ");
				$principal_comment_query_row = mysqli_fetch_assoc($principal_comment_query);
				$principal_comment = htmlentities($principal_comment_query_row['comment']);
				//select only the first 120 characters of the comment
				$principal_comment_1 = substr($principal_comment, 0, 120);
				$principal_comment_2 = substr($principal_comment, 121,220);
				echo"
				<table width='100%'>
				  <tr>
					<td align='left'>Principal's Comments</td>
					<td align='left' width='80%' class='broken-bottom-border'>$principal_comment_1</td>
				  </tr>
				</table>
				<table width='100%'>
				  <tr height='20px'>
					<td align='left' width='100%' class='broken-bottom-border'>$principal_comment_2</td>
				  </tr>
				</table>
				<table width='100%'>
				  <tr>
					<td align='right'>Signature/School Stamp &amp; Date</td>
				  </tr>
				</table>";
				//resumption details
				//if form term is equivalent to MOCK EXAM(4) then make resumption term to be 2(SECOND TERM)
				$resumption_term = $form_term;
				if($resumption_term = 4){
					$resumption_term = 2 ;
				}
				$resumption_query = mysqli_query($conn,"SELECT * FROM resumption WHERE current_term='$resumption_term' AND current_session='$form_session' ");
				$resumption_query_row = mysqli_fetch_assoc($resumption_query);
				$resumption_date = htmlentities($resumption_query_row['resumption_date']);

				echo"
				<table width='100%'>
				  <tr>
					<td align='left'>Parent's/Guardian's Name</td>
					<td align='left' width='50%' class='broken-bottom-border'>$student_guardian_name</td>
					<td align='left'>Signature</td>
					<td align='left' width='15%' class='broken-bottom-border'></td>
				  </tr>
				</table>
				<table width='100%'>
				  <tr>
					<td align='left'>Parents are to please return this card to the school on</td>
					<td align='center' width='37%' class='broken-bottom-border'>$resumption_date</td>
					<td align='left'>when next term begins)</td>
				  </tr>
				</table>
			  </div><!--view-student-result-body-->";
			  ///////////////////////////////////
			  ////add card to used cards list////
			  $insert_used_cards = mysqli_query($conn,"INSERT INTO used_cards VALUES (default,'$form_pin_no','$card_serial_no','$studentId','$form_session','$form_term','$date','$time')");
			  //if the card has been successfully added to the used cards section
			  if($insert_used_cards){
				  //update card status as used if it has not been set to used before
				if($card_status == 'no'){
					$update_card_status = mysqli_query($conn,"UPDATE cards SET used='yes' WHERE card_no='$form_pin_no'");
					}
				  }
			  ///////////////////////////////////
  
			    }else{
			    echo"<div class='text-danger danger-bg'>Your result is not available!</div>";
				  }			  			  
			  }else{
			  echo"<div class='text-danger danger-bg'>Your have not fully paid for your school fees!</div>";
				  }			  			  
			}else{
			echo"<div class='text-danger danger-bg'>You have already used this card five (5) times!</div>";
			}			
		  }else{
		  echo"<div class='text-danger danger-bg'>You have already used this CARD PIN for another term or session!</div>";
			  }		  
		}else{
		  echo"<div class='text-danger danger-bg'>That CARD PIN has been used by another student!</div>";
			}		
	  }else{
		echo"<div class='text-danger danger-bg'>That CARD PIN does not exist!</div>";
		  }
	 }else{
		  echo"<div class='text-danger danger-bg'>You are not currently a student, kindly contact the school authorities!</div>";
	  }//end check student status
  }else{
	  echo"<div class='text-danger danger-bg'>Kindly login again! Unrecognised Registration Number!</div>";
  }
  
 
}// end if $_POST['view_student_result']
else
{
	//header("Location:../index.php");
	}
?>