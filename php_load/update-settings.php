<?php 
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['address'])&&isset($_POST['phone_number'])&&isset($_POST['website'])){
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");

	$form_name = htmlentities($_POST['name']);
	$form_email = htmlentities($_POST['email']);
	$form_address = htmlentities($_POST['address']);
	$form_phone_number = htmlentities($_POST['phone_number']);
	$form_website = htmlentities($_POST['website']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	if($form_name==''||$form_email==''||$form_address==''||$form_phone_number==''|| $form_website==''){		
	echo"<div class='text-danger bg-danger'>Make sure the fields are not empty!</div>";		  

	}else{

	$update_sql = mysqli_query($conn,"UPDATE school_details SET name='$form_name',email='$form_email',address='$form_address',phone_number='$form_phone_number',website='$form_website',date='$date',time='$time' WHERE id='1' ");
			
	echo"<div class='text-success bg-success'>Successfully updated!</div>";		  

		}

//security measure
}//end security measure
?>