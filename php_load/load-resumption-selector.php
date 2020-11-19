<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");


  echo"
 <form method='post' id='resumption_selector_form' name='resumption_selector_form'>
   <div class='resumption-selector-info'></div><!--where alerts are displayed-->            
   
   <div class='row'>
	 <div class='form-group col-md-4'>                     
	   <label for='session_selector'>Select Session</label>
	   <span class='help-block'>Select the session</span>
	   <select class='form-control' id='session_selector' name='session_selector'>
		   <option value=''>-select session-</option>//";
		     $session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' ");
		     while($session_sql_row = mysqli_fetch_assoc($session_sql)){
			  	 $alt_session_db = htmlentities($session_sql_row['alt_session']);
			   	 $session_db = htmlentities($session_sql_row['session']);
			 		 echo "<option value='$alt_session_db'>$session_db</option>";
			 			}	   
		 	   echo"
     </select>
	 </div><!--end col--> 
	 <div class='form-group col-md-4'>                     
	   <label for='term_selector'>Select Term</label>
	   <span class='help-block'>Select the term?</span>
	   <select class='form-control' id='term_selector' name='term_selector'>
			 <option value=''>-select term-</option>
			 <option value='1'>First Term</option>
			 <option value='2'>Second Term</option>
			 <option value='3'>Third Term</option>
	   </select>
	 </div><!--end col-->	
	 <button type='submit' class='btn btn-outline-primary' name='load_resumption_form' id='load_resumption_form'>Load Resumption Form
     <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/></button>         	   
	 </div><!--end row-->
 </form><!--end update_settings_form-->
 <div class='resumption-form-content'></div>
";
//end while loop
?>