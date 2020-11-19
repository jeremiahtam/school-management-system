<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");

$reg_no = htmlentities($_POST['reg_no']);
$session = htmlentities($_POST['session']);
$term = htmlentities($_POST['term']);

//if $reg_no, $session or $term is empty
  echo"
  <form method='post' id='find_result_form' name='find_result_form'>
  <div class='find-result-info'></div><!--where alerts are displayed-->            
  <div class='row'>
	<div class='form-group col-sm-3'>                     
	  <label for='reg_no'>Registration Number</label>
	  <input class='form-control' id='reg_no' name='reg_no' placeholder='Registration Number' value='$reg_no'/>
	</div><!--end col-->		   
	<div class='form-group col-sm-3'>                     
	  <label for='session' class='col-form-label'>Session</label>
	  <select class='form-control' id='session' name='session'>
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
	<div class='form-group col-sm-3'>                     
	  <label for='term' class='col-form-label'>Term</label>
	  <select class='form-control' id='term' name='term'>
		<option value=''>-select term-</option>";
		$term_sql = mysqli_query($conn,"SELECT * FROM terms WHERE removed='no' ");
		while($term_sql_row = mysqli_fetch_assoc($term_sql)){
			$term_db = htmlentities($term_sql_row['term']);
			$term_name_db = htmlentities($term_sql_row['term_name']);
			echo "<option value='$term_db'";				 
				if($term==$term_db){echo 'selected='.'true';}				 
			 echo">$term_name_db</option>";
			}	   
		echo"	  
	  </select>
	</div><!--end col-->
	<div class='form-group col-sm-3'>
	  <button class='btn btn-outline-primary form-control' type='submit' id='find_result' name='find_result'>Find Result
	  <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
	  </button>
	</div><!--end col-->
  </div><!--end row-->
  </form>
  ";
	

?>