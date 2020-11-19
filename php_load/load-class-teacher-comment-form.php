<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");
include("../classes/develop-php-library.php");

$reg_no = htmlentities($_POST['reg_no']);
$session = htmlentities($_POST['session']);
$term = htmlentities($_POST['term']);

//if $reg_no, $session or $term is empty
if($reg_no==''||$session==''||$term==''){
  }else{
	//get student details
	$studentDetails = new studentDetails();
	list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

	//else if neither $reg_no, $session or $term is empty
  //check if this data already exixts
  $check = mysqli_query($conn,"SELECT * FROM class_teacher_comment WHERE student_id='$studentId' AND session='$session' AND term='$term' LIMIT 1");
  $num_rows = mysqli_num_rows($check);
  //if it does not exist, show add form
	if($num_rows==1){
		//if the data does not already exixts
		while($row = mysqli_fetch_assoc($check)){
		$id = htmlentities($row['id']);
		$comment = htmlentities($row['comment']);
		}
	}
		echo"
	<form method='post' id='add_class_teacher_comment_form' name='add_class_teacher_comment_form'>
	<div class='class-teacher-comment-info'></div><!--where alerts are displayed-->            
	<div class='row'>
		<div class='form-group col-md-12'>                     
			<label for='class_teacher_comment' class='col-form-label'>Class teacher's comment</label>
			<textarea class='form-control' rows='1' id='class_teacher_comment' name='class_teacher_comment' placeholder='Class teacher comment'>"; if(isset($comment)){echo $comment;} echo"</textarea>
		</div><!--end col-->
	</div><!--end row-->
	<button class='btn btn-outline-primary pull-right' type='submit' id='add_class_teacher_comment' name='add_class_teacher_comment'>Add class teacher's comment
	<img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
	</button>
	</form>
</form>
  ";
  
}
?>