<?php
//security measure
//only load this file if a the necessary variables are set
if(isset($_POST['login'])){

	session_start(); 
	include("../inc/db.inc.php");
	$userType=strip_tags($_POST['userType']);
	$username=strip_tags($_POST['username']);
	$password=strip_tags($_POST['password']);
	$login=strip_tags($_POST['login']);
	$date= date('Y-m-d');
	$time= date('h:i A');
	
	if(isset($login)){
	
	switch($userType){
		case 'admin':
		$password = preg_replace('#[^A-Za-z0-9]#i',"",$_POST["password"]);//filter everything but letters and numbers
		$password_md5 = md5($password);
		//check if the administrator logs in
		$sql= mysqli_query($conn,"SELECT * FROM admin WHERE username='$username' AND password='$password_md5' AND removed='no' LIMIT 1"); //query
		//Check for their existence
		$userCount = mysqli_num_rows($sql);//count the number of rows returned
		if ($userCount===1){
			$row = mysqli_fetch_assoc($sql);
			$loggedin_user = $row["username"];
			$loggedin_user_type = $userType;
			//echo $user;
			$_SESSION["loggedin_user"]= $loggedin_user;
			$_SESSION['loggedin_user_type'] = $loggedin_user_type;
			exit();
			}else{
			echo "<p class='text-danger'>Your username or password is incorrect! Try again!</p>";
			
				}
		break;
		
		case 'class-teacher':

		//check if the class teacher exists in
		$sql= mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$username' AND password='$password' AND removed='no' LIMIT 1"); //query
		//Check for their existence
		$userCount = mysqli_num_rows($sql);//count the number of rows returned
		if ($userCount===1){
			$row = mysqli_fetch_assoc($sql);

			$teacher_id = $row["id"];
			$loggedin_user = $row["username"];
			$loggedin_user_type = $userType;
			
			//check if this teacher is a class teacher
			$class_teacher_sql= mysqli_query($conn,"SELECT * FROM class_teachers WHERE teacher_id='$teacher_id' AND removed='no' LIMIT 1"); //query
			$classTeacherCount = mysqli_num_rows($class_teacher_sql);//count the number of rows returned
			if($classTeacherCount===1){
				//echo $user;
				$_SESSION["loggedin_user"]= $loggedin_user;
				$_SESSION['loggedin_user_type'] = $loggedin_user_type;			  
				exit();
				}else{
			echo "<p class='text-danger'>You are not a class teacher!</p>";		
					}
			exit();
			}else{
			echo "<p class='text-danger'>Your username or password is incorrect! Try again!</p>";		
				}
		break;

		case 'student':
		//check if the administrator logs in
		$sql= mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$username' AND password='$password' AND removed='no' LIMIT 1"); //query
		//Check for their existence
		$userCount = mysqli_num_rows($sql);//count the number of rows returned
		if ($userCount===1){
			$row = mysqli_fetch_assoc($sql);
			$loggedin_user = $row["reg_no"];
			$loggedin_user_type = $userType;
			//echo $user;
			$_SESSION["loggedin_user"]= $loggedin_user;
			$_SESSION['loggedin_user_type'] = $loggedin_user_type;
			exit();
			}else{
			echo "<p class='text-danger'>Your admission number or password is incorrect! Try again!</p>";		
				}
		break;

		}
	}// end if login
//security measure
}//end security measure

?>