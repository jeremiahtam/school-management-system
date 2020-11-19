<?php
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['modal_content'])){

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	if(isset($_POST['data_page'])){
		$page = $_POST['data_page'];
	}
	$modal_content = $_POST['modal_content'];

	//if the delete_type is set, give variables for the delete_type and delete_id
	if(isset($_POST['delete_type'])){
		$delete_type = $_POST['delete_type'];
		$delete_id = $_POST['delete_id'];
	}
	//if the edit_type is set, give variables for the edit_type and delete_id
	if(isset($_POST['edit_type'])){
		$edit_type = $_POST['edit_type'];
		$edit_id = $_POST['edit_id'];
	}
	//if the display_type is set, give variables for the display_type and display_id
	if(isset($_POST['display_type'])){
		$display_type = $_POST['display_type'];
		$display_id = $_POST['display_id'];
	}

	switch($modal_content){
		
		case 'add-class':
		
			echo "
				<div class='modal-content add-class-modal' id='add-class-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add A class</h4>
				</div><!--modal header-->


			<form method='post' id='add_class_form' name='add_class_form'>

				<div class='modal-body'>         

			<div class='add-class-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-6'>                     
				<label for='class_name'>Class Name</label>
				<span class='help-block'>Select a class e.g. JS3</span>
				<select class='form-control' id='class_name' name='class_name'>
					<option value=''>-select a class-</option>
					<option value='PRY1'>PRY1</option>
					<option value='PRY2'>PRY2</option>
					<option value='PRY3'>PRY3</option>
					<option value='PRY4'>PRY4</option>
					<option value='PRY5'>PRY5</option>
					<option value='JS1'>JS1</option>
					<option value='JS2'>JS2</option>
					<option value='JS3'>JS3</option>
					<option value='SS1'>SS1</option>
					<option value='SS2'>SS2</option>
					<option value='SS3'>SS3</option>
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='arm_name' class='col-form-label'>Arm Name</label>
				<span class='help-block'>Select an arm e.g. Love</span>
				<select class='form-control' id='arm_name' name='arm_name'>
					<option value=''>-select an arm-</option>
					<option value='Love'>Love</option>
					<option value='Peace'>Peace</option>
				</select>
				</div><!--end col-->
			</div><!--end row-->

			<div class='row'>
			</div>		  
				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_class' id='add_class'>Add Class
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_class_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-class':
			$query = mysqli_query($conn,"SELECT * FROM classes WHERE id='$edit_id' AND removed='no'");	  
			$get_class = mysqli_fetch_array($query);

			$db_class_name = $get_class['class'];
			$db_arm_name = $get_class['arm'];
		
			echo "
				<div class='modal-content edit-class-modal' id='edit-class-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Class</h4>
				</div><!--modal header-->


			<form method='post' id='edit_class_form' name='edit_class_form'>

				<div class='modal-body'>         

			<div class='edit-class-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-6'>                     
				<label for='class_name'>Class Name</label>
				<span class='help-block'>Select a class e.g. JS3</span>
				<select class='form-control' id='class_name' name='class_name'>
					<option value=''>-select a class-</option>
					<option value='PRY1' "; if($db_class_name=='PRY1'){echo 'selected='.'true';} echo">PRY1</option>
					<option value='PRY2' "; if($db_class_name=='PRY2'){echo 'selected='.'true';} echo">PRY2</option>
					<option value='PRY3' "; if($db_class_name=='PRY3'){echo 'selected='.'true';} echo">PRY3</option>
					<option value='PRY4' "; if($db_class_name=='PRY4'){echo 'selected='.'true';} echo">PRY4</option>
					<option value='PRY5' "; if($db_class_name=='PRY5'){echo 'selected='.'true';} echo">PRY5</option>
					<option value='JS1' "; if($db_class_name=='JS1'){echo 'selected='.'true';} echo">JS1</option>
					<option value='JS2' "; if($db_class_name=='JS2'){echo 'selected='.'true';} echo">JS2</option>
					<option value='JS3' "; if($db_class_name=='JS3'){echo 'selected='.'true';} echo">JS3</option>
					<option value='SS1' "; if($db_class_name=='SS1'){echo 'selected='.'true';} echo">SS1</option>
					<option value='SS2' "; if($db_class_name=='SS2'){echo 'selected='.'true';} echo">SS2</option>
					<option value='SS3' "; if($db_class_name=='SS3'){echo 'selected='.'true';} echo">SS3</option>
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='arm_name' class='col-form-label'>Arm Name</label>
				<span class='help-block'>Select an arm e.g. Love</span>
				<select class='form-control' id='arm_name' name='arm_name'>
					<option value=''>-select an arm-</option>
					<option value='Love'"; if($db_arm_name=='Love'){echo 'selected='.'true';} echo">Love</option>
					<option value='Peace'"; if($db_arm_name=='Peace'){echo 'selected='.'true';} echo">Peace</option>
				</select>
				</div><!--end col-->
			</div><!--end row-->

			<div class='row'>
			</div>		  
		</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_class' id='edit_class' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Class
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end edit_class_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-class':
		
			echo "
			<div class='modal-content delete-class-modal' id='delete-class-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete This Class</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_class_form' name='delete_class_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_class' id='delete_class' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_class_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;


	//////////subject section////////////
	/////////////////////////////////////

		case 'add-subject':
		
			echo "
				<div class='modal-content add-subject-modal' id='add-subject-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add A Subject</h4>
				</div><!--modal header-->


			<form method='post' id='add_subject_form' name='add_subject_form'>

				<div class='modal-body'>         

			<div class='add-subject-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-12'>                     
				  <label for='subject_name'>Subject Name</label>
				  <span class='help-block'>Fill in a subject e.g. English Lang.</span>
					  <textarea placeholder='Subject Name' row='1' column='1' class='form-control' id='subject_name' name='subject_name'></textarea>
				  </div><!--end col-->		   
			</div><!--end row-->
			
			<div class='row'>
				<div class='form-group col-md-6'>                     
					<label for='offered_by' class='col-form-label'>Offered By</label>
					<span class='help-block'>Select which class offers this subject e.g. Senior Secondary</span>
					<select class='form-control' id='offered_by' name='offered_by'>
						<option value=''>-select class-</option>
						<option value='PRY'>PRY Only</option>
						<option value='PRY,JS'>PRY and JS Only</option>
						<option value='JS'>JS Only</option>
						<option value='JS,SS'>JS and SS Only</option>
						<option value='SS'>SS Only</option>
						<option value='General'>General</option>
					</select>
				</div><!--end col-->
				<div class='form-group col-md-6'>
					<label for='session_intro' class='col-form-label'>Session Introduced</label>
					<span class='help-block'>The session in which the subject was introduced to the school e.g. 2019/2020</span>
					<select class='form-control' id='session_intro' name='session_intro'>
						<option value=''>-select session introduced-</option>";
						$session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no'");
						while($session_sql_row = mysqli_fetch_assoc($session_sql)){
							$alt_session_db = htmlentities($session_sql_row['alt_session']);
							$session_db = htmlentities($session_sql_row['session']);
							echo "<option value='$alt_session_db'>$session_db</option>";
							}	   
						 echo"
					</select>
				</div><!--end col-->
			</div><!--end row-->

			<div class='row'>
			</div>		  
				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_subject' id='add_subject'>Add Subject
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_subject_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-subject':
			$query = mysqli_query($conn,"SELECT * FROM subjects WHERE id='$edit_id' AND removed='no'");	  
			$get_subject = mysqli_fetch_array($query);

			$db_subject_name = $get_subject['subject_name'];
			$db_offered_by = $get_subject['offered_by'];
			$db_session_intro = $get_subject['session_intro'];
			$db_session_removed = $get_subject['session_removed'];
		
			echo "
				<div class='modal-content edit-subject-modal' id='edit-subject-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Subject</h4>
				</div><!--modal header-->


			<form method='post' id='edit_subject_form' name='edit_subject_form'>

				<div class='modal-body'>         

			<div class='edit-subject-info'></div><!--where alerts are displayed-->            

			<div class='row'>";
			//check if data the subject to be edited is already in use
			$subject_edit_query = mysqli_query($conn,"SELECT * FROM subject_result WHERE subject_id='$edit_id'");
			$subject_edit_rows = mysqli_num_rows($subject_edit_query);
			
					echo"
					<div class='form-group col-md-12'>                     
					<label for='subject_name'>Subject Name</label>
					<span class='help-block'>Fill in a subject e.g. English Lang.</span>";
					//prevent users from editing subject name if it already houses result records
					if($subject_edit_rows>0){
						echo"<div class='text-danger'>This field cannot be changed!</div>";
					}
					echo"
					<textarea placeholder='Subject Name' class='form-control' id='subject_name' name='subject_name'>$db_subject_name</textarea>
					</div><!--end col-->		   
			</div><!--end row-->
			
			<div class='row'>
				<div class='form-group col-md-4'>                     
					<label for='offered_by' class='col-form-label'>Offered By</label>
					<span class='help-block'>Select which class offers this subject e.g. Senior Secondary</span>
					<select class='form-control' id='offered_by' name='offered_by'>
						<option value=''>-select class-</option>
						<option value='PRY' "; if($db_offered_by=='PRY'){echo 'selected='.'true';} echo">PRY Only</option>
						<option value='PRY,JS' "; if($db_offered_by=='PRY,JS'){echo 'selected='.'true';} echo">PRY and JS Only</option>
						<option value='JS' "; if($db_offered_by=='JS'){echo 'selected='.'true';} echo">JS Only</option>
						<option value='JS,SS' "; if($db_offered_by=='JS,SS'){echo 'selected='.'true';} echo">JS and SS Only</option>
						<option value='SS' "; if($db_offered_by=='SS'){echo 'selected='.'true';} echo">SS Only</option>
						<option value='General' "; if($db_offered_by=='General'){echo 'selected='.'true';} echo">General</option>
					</select>
				</div><!--end col-->
				<div class='form-group col-md-4'>
					<label for='session_intro' class='col-form-label'>Session Introduced</label>
					<span class='help-block'>The session in which the subject was introduced to the school e.g. 2019/2020</span>
					<select class='form-control' id='session_intro' name='session_intro'>
						<option value=''>-select session introduced-</option>";
						$session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no'");
						while($session_sql_row = mysqli_fetch_assoc($session_sql)){
							$alt_session_db = htmlentities($session_sql_row['alt_session']);
							$session_db = htmlentities($session_sql_row['session']);
							echo "<option value='$alt_session_db'";				 
								if($db_session_intro==$alt_session_db){echo 'selected='.'true';}				 
								echo">$session_db</option>";
							}
									 echo"
					</select>
				</div><!--end col-->
				<div class='form-group col-md-4'>
					<label for='session_removed' class='col-form-label'>Session Cancelled</label>
					<span class='help-block'>The session in which the subject was removed from the school e.g. 2019/2020</span>
					<select class='form-control' id='session_removed' name='session_removed'>
						<option value=''>-select session removed-</option>";
						echo"
						<option value='active' "; if($db_session_removed=='active'){echo 'selected='.'true';} echo">Currently Active</option>";
						$session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no'");
						while($session_sql_row = mysqli_fetch_assoc($session_sql)){
							$alt_session_db = htmlentities($session_sql_row['alt_session']);
							$session_db = htmlentities($session_sql_row['session']);
							echo "<option value='$alt_session_db'";				 
								if($db_session_removed==$alt_session_db){echo 'selected='.'true';}				 
								echo">$session_db</option>";
							}
									 echo"
					</select>
				</div><!--end col-->
			</div><!--end row-->
			</div><!--end modal-body-->
			
			<div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
				<button type='submit' class='btn btn-outline-primary' name='edit_subject' id='edit_subject' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Subject
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_subject_form-->
					
			</div><!--end modal-content-->
			";
			break;

		case 'delete-subject':
		
			echo "
			<div class='modal-content delete-subject-modal' id='delete-subject-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete This Subject</h4>
				</div><!--modal header-->

				<div class='modal-body'>";
			//check if data the subject to be deleted is already in use
			$subject_delete_query = mysqli_query($conn,"SELECT * FROM subject_result WHERE subject_id='$delete_id'");
			$subject_delete_rows = mysqli_num_rows($subject_delete_query);
			if($subject_delete_rows>0){
				echo"
				<div class='alert alert-warning'>This subject cannot be deleted as it is been currently used in a result sheet and can no longer be edited!</div>
				";
			}else{
				echo"
			<p>Are you sure you want to delete this entry?</p>";
			}         		  
			echo"
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_subject_form' name='delete_subject_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>";         
				if($subject_delete_rows==0){
					echo"
				<button type='submit' class='btn btn-outline-danger' name='delete_subject' id='delete_subject' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>";
				}
				echo"
			</form><!--end delete_subject_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	/////////////grades section/////////////
		case 'add-grades':
		
	echo "
		<div class='modal-content add-grades-modal' id='add-grades-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Add Grades</h4>
		</div><!--modal header-->


		 <form method='post' id='add_grades_form' name='add_grades_form'>

			<div class='modal-body'>
			<div class='add-grades-info'></div><!--where alerts are displayed-->            

			<div class='row'>
			  <div class='form-group col-md-4'>                     
				<label for='minimum'>Minimum Score</label>
				<span class='help-block'>Minimum score limit e.g. 70</span>
				<input type='text' class='form-control' id='minimum' name='minimum' placeholder='Minimum Score'>
			  </div><!--end col-->		   
			  <div class='form-group col-md-4'>                     
				<label for='maximum'>Maximum Score</label>
				<span class='help-block'>Maximum score limit e.g. 70</span>
				<input type='text' class='form-control' id='maximum' name='maximum' placeholder='Maximum Score'/>
			  </div><!--end col-->		   
			  <div class='form-group col-md-4'>                     
				<label for='grade_letter' class='col-form-label'>Grade Letter</label>
				<span class='help-block'>Grade Letter e.g. A</span>
				<input type='text' class='form-control' id='grade_letter' name='grade_letter' placeholder='Grade Letter'/>
			  </div><!--end col-->
			</div><!--end row-->
			<div class='row'>
			  <div class='form-group col-md-6'>                     
				<label for='remark' class='col-form-label'>Remark</label>
				<span class='help-block'>Remark e.g. Excellent</span>
				<input type='text' class='form-control' id='remark' name='remark' placeholder='Remark'/>
			  </div><!--end col-->

			  <div class='form-group col-md-6'>                     
				<label for='offered_by' class='col-form-label'>Class Group</label>
				<span class='help-block'>Select class group for which this grade is meant for!</span>
				<select class='form-control' id='class_group' name='class_group'>
				  <option value=''>-select class group-</option>
				  <option value='PRY'>Primary School (PRY)</option>
				  <option value='JS'>Junior Secondary (JS)</option>
				  <option value='SS'>Senior Secondary (SS)</option>
				</select>
			  </div><!--end col-->

			  </div><!--end row-->
			</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_grades' id='add_grades'>Add Grade
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_grades_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-grades':
			$query = mysqli_query($conn,"SELECT * FROM grades WHERE id='$edit_id' AND removed='no'");	  
			$get_grades = mysqli_fetch_array($query);

			$db_minimum = $get_grades['minimum'];
			$db_maximum = $get_grades['maximum'];
			$db_grade_letter = $get_grades['grade_letter'];
			$db_remark = $get_grades['remark'];
			$db_class_group = $get_grades['class_group'];
		
			echo "
				<div class='modal-content edit-grades-modal' id='edit-grades-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Grades</h4>
				</div><!--modal header-->


			<form method='post' id='edit_grades_form' name='edit_grades_form'>

			<div class='modal-body'>         

			<div class='edit-grades-info'></div><!--where alerts are displayed-->            

			  <div class='row'>

				<div class='form-group col-md-4'>                     
				  <label for='minimum'>Minimum Score</label>
				  <span class='help-block'>Minimum score limit e.g. 70</span>
				  <input type='text' class='form-control' id='minimum' name='minimum' placeholder='Minimum Score' value='$db_minimum'>
				</div><!--end col-->		   

				<div class='form-group col-md-4'>                     
				  <label for='maximum'>Maximum Score</label>
				  <span class='help-block'>Maximum score limit e.g. 70</span>
				  <input type='text' class='form-control' id='maximum' name='maximum' placeholder='Maximum Score' value='$db_maximum'/>
				</div><!--end col-->		   

				<div class='form-group col-md-4'>                     
				  <label for='grade_letter' class='col-form-label'>Grade Letter</label>
				  <span class='help-block'>Grade Letter e.g. A</span>
				  <input type='text' class='form-control' id='grade_letter' name='grade_letter' placeholder='Grade Letter' value='$db_grade_letter'/>
				</div><!--end col-->
			  </div><!--end row-->

			  <div class='row'>
				<div class='form-group col-md-6'>                     
				  <label for='remark' class='col-form-label'>Remark</label>
				  <span class='help-block'>Remark e.g. Excellent</span>
				  <input type='text' class='form-control' id='remark' name='remark' placeholder='Remark' value='$db_remark'/>
				</div><!--end col-->

				<div class='form-group col-md-6'>                     
				  <label for='offered_by' class='col-form-label'>Class Group</label>
				  <span class='help-block'>Select class group for which this grade is meant for!</span>
				  <select class='form-control' id='class_group' name='class_group'>
				    <option value=''>-select class group-</option>
				    <option value='PRY' "; if($db_class_group=='PRY'){echo 'selected='.'true';} echo">Primary School (PRY)</option>
				    <option value='JS' "; if($db_class_group=='JS'){echo 'selected='.'true';} echo">Junior Secondary (JS)</option>
				    <option value='SS' "; if($db_class_group=='SS'){echo 'selected='.'true';} echo">Senior Secondary (SS)</option>
				  </select>
			    </div><!--end col-->

			  </div><!--end row-->


			</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_grades' id='edit_grades' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Grade
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_grades_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-grades':
		
			echo "
			<div class='modal-content delete-grades-modal' id='delete-grades-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete This Grade</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_grades_form' name='delete_class_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_grades' id='delete_grades' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_grades_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

		//////////all teachers section////////////
		/////////////////////////////////////
		case 'add-all-teachers':
			echo "
				<div class='modal-content add-all-teachers-modal' id='add-all-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add A Teacher</h4>
				</div><!--modal header-->


			<form method='post' id='add_all_teachers_form' name='add_all_teachers_form'>

				<div class='modal-body'>         

			<div class='add-all-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Full Name</label>
				<span class='help-block'>Teacher's full name e.g. Mark Anthony</span>
				<input type='text' placeholder='Full Name' class='form-control' id='fullname' name='fullname'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='username'>Username</label>
				<span class='help-block'>Teacher's Username e.g. 22savage.</span>
				<input type='text' placeholder='Username' class='form-control' id='username' name='username'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>
				<label for='password'>Password</label>
				<span class='help-block'>Teacher's password</span>
				<input type='text' placeholder='Password' class='form-control' id='password' name='password'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='email'>Email</label>
				<span class='help-block'>Teacher's email e.g. john@gmail.com.</span>
				<input type='text' placeholder='Email' class='form-control' id='email' name='email'/>
				</div><!--end col-->		   

			</div><!--end row-->

			<div class='row'>
			</div>		  
				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_all_teachers' id='add_all_teachers'>Add Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_all_teachers_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-all-teachers':
			$query = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$edit_id' AND removed='no'");	  
			$get_class = mysqli_fetch_array($query);

			$db_fullname = $get_class['fullname'];
			$db_username = $get_class['username'];
			$db_password = $get_class['password'];
			$db_email = $get_class['email'];
		
			echo "
				<div class='modal-content edit-all-teachers-modal' id='edit-all-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit This Teacher Entry</h4>
				</div><!--modal header-->


			<form method='post' id='edit_all_teachers_form' name='edit_all_teachers_form'>

				<div class='modal-body'>         

			<div class='edit-all-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Full Name</label>
				<span class='help-block'>Teacher's full name e.g. Mark Anthony</span>
				<input type='text' placeholder='Full Name' class='form-control' id='fullname' name='fullname' value='$db_fullname'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='username'>Username</label>
				<span class='help-block'>Teacher's Username e.g. 22savage.</span>
				<input type='text' placeholder='Username' class='form-control' id='username' name='username' value='$db_username'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>
				<label for='password'>Password</label>
				<span class='help-block'>Teacher's password</span>
				<input type='text' placeholder='Password' class='form-control' id='password' name='password' value='$db_password'/>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='email'>Email</label>
				<span class='help-block'>Teacher's email e.g. john@gmail.com.</span>
				<input type='text' placeholder='Email' class='form-control' id='email' name='email' value='$db_email'/>
				</div><!--end col-->

			</div><!--end row-->

			<div class='row'>
			</div>		  
				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_all_teachers' id='edit_all_teachers' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end edit_all_teachers_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-all-teachers':
		
			echo "
			<div class='modal-content delete-all-teachers-modal' id='delete-all-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete This Teacher</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_all_teachers_form' name='delete_all_teachers_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_all_teachers' id='delete_all_teachers' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_all_teachers_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	//************search for all-teachers section**************//
	//////////////////////////////////////////////
		
	case 'edit-search-all-teachers':
	$query = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$edit_id' AND removed='no'");	  
	$get_class = mysqli_fetch_array($query);

	$db_fullname = $get_class['fullname'];
	$db_username = $get_class['username'];
	$db_password = $get_class['password'];
	$db_email = $get_class['email'];

	echo "
		<div class='modal-content edit-search-all-teachers-modal' id='edit-search-all-teachers-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Edit This Teacher Entry</h4>
		</div><!--modal header-->


	<form method='post' id='edit_search_all_teachers_form' name='edit_search_all_teachers_form'>

		<div class='modal-body'>         

	<div class='edit-search-all-teachers-info'></div><!--where alerts are displayed-->            

	<div class='row'>

		<div class='form-group col-md-6'>                     
		<label for='fullname'>Full Name</label>
		<span class='help-block'>Teacher's full name e.g. Mark Anthony</span>
		<input type='text' placeholder='Full Name' class='form-control' id='fullname' name='fullname' value='$db_fullname'/>
		</div><!--end col-->		   

		<div class='form-group col-md-6'>                     
		<label for='username'>Username</label>
		<span class='help-block'>Teacher's Username e.g. 22savage.</span>
		<input type='text' placeholder='Username' class='form-control' id='username' name='username' value='$db_username'/>
		</div><!--end col-->		   

		<div class='form-group col-md-6'>
		<label for='password'>Password</label>
		<span class='help-block'>Teacher's password</span>
		<input type='text' placeholder='Password' class='form-control' id='password' name='password' value='$db_password'/>
		</div><!--end col-->		   

		<div class='form-group col-md-6'>                     
		<label for='email'>Email</label>
		<span class='help-block'>Teacher's email e.g. john@gmail.com.</span>
		<input type='text' placeholder='Email' class='form-control' id='email' name='email' value='$db_email'/>
		</div><!--end col-->

	</div><!--end row-->

	<div class='row'>
	</div>		  
		</div><!--end modal-body-->
	
	<div class='modal-footer'>	
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
	<button type='submit' class='btn btn-outline-primary' name='edit_search_all_teachers' id='edit_search_all_teachers' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Teacher
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</div><!--end modal-footer-->
	
	</form><!--end edit_search_all_teachers_form-->
			
		</div><!--end modal-content-->
	";
	break;

case 'delete-search-all-teachers':

	echo "
	<div class='modal-content delete-search-all-teachers-modal' id='delete-search-all-teachers-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Delete This Teacher</h4>
		</div><!--modal header-->

		<div class='modal-body'>
	<p>Are you sure you want to delete this entry?</p>         		  
		</div><!--end modal-body-->
	<div class='modal-footer'>
	<form method='post' id='delete_all_teachers_form' name='delete_all_teachers_form'>
		<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
		<button type='submit' class='btn btn-outline-danger' name='delete_search_all_teachers' id='delete_search_all_teachers' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
		<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</form><!--end delete_search_all_teachers_form-->
		</div> 
	</div><!--end modal-content-->
	";
	break;

		//////////subject teachers section////////////
		/////////////////////////////////////
		case 'add-subject-teachers':
			echo "
				<div class='modal-content add-subject-teachers-modal' id='add-subject-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add Subject Teacher</h4>
				</div><!--modal header-->


			<form method='post' id='add_subject_teachers_form' name='add_subject_teachers_form'>

				<div class='modal-body'>         

			<div class='add-subject-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Name</label>
				<span class='help-block'>Teacher's name e.g. Mark Anthony</span>
				<select class='form-control' class='form-control' id='teacher_id' name='teacher_id'>
					<option value=''>-select teacher-</option>";
				$all_teachers_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE removed='no'");
				while($all_teachers_sql_row = mysqli_fetch_assoc($all_teachers_sql)){
					$teacher_id_db = htmlentities($all_teachers_sql_row['id']);
					$username_db = htmlentities($all_teachers_sql_row['username']);
					$fullname_db = htmlentities($all_teachers_sql_row['fullname']);
					echo "<option value='$teacher_id_db'>$fullname_db ($username_db)</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='class'>Class</label>
				<span class='help-block'>Select a class e.g. JS2 Love</span>
				<select class='form-control' id='class_id' name='class_id'>
					<option value=''>-select class-</option>";
				$class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE removed='no'");
				while($class_sql_row = mysqli_fetch_assoc($class_sql)){
					$class_id_db = htmlentities($class_sql_row['id']);
					$class_db = htmlentities($class_sql_row['class']);
					$arm_db = htmlentities($class_sql_row['arm']);
					echo "<option value='$class_id_db'>$class_db $arm_db</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>
				<label for='class'>Subject</label>
				<span class='help-block'>Select a subject e.g. Mathematics</span>
				<select class='form-control' id='subject_id' name='subject_id'>
					<option value=''>-select subject-</option>";
				$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
				while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
					$subject_id_db = htmlentities($subject_sql_row['id']);
					$subject_db = nl2br($subject_sql_row['subject_name']);
					echo "<option value='$subject_id_db'>$subject_db</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_subject_teachers' id='add_subject_teachers'>Add Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_subject_teachers_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-subject-teachers':
			$query = mysqli_query($conn,"SELECT * FROM subject_teachers WHERE id='$edit_id' AND removed='no'");	  
			$get_subject_teachers = mysqli_fetch_array($query);

			$db_teacher_id = $get_subject_teachers['teacher_id'];
			$db_subject_id = $get_subject_teachers['subject_id'];
			$db_class_id = $get_subject_teachers['class_id'];
		
		
			echo "
				<div class='modal-content edit-subject-teachers-modal' id='edit-subject-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Subject Teacher</h4>
				</div><!--modal header-->


			<form method='post' id='edit_subject_teachers_form' name='edit_subject_teachers_form'>

				<div class='modal-body'>         

			<div class='edit-subject-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Name</label>
				<span class='help-block'>Teacher's name e.g. Mark Anthony</span>
				<select class='form-control' class='form-control' id='teacher_id' name='teacher_id'>
					<option value=''>-select teacher-</option>";
				$all_teachers_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE removed='no'");
				while($all_teachers_sql_row = mysqli_fetch_assoc($all_teachers_sql)){
					$teacher_id_db = htmlentities($all_teachers_sql_row['id']);
					$fullname_db = htmlentities($all_teachers_sql_row['fullname']);
					$username_db = htmlentities($all_teachers_sql_row['username']);
					echo "<option value='$teacher_id_db' ";				 
						if($db_teacher_id==$teacher_id_db){echo 'selected='.'true';}				 
						echo">$fullname_db ($username_db)</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='class'>Class</label>
				<span class='help-block'>Select a class e.g. JS2 Love</span>
				<select class='form-control' id='class_id' name='class_id'>
					<option value=''>-select class-</option>";
				$class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE removed='no'");
				while($class_sql_row = mysqli_fetch_assoc($class_sql)){
					$class_id_db = htmlentities($class_sql_row['id']);
					$class_db = htmlentities($class_sql_row['class']);
					$arm_db = htmlentities($class_sql_row['arm']);
					echo "<option value='$class_id_db' ";
						if($db_class_id==$class_id_db){echo 'selected='.'true';}
					echo">$class_db $arm_db</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>
				<label for='class'>Subject</label>
				<span class='help-block'>Select a subject e.g. Mathematics</span>
				<select class='form-control' id='subject_id' name='subject_id'>
					<option value=''>-select subject-</option>";
				$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
				while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
					$subject_id_db = htmlentities($subject_sql_row['id']);
					$subject_db = nl2br($subject_sql_row['subject_name']);
					echo "<option value='$subject_id_db' ";
						if($db_subject_id==$subject_id_db){echo 'selected='.'true';}
					echo">$subject_db</option>";
					}
				echo"
				</select>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_subject_teachers' id='edit_subject_teachers' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Subject Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_subject_teachersform-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-subject-teachers':
		
			echo "
			<div class='modal-content delete-subject-teachers-modal' id='delete-subject-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete This Teacher</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_subject_teachers_form' name='delete_subject_teachers_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_subject_teachers' id='delete_subject_teachers' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_subject_teachers_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

		//////////class teachers section////////////
		/////////////////////////////////////
		case 'add-class-teachers':
			echo "
				<div class='modal-content add-class-teachers-modal' id='add-class-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add Class Teacher</h4>
				</div><!--modal header-->


			<form method='post' id='add_class_teachers_form' name='add_class_teachers_form'>

				<div class='modal-body'>         

			<div class='add-class-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Name</label>
				<span class='help-block'>Teacher's name e.g. Mark Anthony</span>
				<select class='form-control' class='form-control' id='teacher_id' name='teacher_id'>
					<option value=''>-select teacher-</option>";
				$all_teachers_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE removed='no'");
				while($all_teachers_sql_row = mysqli_fetch_assoc($all_teachers_sql)){
					$username_db = htmlentities($all_teachers_sql_row['username']);
					$fullname_db = htmlentities($all_teachers_sql_row['fullname']);
					$teacher_id_db = htmlentities($all_teachers_sql_row['id']);
					echo "<option value='$teacher_id_db'>$fullname_db ($username_db)</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='class'>Class</label>
				<span class='help-block'>Select a class e.g. JS2 Love</span>
				<select class='form-control' id='class_id' name='class_id'>
					<option value=''>-select class-</option>";
				$class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE removed='no'");
				while($class_sql_row = mysqli_fetch_assoc($class_sql)){
					$class_id_db = htmlentities($class_sql_row['id']);
					$class_db = htmlentities($class_sql_row['class']);
					$arm_db = htmlentities($class_sql_row['arm']);
					echo "<option value='$class_id_db'>$class_db $arm_db</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_class_teachers' id='add_class_teachers'>Add Class Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_class_teachers_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-class-teachers':
			$query = mysqli_query($conn,"SELECT * FROM class_teachers WHERE id='$edit_id' AND removed='no'");	  
			$get_class_teachers = mysqli_fetch_array($query);

			$db_teacher_id = $get_class_teachers['teacher_id'];
			$db_class_id = $get_class_teachers['class_id'];
		
		
			echo "
				<div class='modal-content edit-class-teachers-modal' id='edit-class-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Class Teacher</h4>
				</div><!--modal header-->

			<form method='post' id='edit_class_teachers_form' name='edit_class_teachers_form'>

				<div class='modal-body'>         

			<div class='edit-class-teachers-info'></div><!--where alerts are displayed-->            

			<div class='row'>

				<div class='form-group col-md-6'>                     
				<label for='fullname'>Name</label>
				<span class='help-block'>Teacher's name e.g. Mark Anthony</span>
				<select class='form-control' class='form-control' id='teacher_id' name='teacher_id'>
					<option value=''>-select teacher-</option>";
				$all_teachers_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE removed='no'");
				while($all_teachers_sql_row = mysqli_fetch_assoc($all_teachers_sql)){
					$teacher_id_db = htmlentities($all_teachers_sql_row['id']);
					$username_db = htmlentities($all_teachers_sql_row['username']);
					$fullname_db = htmlentities($all_teachers_sql_row['fullname']);
					echo "<option value='$teacher_id_db' ";				 
						if($db_teacher_id==$teacher_id_db){echo 'selected='.'true';}				 
						echo">$fullname_db ($username_db)</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

				<div class='form-group col-md-6'>                     
				<label for='class'>Class</label>
				<span class='help-block'>Select a class e.g. JS2 Love</span>
				<select class='form-control' id='class_id' name='class_id'>
					<option value=''>-select class-</option>";
				$class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE removed='no'");
				while($class_sql_row = mysqli_fetch_assoc($class_sql)){
					$class_id_db = htmlentities($class_sql_row['id']);
					$class_db = htmlentities($class_sql_row['class']);
					$arm_db = htmlentities($class_sql_row['arm']);
					echo "<option value='$class_id_db' ";
						if($db_class_id==$class_id_db){echo 'selected='.'true';}
					echo">$class_db $arm_db</option>";
					}
				
				echo"
				</select>
				</div><!--end col-->		   

			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_class_teachers' id='edit_class_teachers' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Class Teacher
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_class_teachersform-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-class-teachers':
		
			echo "
			<div class='modal-content delete-class-teachers-modal' id='delete-class-teachers-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete Class Teacher</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_class_teachers_form' name='delete_class_teachers_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_class_teachers' id='delete_class_teachers' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_class_teachers_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	//************students section**************//
	//////////////////////////////////////////////
		case 'add-students':
		
			echo "
				<div class='modal-content add-students-modal' id='add-students-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add Student</h4>
				</div><!--modal header-->


			<form method='post' id='add_students_form' name='add_students_form'>

				<div class='modal-body'>         

			<div class='add-students-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='fullname'>Fullname</label>
				<span class='help-block'>Fullname e.g. John Patterson</span>
				<input type='text' class='form-control' id='fullname' name='fullname' placeholder='Fullname'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='guardian_name'>Guardian's Name</label>
				<span class='help-block'>Guardian's Name e.g. Mr & Mrs Smith</span>
				<input type='text' class='form-control' id='guardian_name' name='guardian_name' placeholder='Guardian Name'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='gender'>Gender</label>
				<span class='help-block'>Gender e.g. Male</span>
				<select  class='form-control' id='gender' name='gender'>
					<option value=''>-select gender-</option>
					<option value='male'>Male</option>
					<option value='female'>Female</option>
				</select>
				</div><!--end col-->
					</div><!--end row-->
			<div class='row'>
				<div class='form-group col-md-3'>                     
				<label for='date_of_birth' class='col-form-label'>Date of Birth</label>
				<span class='help-block'>In the format YYYY-MM-DD e.g. A</span>
				<input type='text' class='form-control' id='date_of_birth' name='date_of_birth' placeholder='Date of Birth'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>                     
				<label for='reg_no' class='col-form-label'>Reistration Number</label>
				<span class='help-block'>Student's Registration Number</span>
				<input type='text' class='form-control' id='reg_no' name='reg_no' placeholder='Registration Number'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>
				<label for='password' class='col-form-label'>Password</label>
				<span class='help-block'>Student's Password</span>
				<input type='text' class='form-control' id='password' name='password' placeholder='Password'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>                     
				<label for='gender'>Status</label>
				<span class='help-block'>Status e.g. Graduated</span>
				<select  class='form-control' id='status' name='status'>
					<option value=''>-select status-</option>
					<option value='active'>Active</option>
					<option value='graduated'>Graduated</option>
					<option value='left'>Left</option>
				</select>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_students' id='add_students'>Add Student
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_students_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-students':
			$query = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");	  
			$get_students = mysqli_fetch_array($query);

			$db_fullname = $get_students['fullname'];
			$db_guardian_name = $get_students['guardian_name'];
			$db_gender = $get_students['gender'];
			$db_date_of_birth = $get_students['date_of_birth'];
			$db_reg_no = $get_students['reg_no'];
			$db_password = $get_students['password'];
			$db_status = $get_students['status'];
		
			echo "
				<div class='modal-content edit-students-modal' id='edit-students-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Student</h4>
				</div><!--modal header-->


			<form method='post' id='edit_students_form' name='edit_students_form'>

				<div class='modal-body'>         

			<div class='edit-students-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='fullname'>Fullname</label>
				<span class='help-block'>Fullname e.g. John Patterson</span>
				<input type='text' class='form-control' id='fullname' name='fullname' placeholder='Fullname' value='$db_fullname'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='guardian_name'>Guardian's Name</label>
				<span class='help-block'>Guardian's Name e.g. Mr & Mrs Smith</span>
				<input type='text' class='form-control' id='guardian_name' name='guardian_name' placeholder='Guardian Name' value='$db_guardian_name'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>   		                     
				<label for='gender'>Gender</label>
				<span class='help-block'>Gender e.g. Male</span>
				<select class='form-control' id='gender' name='gender'>
					<option value=''>-select gender-</option>";
					$students_sql = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");
					$students_sql_row = mysqli_fetch_assoc($students_sql);
					$gender_db = htmlentities($students_sql_row['gender']);
					echo"<option value='male' "; if($db_gender=='male'){echo 'selected='.'true';}echo">Male</option>";
					echo"<option value='female' "; if($db_gender=='female'){echo 'selected='.'true';}echo">Female</option>";			
				echo"
				</select>
				
				</div><!--end col-->		   
					</div><!--end row-->
					<div class='row'>
				<div class='form-group col-md-3'>                     
				<label for='date_of_birth' class='col-form-label'>Date of Birth</label>
				<span class='help-block'>In the format YYYY-MM-DD e.g. A</span>
				<input type='text' class='form-control' id='date_of_birth' name='date_of_birth' placeholder='Date of Birth' value='$db_date_of_birth'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>                     
				<label for='reg_no' class='col-form-label'>Reistration Number</label>
				<span class='help-block'>Student's Registration Number</span>
				<input type='text' class='form-control' id='reg_no' name='reg_no' placeholder='Registration Number' value='$db_reg_no'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>
				<label for='password' class='col-form-label'>Password</label>
				<span class='help-block'>Student's Password</span>
				<input type='text' class='form-control' id='password' name='password' placeholder='Password' value='$db_password'/>
				</div><!--end col-->
				<div class='form-group col-md-3'>
				<label for='status' class='col-form-label'>Status</label>
				<span class='help-block'>Status e.g. Graduated</span>
				<select  class='form-control' id='status' name='status'>
					<option value=''>-select status-</option>";
					$status_sql = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");
					$status_sql_row = mysqli_fetch_assoc($status_sql);
					$status_db = htmlentities($status_sql_row['gender']);
					echo"<option value='active' "; if($db_status=='active'){echo 'selected='.'true';}echo">Active</option>";
					echo"<option value='graduated' "; if($db_status=='graduated'){echo 'selected='.'true';}echo">Graduated</option>";			
					echo"<option value='left' "; if($db_status=='left'){echo 'selected='.'true';}echo">Left</option>";			
				echo"
				</select>
				</div><!--end col-->

			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_students' id='edit_students' data-reg-no='$db_reg_no' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Student
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_students_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-students':
		
			echo "
			<div class='modal-content delete-students-modal' id='delete-students-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete Student</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_students_form' name='delete_students_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_students' id='delete_students' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_students_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	//************search for students section**************//
	//////////////////////////////////////////////


	case 'edit-search-students':
	$query = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");	  
	$get_students = mysqli_fetch_array($query);

	$db_fullname = $get_students['fullname'];
	$db_guardian_name = $get_students['guardian_name'];
	$db_gender = $get_students['gender'];
	$db_date_of_birth = $get_students['date_of_birth'];
	$db_reg_no = $get_students['reg_no'];
	$db_password = $get_students['password'];
	$db_status = $get_students['status'];

	echo "
		<div class='modal-content edit-search-students-modal' id='edit-search-students-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Edit Student</h4>
		</div><!--modal header-->


	<form method='post' id='edit_search_students_form' name='edit_search_students_form'>

		<div class='modal-body'>         

	<div class='edit-search-students-info'></div><!--where alerts are displayed-->            

	<div class='row'>
		<div class='form-group col-md-4'>                     
		<label for='fullname'>Fullname</label>
		<span class='help-block'>Fullname e.g. John Patterson</span>
		<input type='text' class='form-control' id='fullname' name='fullname' placeholder='Fullname' value='$db_fullname'/>
		</div><!--end col-->		   
		<div class='form-group col-md-4'>                     
		<label for='guardian_name'>Guardian's Name</label>
		<span class='help-block'>Guardian's Name e.g. Mr & Mrs Smith</span>
		<input type='text' class='form-control' id='guardian_name' name='guardian_name' placeholder='Guardian Name' value='$db_guardian_name'/>
		</div><!--end col-->		   
		<div class='form-group col-md-4'>   		                     
		<label for='gender'>Gender</label>
		<span class='help-block'>Gender e.g. Male</span>
		<select class='form-control' id='gender' name='gender'>
			<option value=''>-select gender-</option>";
			$students_sql = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");
			$students_sql_row = mysqli_fetch_assoc($students_sql);
			$gender_db = htmlentities($students_sql_row['gender']);
			echo"<option value='male' "; if($db_gender=='male'){echo 'selected='.'true';}echo">Male</option>";
			echo"<option value='female' "; if($db_gender=='female'){echo 'selected='.'true';}echo">Female</option>";			
		echo"
		</select>
		
		</div><!--end col-->		   
			</div><!--end row-->
			<div class='row'>
		<div class='form-group col-md-3'>                     
		<label for='date_of_birth' class='col-form-label'>Date of Birth</label>
		<span class='help-block'>In the format YYYY-MM-DD e.g. A</span>
		<input type='text' class='form-control' id='date_of_birth' name='date_of_birth' placeholder='Date of Birth' value='$db_date_of_birth'/>
		</div><!--end col-->
		<div class='form-group col-md-3'>                     
		<label for='reg_no' class='col-form-label'>Reistration Number</label>
		<span class='help-block'>Student's Registration Number</span>
		<input type='text' class='form-control' id='reg_no' name='reg_no' placeholder='Registration Number' value='$db_reg_no'/>
		</div><!--end col-->
		<div class='form-group col-md-3'>
		<label for='password' class='col-form-label'>Password</label>
		<span class='help-block'>Student's Password</span>
		<input type='text' class='form-control' id='password' name='password' placeholder='Password' value='$db_password'/>
		</div><!--end col-->
		<div class='form-group col-md-3'>
		<label for='status' class='col-form-label'>Status</label>
		<span class='help-block'>Status e.g. Graduated</span>
		<select  class='form-control' id='status' name='status'>
			<option value=''>-select status-</option>";
			$status_sql = mysqli_query($conn,"SELECT * FROM students WHERE id='$edit_id' AND removed='no'");
			$status_sql_row = mysqli_fetch_assoc($status_sql);
			$status_db = htmlentities($status_sql_row['gender']);
			echo"<option value='active' "; if($db_status=='active'){echo 'selected='.'true';}echo">Active</option>";
			echo"<option value='graduated' "; if($db_status=='graduated'){echo 'selected='.'true';}echo">Graduated</option>";			
			echo"<option value='left' "; if($db_status=='left'){echo 'selected='.'true';}echo">Left</option>";			
		echo"
		</select>
		</div><!--end col-->

	</div><!--end row-->

		</div><!--end modal-body-->
	
	<div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
	<button type='submit' class='btn btn-outline-primary' name='edit_search_students' id='edit_search_students' data-edit-type='$edit_type' data-edit-id='$edit_id' data-modal-page='$page'>Edit Student
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
	</div><!--end modal-footer-->
	
	</form><!--end edit_search_students_form-->
			
		</div><!--end modal-content-->
	";
	break;

	case 'delete-search-students':

	echo "
	<div class='modal-content delete-search-students-modal' id='delete-search-students-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Delete Student</h4>
		</div><!--modal header-->

		<div class='modal-body'>
	<p>Are you sure you want to delete this entry?</p>         		  
		</div><!--end modal-body-->
	<div class='modal-footer'>
	<form method='post' id='delete_students_form' name='delete_students_form'>
		<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
		<button type='submit' class='btn btn-outline-danger' name='delete_search_students' id='delete_search_students' data-delete-type='$delete_type' data-delete-id='$delete_id' data-modal-page='$page'>Delete
		<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</form><!--end delete_search_students_form-->
		</div> 
	</div><!--end modal-content-->
	";
	break;

	//************clubs section**************//
	//////////////////////////////////////////////
		case 'add-clubs':
		
			echo "
				<div class='modal-content add-clubs-modal' id='add-clubs-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add Clubs, youth, organizations, etc</h4>
				</div><!--modal header-->


			<form method='post' id='add_clubs_form' name='add_clubs_form'>

				<div class='modal-body'>         

			<div class='add-clubs-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='organization'>Organization Name</label>
				<span class='help-block'>Name of the club/organization joined</span>
				<input type='text' class='form-control' id='organization' name='organization' placeholder='Organization'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='office_held'>Office Held</label>
				<span class='help-block'>Position held</span>
				<input type='text' class='form-control' id='office_held' name='office_held' placeholder='Office Held'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='significant_contribution' class='col-form-label'>Significant Contribution</label>
				<span class='help-block'>Contributions made by the student</span>
				<input type='text' class='form-control' id='significant_contribution' name='significant_contribution' placeholder='Significant Contribution'/>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_clubs' id='add_clubs'>Add Club
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_clubs_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-clubs':
			$query = mysqli_query($conn,"SELECT * FROM clubs WHERE id='$edit_id' AND removed='no'");	  
			$get_clubs = mysqli_fetch_array($query);

			$db_organization = $get_clubs['organization'];
			$db_office_held = $get_clubs['office_held'];
			$db_significant_contribution = $get_clubs['significant_contribution'];
		
			echo "
				<div class='modal-content edit-clubs-modal' id='edit-clubs-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Club</h4>
				</div><!--modal header-->


			<form method='post' id='edit_clubs_form' name='edit_clubs_form'>

				<div class='modal-body'>         

			<div class='edit-clubs-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='organization'>Organization Name</label>
				<span class='help-block'>Name of the club/organization joined</span>
				<input type='text' class='form-control' value='$db_organization' id='organization' name='organization' placeholder='Organization'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='office_held'>Office Held</label>
				<span class='help-block'>Position held</span>
				<input type='text' class='form-control' value='$db_office_held' id='office_held' name='office_held' placeholder='Office Held'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='significant_contribution' class='col-form-label'>Significant Contribution</label>
				<span class='help-block'>Contributions made by the student</span>
				<input type='text' class='form-control' value='$db_significant_contribution' id='significant_contribution' name='significant_contribution' placeholder='Significant Contribution'/>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_clubs' id='edit_clubs' data-edit-type='$edit_type' data-edit-id='$edit_id'>Edit Club
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_clubs_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-clubs':
		
			echo "
			<div class='modal-content delete-clubs-modal' id='delete-clubs-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete Club</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_clubs_form' name='delete_clubs_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_clubs' id='delete_clubs' data-delete-type='$delete_type' data-delete-id='$delete_id'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_clubs_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	//************subject_result section**************//
	//////////////////////////////////////////////
		case 'add-subject-result':
		
			echo "
				<div class='modal-content add-subject-result-modal' id='add-subject-result-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Add Subject Result</h4>
				</div><!--modal header-->


			<form method='post' id='add_subject_result_form' name='add_subject_result_form'>

				<div class='modal-body'>         

			<div class='add-subject-result-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='subject'>Subject</label>
				<span class='help-block'>Select a subject</span>
				<select type='text' class='form-control' id='subject' name='subject'>
					<option value=''>-select subject-</option>";
				$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
				while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
					$subject_id_db = htmlentities($subject_sql_row['id']);
					$subject_db = nl2br($subject_sql_row['subject_name']);
					echo "<option value='$subject_id_db'>$subject_db</option>";
					}
					echo"
				</select>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='ca_1'>CA Score</label>
				<span class='help-block'>Continuous Assessment Score</span>
				<input type='text' class='form-control' id='ca_1' name='ca_1' placeholder='Continuous Assessment'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='exam' class='col-form-label'>Exam</label>
				<span class='help-block'>Exam Score</span>
				<input type='text' class='form-control' id='exam' name='exam' placeholder='Exam Score'/>
				</div><!--end col-->
			</div><!--end row-->
			
				</div><!--end modal-body-->
			
			<div class='modal-footer'>	
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='add_subject_result' id='add_subject_result'>Add Subject Result
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</div><!--end modal-footer-->
			
			</form><!--end add_subject_result_form-->
					
				</div><!--end modal-content-->
			";
			break;

		
		case 'edit-subject-result':
			$query = mysqli_query($conn,"SELECT * FROM subject_result WHERE id='$edit_id' AND removed='no'");	  
			$get_subject_result = mysqli_fetch_array($query);

			$db_subject_id = $get_subject_result['subject_id'];
			$db_ca_1 = $get_subject_result['ca_1'];
			$db_exam = $get_subject_result['exam'];
		
			echo "
				<div class='modal-content edit-subject-result-modal' id='edit-subject-result-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Edit Subject Result</h4>
				</div><!--modal header-->


			<form method='post' id='edit_subject_result_form' name='edit_subject_result_form'>

				<div class='modal-body'>         

			<div class='edit-subject-result-info'></div><!--where alerts are displayed-->            

			<div class='row'>
				<div class='form-group col-md-4'>                     
				<label for='subject'>Subject</label>
				<span class='help-block'>Select a subject</span>
				<select type='text' class='form-control' id='subject' name='subject'>
					<option value=''>-select subject-</option>";
				$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
				while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
					$subject_id_db = $subject_sql_row['id'];
					$subject_db = $subject_sql_row['subject_name'];
					echo "<option value='$subject_id_db' ";
						if($db_subject_id==$subject_id_db){echo 'selected='.'true';}
					echo">$subject_db</option>";
					}
				echo"
				</select>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='ca_1'>CA Score</label>
				<span class='help-block'>Continuous Assessment Score</span>
				<input type='text' class='form-control' id='ca_1' name='ca_1' value='$db_ca_1' placeholder='Continuous Assessment'/>
				</div><!--end col-->		   
				<div class='form-group col-md-4'>                     
				<label for='exam' class='col-form-label'>Exam</label>
				<span class='help-block'>Exam Score</span>
				<input type='text' class='form-control' id='exam' name='exam' value='$db_exam' placeholder='Exam Score'/>
				</div><!--end col-->
			</div><!--end row-->

				</div><!--end modal-body-->
			
			<div class='modal-footer'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
			<button type='submit' class='btn btn-outline-primary' name='edit_subject_result' id='edit_subject_result' data-edit-type='$edit_type' data-edit-id='$edit_id'>Edit Subject Result
			<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
			</div><!--end modal-footer-->
			
			</form><!--end edit_subject_result_form-->
					
				</div><!--end modal-content-->
			";
			break;

		case 'delete-subject-result':
		
			echo "
			<div class='modal-content delete-subject-result-modal' id='delete-subject-result-modal'>
					
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title'>Delete Subject Result</h4>
				</div><!--modal header-->

				<div class='modal-body'>
			<p>Are you sure you want to delete this entry?</p>         		  
				</div><!--end modal-body-->
			<div class='modal-footer'>
			<form method='post' id='delete_subject_result_form' name='delete_subject_result_form'>
				<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
				<button type='submit' class='btn btn-outline-danger' name='delete_subject_result' id='delete_subject_result' data-delete-type='$delete_type' data-delete-id='$delete_id'>Delete
				<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
			</form><!--end delete_subject_result_form-->
				</div> 
			</div><!--end modal-content-->
			";
			break;

	//************mock_exam_result section**************//
	//////////////////////////////////////////////
	case 'add-mock-exam-result':
		
	echo "
		<div class='modal-content add-mock-exam-result-modal' id='add-mock-exam-result-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Add Mock Exam Result</h4>
		</div><!--modal header-->


	<form method='post' id='add_mock_exam_result_form' name='add_mock_exam_result_form'>

		<div class='modal-body'>         

	<div class='add-mock-exam-result-info'></div><!--where alerts are displayed-->            

	<div class='row'>
		<div class='form-group col-md-6'>                     
		<label for='subject'>Subject</label>
		<span class='help-block'>Select a subject</span>
		<select type='text' class='form-control' id='subject' name='subject'>
			<option value=''>-select subject-</option>";
		$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
		while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
			$subject_id_db = htmlentities($subject_sql_row['id']);
			$subject_db = nl2br($subject_sql_row['subject_name']);
			echo "<option value='$subject_id_db'>$subject_db</option>";
			}
			echo"
		</select>
		</div><!--end col-->		   
		<div class='form-group col-md-6'>                     
			<label for='exam' class='col-form-label'>Exam</label>
			<span class='help-block'>Exam Score</span>
			<input type='text' class='form-control' id='exam' name='exam' placeholder='Exam Score'/>
		</div><!--end col-->
	</div><!--end row-->
	
		</div><!--end modal-body-->
	
	<div class='modal-footer'>	
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
	<button type='submit' class='btn btn-outline-primary' name='add_mock_exam_result' id='add_mock_exam_result'>Add Mock Exam Result
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</div><!--end modal-footer-->
	
	</form><!--end add_mock_exam_result_form-->
			
		</div><!--end modal-content-->
	";
	break;


case 'edit-mock-exam-result':
	$query = mysqli_query($conn,"SELECT * FROM subject_result WHERE id='$edit_id' AND removed='no'");	  
	$get_subject_result = mysqli_fetch_array($query);

	$db_subject_id = $get_subject_result['subject_id'];
	$db_ca_1 = $get_subject_result['ca_1'];
	$db_exam = $get_subject_result['exam'];

	echo "
		<div class='modal-content edit-mock-exam-result-modal' id='edit-mock-exam-result-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Edit Mock Exam Result</h4>
		</div><!--modal header-->


	<form method='post' id='edit_mock_exam_result_form' name='edit_mock_exam_result_form'>

		<div class='modal-body'>         

	<div class='edit-mock-exam-result-info'></div><!--where alerts are displayed-->            

	<div class='row'>
		<div class='form-group col-md-6'>                     
		<label for='subject'>Subject</label>
		<span class='help-block'>Select a subject</span>
		<select type='text' class='form-control' id='subject' name='subject'>
			<option value=''>-select subject-</option>";
		$subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
		while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
			$subject_id_db = $subject_sql_row['id'];
			$subject_db = $subject_sql_row['subject_name'];
			echo "<option value='$subject_id_db' ";
				if($db_subject_id==$subject_id_db){echo 'selected='.'true';}
			echo">$subject_db</option>";
			}
		echo"
		</select>
		</div><!--end col-->		   
		<div class='form-group col-md-6'>                     
		<label for='exam' class='col-form-label'>Exam</label>
		<span class='help-block'>Exam Score</span>
		<input type='text' class='form-control' id='exam' name='exam' value='$db_exam' placeholder='Exam Score'/>
		</div><!--end col-->
	</div><!--end row-->

		</div><!--end modal-body-->
	
	<div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
	<button type='submit' class='btn btn-outline-primary' name='edit_mock_exam_result' id='edit_mock_exam_result' data-edit-type='$edit_type' data-edit-id='$edit_id'>Edit Mock Exam Result
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
	</div><!--end modal-footer-->
	
	</form><!--end edit_mock_exam_result_form-->
			
		</div><!--end modal-content-->
	";
	break;

case 'delete-mock-exam-result':

	echo "
	<div class='modal-content delete-mock-exam-result-modal' id='delete-mock-exam-result-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Delete Mock Exam Result</h4>
		</div><!--modal header-->

		<div class='modal-body'>
	<p>Are you sure you want to delete this entry?</p>         		  
		</div><!--end modal-body-->
	<div class='modal-footer'>
	<form method='post' id='delete_mock_exam_result_form' name='delete_mock_exam_result_form'>
		<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
		<button type='submit' class='btn btn-outline-danger' name='delete_mock_exam_result' id='delete_mock_exam_result' data-delete-type='$delete_type' data-delete-id='$delete_id'>Delete
		<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</form><!--end delete_mock_exam_result_form-->
		</div> 
	</div><!--end modal-content-->
	";
	break;

	//************school fees section**************//
	//////////////////////////////////////////////
	case 'add-school-fees':
		
	echo "
	<div class='modal-content add-school-fees-modal' id='add-school-fees-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Add School Fees</h4>
		</div><!--modal header-->


		<form method='post' id='add_school_fees_form' name='add_school_fees_form'>

		<div class='modal-body'>         

		  <div class='add-school-fees-info'></div><!--where alerts are displayed-->            

		  <div class='row'>
			<div class='form-group col-md-4'>                     
			<label for='session'>Session</label>
			<span class='help-block'>Select a session</span>
			<select type='text' class='form-control' id='session' name='session'>
				<option value=''>-select session-</option>";
				$session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no'");
				while($session_sql_row = mysqli_fetch_assoc($session_sql)){
					$alt_session_db = htmlentities($session_sql_row['alt_session']);
					$session_db = htmlentities($session_sql_row['session']);
					echo "<option value='$alt_session_db'>$session_db</option>";
					}	   
			   echo"
			</select>
			</div><!--end col-->		

			<div class='form-group col-md-4'>                     
			<label for='term'>Term</label>
			<span class='help-block'>Select a term</span>
			<select type='text' class='form-control' id='term' name='term'>
				<option value=''>-select term-</option>";
				$terms_sql = mysqli_query($conn,"SELECT * FROM terms WHERE removed='no'  LIMIT 3");
				while($terms_sql_row = mysqli_fetch_assoc($terms_sql)){
					$term_db = htmlentities($terms_sql_row['term']);
					$term_name_db = htmlentities($terms_sql_row['term_name']);
					echo "<option value='$term_db'>$term_name_db</option>";
					}	   
			   echo"
			</select>
			</div><!--end col-->

			<div class='form-group col-md-4'>                     
			<label for='status'>Status</label>
			<span class='help-block'>Select status</span>
			<select type='text' class='form-control' id='status' name='status'>
			  <option value=''>-select status-</option>
			  <option value='Full Payment'>Full Payment</option>
			  <option value='Part Payment'>Part Payment</option>
			</select>
			</div><!--end col-->
			
		  </div><!--end row-->
		
		</div><!--end modal-body-->
		
		<div class='modal-footer'>	
		  <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
		  <button type='submit' class='btn btn-outline-primary' name='add_school_fees' id='add_school_fees'>Add School Fees
		  <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
		</div><!--end modal-footer-->
		
		</form><!--end add_school_fees_form-->
			
		</div><!--end modal-content-->
	";
	break;


case 'edit-school-fees':
    //check for school fees
	$query = mysqli_query($conn,"SELECT * FROM school_fees WHERE id='$edit_id' AND removed='no'");	  
	$get_school_fees = mysqli_fetch_array($query);

	$id = htmlentities($get_school_fees['id']);
	$student_reg_no = htmlentities($get_school_fees['student_reg_no']);
	$session = htmlentities($get_school_fees['session']);
	$term = htmlentities($get_school_fees['term']);
	$status = htmlentities($get_school_fees['status']);

	echo "
		<div class='modal-content edit-school-fees-modal' id='edit-school-fees-modal'>
			
		<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		  <h4 class='modal-title'>Edit School Fees</h4>
		</div><!--modal header-->


	<form method='post' id='edit_school_fees_form' name='edit_school_fees_form'>

		<div class='modal-body'>         

	<div class='edit-school-fees-info'></div><!--where alerts are displayed-->            

	<div class='row'>
		<div class='form-group col-md-4'>                     
		<label for='session'>Session</label>
		<span class='help-block'>Select a session</span>
		<select type='text' class='form-control' id='session' name='session'>
			<option value=''>-select session-</option>";
			$session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' ");
			while($session_sql_row = mysqli_fetch_assoc($session_sql)){
				$alt_session_db = htmlentities($session_sql_row['alt_session']);
				$session_db = htmlentities($session_sql_row['session']);
				echo "<option value='$alt_session_db'";				 
					if($session==$alt_session_db){echo 'selected='.'true';}				 
					echo">$session_db</option>";
				}
		echo"
		</select>
		</div><!--end col-->		
 
		<div class='form-group col-md-4'>                     
		<label for='term'>Term</label>
		<span class='help-block'>Select a term</span>
		<select type='text' class='form-control' id='term' name='term'>
			<option value=''>-select term-</option>";
			$terms_sql = mysqli_query($conn,"SELECT * FROM terms WHERE removed='no'  LIMIT 3");
			while($terms_sql_row = mysqli_fetch_assoc($terms_sql)){
				$term_db = htmlentities($terms_sql_row['term']);
				$term_name_db = htmlentities($terms_sql_row['term_name']);
				echo "<option value='$term_db' ";				 
					if($term==$term_db){echo 'selected='.'true';}				 
					echo">$term_name_db</option>";
				}	   
		   echo"
		</select>
		</div><!--end col-->

		<div class='form-group col-md-4'>                     
		<label for='status'>Status</label>
		<span class='help-block'>Select status</span>
		<select type='text' class='form-control' id='status' name='status'>
		  <option value=''>-select status-</option>
		  <option value='Full Payment' "; if($status=='Full Payment'){echo 'selected='.'true';}echo">Full Payment</option>
		  <option value='Part Payment' "; if($status=='Part Payment'){echo 'selected='.'true';}echo">Part Payment</option>
		</select>
		</div><!--end col-->
		
	</div><!--end row-->

		</div><!--end modal-body-->
	
	<div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>	   
	<button type='submit' class='btn btn-outline-primary' name='edit_school_fees' id='edit_school_fees' data-edit-type='$edit_type' data-edit-id='$edit_id'>Edit School Fees
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>
	</div><!--end modal-footer-->
	
	</form><!--end edit_school_fees_form-->
			
		</div><!--end modal-content-->
	";
	break;

case 'delete-school-fees':

	echo "
	<div class='modal-content delete-school-fees-modal' id='delete-school-fees-modal'>
			
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-title'>Delete School Fees</h4>
		</div><!--modal header-->

		<div class='modal-body'>
	<p>Are you sure you want to delete this entry?</p>         		  
		</div><!--end modal-body-->
	<div class='modal-footer'>
	<form method='post' id='delete_school_fees_form' name='delete_school_fees_form'>
		<button type='submit' class='btn btn-default' data-dismiss='modal' aria-label='Close'>Cancel</button>         
		<button type='submit' class='btn btn-outline-danger' name='delete_school_fees' id='delete_school_fees' data-delete-type='$delete_type' data-delete-id='$delete_id'>Delete
		<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
	</form><!--end delete_school_fees_form-->
		</div> 
	</div><!--end modal-content-->
	";
	break;


	}// end switch statement
	//security measure
}//end security measure

?>