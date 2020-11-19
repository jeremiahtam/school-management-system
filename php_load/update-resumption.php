<?php 
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['selected_session'])&&isset($_POST['selected_term'])&&isset($_POST['next_session'])&&isset($_POST['next_term'])&&isset($_POST['result_collection_date'])&&isset($_POST['resumption_date'])){

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	$selected_session = htmlentities($_POST['selected_session']);
	$selected_term = htmlentities($_POST['selected_term']);
	$form_next_session = htmlentities($_POST['next_session']);
	$form_next_term = htmlentities($_POST['next_term']);
	$form_result_collection_date = htmlentities($_POST['result_collection_date']);
	$form_resumption_date = htmlentities($_POST['resumption_date']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if that resumption entery is already in the database table
	$check_resumption = mysqli_query($conn,"SELECT * FROM resumption WHERE current_session='$selected_session' AND current_term='$selected_term' ");
	$check_resumption_num_rows = mysqli_num_rows($check_resumption);
	//if there is no record
	if($check_resumption_num_rows==0){
		if($form_result_collection_date==''||$form_resumption_date==''){		
		    echo"<div class='text-danger bg-danger'>Make sure the fields are not empty!</div>";		  
		  }else{
		    $insert_sql = mysqli_query($conn,"INSERT INTO resumption VALUES (default,'$selected_session','$selected_term','$form_next_session','$form_next_term','$form_resumption_date','$form_result_collection_date','$date','$time')"); 
		    echo"<div class='text-success bg-success'>Successfully Inserted!</div>";		  			
	  }
	}else if($check_resumption_num_rows==1){
		if($form_result_collection_date==''||$form_resumption_date==''){		
		    echo"<div class='text-danger bg-danger'>Make sure the fields are not empty!</div>";		  
		  }else{
		    $update_sql = mysqli_query($conn,"UPDATE resumption SET current_session='$selected_session',current_term='$selected_term',next_session='$form_next_session',next_term='$form_next_term',resumption_date='$form_resumption_date',result_collection_date='$form_result_collection_date',date='$date',time='$time' WHERE id='1' "); 
		    echo"<div class='text-success bg-success'>Successfully updated!</div>";		  			
	  }
		
	}
//security measure
}//end security measure

?>