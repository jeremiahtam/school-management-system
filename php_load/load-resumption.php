<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");

if(isset($_POST['session_selector']) && isset($_POST['term_selector'])){

	$selected_session = $_POST['session_selector'];
	$selected_term = $_POST['term_selector'];

	$query = "SELECT * FROM resumption WHERE current_session='$selected_session' AND current_term='$selected_term'";	
	$result = mysqli_query($conn,$query);

	$num_rows = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result)){
		$id = htmlentities($row['id']);
		$current_session = htmlentities($row['current_session']);
		$current_term = htmlentities($row['current_term']);
		$next_session = htmlentities($row['next_session']);
		$next_term = htmlentities($row['next_term']);
		$result_collection_date = htmlentities($row['result_collection_date']);
		$resumption_date = htmlentities($row['resumption_date']);
		$date = htmlentities($row['date']);
		$time = htmlentities($row['time']);
	
	}

  

  echo"
 <form method='post' id='update_resumption_form' name='update_resumption_form'>
   <div class='update-resumption-info'></div><!--where alerts are displayed-->               
   <div class='row'>
	
 <div class='form-group col-md-6'>                     
	   <label for='selected_session'>Selected Session</label>";
		 $session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE alt_session='$selected_session' AND removed='no' ");
		 while($session_sql_row = mysqli_fetch_assoc($session_sql)){
			 $alt_session_db = htmlentities($session_sql_row['alt_session']);
			 $session_db = htmlentities($session_sql_row['session']);
			 echo "<div data-selected-session='$selected_session' id='selected_session_info'>$session_db</div>";
			 }	   
		 echo"
       </select>
	 </div><!--end col--> 
	 <div class='form-group col-md-6'>                     
	   <label for='selected_term'>Selected Term</label>";
		 $term_sql = mysqli_query($conn,"SELECT * FROM terms WHERE term='$selected_term' AND removed='no' ");
		 while($term_sql_row = mysqli_fetch_assoc($term_sql)){
			 $term_name_db = htmlentities($term_sql_row['term_name']);
			 $term_db = htmlentities($term_sql_row['term']);
			 echo "<div data-selected-term='$selected_term' id='selected_term_info'>$term_name_db</div>";
			 }	   
	 echo"
		</div><!--end col-->		   
   </div><!--end row-->
   <div class='row'>
	 <div class='form-group col-md-6'>                     
	   <label for='next_session'>Next Session</label>
	   <span class='help-block'>What is the next session</span>
	   <select class='form-control' id='next_session' name='next_session'>
		<option value=''>-select session-</option>";
		 $session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' ");
		 while($session_sql_row = mysqli_fetch_assoc($session_sql)){
			 $alt_session_db = htmlentities($session_sql_row['alt_session']);
			 $session_db = htmlentities($session_sql_row['session']);
			 echo "<option value='$alt_session_db'";
			 //check if	$next_session is set
			 if(isset($next_session)){
				 if($next_session==$alt_session_db){echo 'selected='.'true';}				 
			 }			 
			  echo">$session_db</option>";
			 }	   
		 echo"	  
       </select>
	 </div><!--end col--> 
	 <div class='form-group col-md-6'>                     
	   <label for='next_term'>Next Term</label>
	   <span class='help-block'>What is the next term?</span>
	   <select class='form-control' id='next_term' name='next_term'>
		 <option value=''>-select term-</option>
		 <option value='1' ";if(isset($next_term)){
				if($next_term=='1'){echo 'selected='.'true';}
			  }echo">First Term</option>
		 <option value='2' "; if(isset($next_term)){
		    if($next_term=='2'){echo 'selected='.'true';}
			  }echo">Second Term</option>
		 <option value='3' "; if(isset($next_term)){
				if($next_term=='3'){echo 'selected='.'true';}
			  }echo">Third Term</option>
	   </select>
	 </div><!--end col-->		   
   </div><!--end row-->
   <div class='row'>
	 <div class='form-group col-md-6'>                     
	   <label for='resumption_date'>Resumption Date</label>
	   <span class='help-block'>Date in the format yyyy-mm-dd</span>
		 <input type='text' class='form-control' id='resumption_date' name='resumption_date' placeholder='Resumption Date' 
			 value='";if(isset($resumption_date)){echo $resumption_date;}
			 echo"
			 '>
	 </div><!--end col-->		   
	 <div class='form-group col-md-6'>                     
	   <label for='result_collection_date'>Result Collection Date</label>
	   <span class='help-block'>Is the last term result available now?</span>
	   <select class='form-control' id='result_collection_date' name='result_collection_date'>
		 <option value='yes' "; if(isset($result_collection_date)){
			   if($result_collection_date=='yes'){echo 'selected';}
			} echo">Yes</option>
		 <option value='no' ";  if(isset($result_collection_date)){
			   if($result_collection_date=='no'){echo 'selected';}
			} echo">No</option>
	   </select>
	 </div><!--end col-->		   
 </div><!--end row-->
   <button type='submit' class='btn btn-outline-primary' name='update_resumption' id='update_resumption'>Update Resumption
   <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         
 </form><!--end update_settings_form-->

  ";	
}else{
	echo"<div class='text-danger'>Sorry! An error occured!</div>";
}
?>