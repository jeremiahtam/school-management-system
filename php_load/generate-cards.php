<?php 
//security measure
//only load this file if a the necessary variables are set
//if(isset($_POST['activation_code'])){

	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
	include("../classes/develop-php-library.php");

	$form_activation_code = htmlentities($_POST['activation_code']);
	$date= date('Y-m-d');
	$time= date('H:i:s');

	//check if any empty fields
	if($form_activation_code == ''){		

	}else{
		
	//check if card activation code exists
	$check_activation_code_query = mysqli_query($conn,"SELECT * FROM card_generator WHERE pin='$form_activation_code' AND used='no'");	
	$check_activation_code_rows=mysqli_num_rows($check_activation_code_query);
	
	if($check_activation_code_rows==0){
			echo"dne";
			
			
	}else if($check_activation_code_rows==1){
		
		$activation_code_array=mysqli_fetch_assoc($check_activation_code_query);
		$number_of_cards = $activation_code_array['number_of_cards'];
		while ($number_of_cards > 0){
		$small_letters ='abcdefghijklmnopqrstuvwxyz';
		$capital_letters ='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = '0123456789';
		$pin_permitted_chars = $numbers.''.$small_letters.''.time();
		$card_pin = substr(str_shuffle($pin_permitted_chars), 0, 12);
		
		$serial_permitted_chars = $numbers.''.time();
		$serial_num = substr(str_shuffle($serial_permitted_chars), 0, 16);
		
		//check if the generated card pin already exists
		$check_card_pin_query = mysqli_query($conn,"SELECT * FROM cards WHERE card_no='$card_pin' ");
		$check_card_pin_rows=mysqli_num_rows($check_card_pin_query);

		//check if the generated serial-number already exists
		$check_card_serial_query = mysqli_query($conn,"SELECT * FROM cards WHERE serial_number='$serial_num' ");	
		$check_card_serial_rows = mysqli_num_rows($check_card_serial_query);

		if($check_card_pin_rows==0 && $check_card_serial_rows==0){
			$insert_card = mysqli_query($conn,"INSERT INTO cards VALUES (default,'$card_pin','$serial_num','$form_activation_code','no','$date','$time')");
			$number_of_cards= $number_of_cards - 1;
			$update_card_generator = mysqli_query($conn,"UPDATE card_generator SET number_of_cards='$number_of_cards'");
			}else{
				$number_of_cards = $number_of_cards;
			}
		}//end while loop
		//set the card generator pin to used
		$update_card_generator_status = mysqli_query($conn,"UPDATE card_generator SET used='yes'");


		$record_per_page = 24;
		//$page = '';
		//$output = '';
		if(isset($_POST["page"]))
		{
			$page = $_POST['page'];
			}
			else
			{
				$page = 1;
				}
				display_cards($conn,$page);
			} //end check if teacher's username already exists
	}//end check if form is empty
//security measure
//}//end security measure
?>