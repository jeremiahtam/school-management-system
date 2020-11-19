<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");
include("../classes/develop-php-library.php");

$form_serial_no = htmlentities($_POST['serial_no']);
//check for card with serial number
$query = "SELECT * FROM cards WHERE serial_number='$form_serial_no'";	
$result = mysqli_query($conn,$query);
$num_rows = mysqli_num_rows($result);

//if the serial number exists(used or unused)
if($num_rows==1){

echo"	
  <table class='table table-hover'>
	<tr>
	  <th class=''>Serial No.</td>
	  <th class=''>Card Pin</td>
	  <th class=''>Used</td>
	  <th class=''>Reg. Number</td>
	  <th class=''>Name</td>
	  <th class=''>Times Used</td>
	  <th class=''>Datea</td>
	  <th class=''>Time</td>
	</tr>
";

while($row = mysqli_fetch_assoc($result)){
  
  $id = htmlentities($row['id']);
  $serial_number= htmlentities($row['serial_number']);
  $card_no = htmlentities($row['card_no']);
  $used = htmlentities($row['used']);
  $date = htmlentities($row['date']);
  $time = htmlentities($row['time']);
  

  echo"
	<tr>
	  <td class=''>$serial_number</td>
	  <td class=''>$card_no</td>
	  <td class=''>";
	  if($used=='yes'){ echo "Yes";}
	  elseif($used=='no'){ echo "No";}
	  
	  //check how many times it has been used(if it has been used)
	  $used_cards_query = mysqli_query($conn,"SELECT * FROM used_cards WHERE card_no='$card_no'");	
	  $used_cards_num_rows = mysqli_num_rows($used_cards_query);

	  
		while($used_cards_data = mysqli_fetch_assoc($used_cards_query)){
			$db_student_id = $used_cards_data['student_id'];
			//get student reg number
			$studentDetails = new studentDetails();
			$reg_no = $studentDetails->get_student_reg_no($conn,$db_student_id);
			

			//get student details
			$studentDetails = new studentDetails();
			list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

		}


	  echo"
	  </td>	  
	  <td class=''>"; if(isset($reg_no)){echo $reg_no;} echo"</td>
	  <td class=''>"; if(isset($studentFullname)){echo $studentFullname;} echo"</td>
	  <td class=''>$used_cards_num_rows</td>
	  <td class=''>$date</td>
	  <td class=''>$time</td>
	</tr>
  ";
}//end while loop
echo"
  </table>";
	


  }else if ($num_rows==0){
    echo"<div class='bg-danger text-danger'>That serial number does not exist!</div>";
  
  }
?>