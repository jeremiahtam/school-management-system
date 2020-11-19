<?php
// function for base url
function base_url(){
	if(isset($_SERVER['HTTPS'])){
		$protocal = ($_SERVER['HTTPS'] && $_SERVER['HTTPS']!='off') ? "https":"http";
	}else{
		$protocal = 'http';
    }
    if($_SERVER['SERVER_NAME']=='localhost'){
        return $protocal."://".$_SERVER['SERVER_NAME'].'/vlgnpcportal/';

    }else{
        return $protocal."://".$_SERVER['SERVER_NAME']."/";

    }
}
//***********get arm and class of a particular teacher*******//
class classTeacherDetails{

	function get_arm($conn,$loggedInUser){
    //get class teacher id
    $teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedInUser' AND removed='no'");
    $teacher_row = mysqli_fetch_assoc($teacher_sql);
    $teacherId = htmlentities($teacher_row['id']);

    //get the class this class teacher takes so we can use in determining the subject teacher for that class 
    $class_teacher_sql = mysqli_query($conn,"SELECT * FROM class_teachers WHERE teacher_id='$teacherId' AND removed='no'");
    $class_teacher_row = mysqli_fetch_assoc($class_teacher_sql);
    $class_id = htmlentities($class_teacher_row['class_id']);

    //get the class name and arem from the class id 
    $class_name_sql = mysqli_query($conn,"SELECT * FROM classes WHERE id='$teacherId' AND removed='no'");
    $class_name_row = mysqli_fetch_assoc($class_name_sql);
	$class_name = htmlentities($class_name_row['class']);
    $class_arm = htmlentities($class_name_row['arm']);
	return array($class_name,$class_arm);

	}
}
//***********subject result*******//
class subjectResult{
    function subjects_by_class($conn,$student_class,$form_session){
        //subject-result query for senior secondary
        if($student_class=='SS1' || $student_class=='SS2' || $student_class=='SS3'){
            $subjects_query = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no' AND (offered_by='JS,SS' OR offered_by='SS' OR offered_by='General') AND (session_intro<=$form_session) AND (session_removed='active' OR session_removed>=$form_session) ORDER BY id ASC");
            //set grades query for SS
            $grades_query = mysqli_query($conn,"SELECT * FROM grades WHERE class_group='SS' AND removed='no'");
            }
        //subject-result query for junior secondary
        if($student_class=='JS1' || $student_class=='JS2' || $student_class=='JS3'){
                $subjects_query = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no' AND (offered_by='PRY,JS' OR offered_by='JS' OR offered_by='JS,SS' OR offered_by='General') AND (session_intro<=$form_session) AND (session_removed='active' OR session_removed>=$form_session) ORDER BY id ASC");
            //set grades query for JS
            $grades_query = mysqli_query($conn,"SELECT * FROM grades WHERE class_group='JS' AND removed='no'");
                }
        //subject-result query for primary secondary
        if($student_class=='PRY1' || $student_class=='PRY2' || $student_class=='PRY3' || $student_class=='PRY4' || $student_class=='PRY5'){
            $subjects_query = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no' AND (offered_by='PRY' OR offered_by='PRY,JS' OR offered_by='General') AND (session_intro<=$form_session) AND (session_removed='active' OR session_removed>=$form_session) ORDER BY id ASC");
            //set grades query for PRY
            $grades_query = mysqli_query($conn,"SELECT * FROM grades WHERE class_group='PRY' AND removed='no'");
            }
            return array($subjects_query,$grades_query);
        }
}

//***********get details of students*******//
class studentDetails{

	function student_info($conn,$reg_no){
            
        $sql = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$reg_no' AND removed='no'");
        while($row = mysqli_fetch_assoc($sql)){
            $student_id = htmlentities($row['id']);
            $fullname = htmlentities($row['fullname']);
            $guardian_name = htmlentities($row['guardian_name']);
            $gender = htmlentities($row['gender']);
            $date_of_birth = htmlentities($row['date_of_birth']);
            $reg_no = htmlentities($row['reg_no']);
            $password = htmlentities($row['password']);
            $status = htmlentities($row['status']);
            $removed = htmlentities($row['removed']);
            return array($student_id,$fullname,$guardian_name,$gender,$date_of_birth,$reg_no,$password,$status,$removed);    
        }
    }
    //get students reg number from their id

    function get_student_reg_no($conn,$id){
        $sql = mysqli_query($conn,"SELECT * FROM students WHERE id='$id' AND removed='no'");
        while($row = mysqli_fetch_assoc($sql)){
            $reg_no = htmlentities($row['reg_no']);
            return $reg_no;    
        }
    }
}

//***********get the total percentage score for studnet*******//
class percentTotal{

	function result_info($conn,$session,$term,$studentId,$teacher){
		$date= date('Y-m-d');
		$time= date('H:i:s');	  
		//initialize total_average value as zero
		$total_average = 0;
		$total_out_of = 0;
		//check those of the student that has a score inputed in the subject_result
		$get_scores = mysqli_query($conn,"SELECT * FROM subject_result WHERE student_id='$studentId' AND session='$session' AND term='$term' AND removed='no'");
		while($get_scores_row = mysqli_fetch_assoc($get_scores)){
			$total_score = htmlentities($get_scores_row['total']);
			//if the result exists
			if(isset($total_score)){
				//if result exists and empty, make it equal to zero(0)
				if($total_score==''){
					$total_score=0;
					}
					$total_average = $total_average + $total_score;
					$total_out_of = $total_out_of + 100;
				}//end if isset($total_score)
			}//end while loop for available total scores
		if($total_out_of>0){
            $percentage = number_format((float)(($total_average*100)/$total_out_of),2,'.','');
			//$percentage = round(($total_average*100)/$total_out_of);
		}else{
			$percentage = 0;
		}
		//check if the percent_total already exists for the student with that session and term
		$check_stmt = mysqli_prepare($conn,"SELECT * FROM percent_total WHERE session=? AND  term=? AND student_id=?");
		mysqli_stmt_bind_param($check_stmt,"sss",$session,$term,$studentId);
		mysqli_stmt_execute($check_stmt);
		$check_result = mysqli_stmt_get_result($check_stmt);
		$check_result_rows = mysqli_num_rows($check_result);

		//get class arma and class based on the logged in class teacher
		$class_arm_and_class = new classTeacherDetails();
		list($class_name,$class_arm) = $class_arm_and_class->get_arm($conn,$teacher);
		
		//check if the student already has his percent score recorded
		if($check_result_rows==1){

			//if the percent_total already exists, update the percent_total
			$stmt = mysqli_prepare($conn,"UPDATE percent_total SET total=? WHERE student_id='$studentId' AND session='$session' AND term='$term' AND class_name='$class_name' AND class_arm='$class_arm' ");
			mysqli_stmt_bind_param($stmt,"s",$percentage);
			mysqli_stmt_execute($stmt);

		}else{

			//if the percent_total does not exist, insert into percent_total
			$id = "";
			$stmt = mysqli_prepare($conn,"INSERT INTO percent_total VALUES (?,?,?,?,?,?,?,?,?,?)");
			mysqli_stmt_bind_param($stmt,"ssssssssss",$id,$session,$term,$studentId,$teacher,$class_name,$class_arm,$percentage,$date,$time);
			mysqli_stmt_execute($stmt);
		}
	}
}

// CLASS FOR CONVERTING TIME TO AGO
class convertToAgo {

    function convert_datetime($str) {
	
   		list($date, $time) = explode(' ', $str);
    	list($year, $month, $day) = explode('-', $date);
    	list($hour, $minute, $second) = explode(':', $time);
    	$timestamp = strtotime($str);
    	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    	return $timestamp;
    }
	
    function makeAgo($timestamp){
	
   		$difference = time() - $timestamp;
   		$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
   		$lengths = array("60","60","24","7","4.35","12","10");
   		
		for($j = 0; $difference >= $lengths[$j]; $j++)
   			$difference /= $lengths[$j];
   			$difference = round($difference);
   		if($difference != 1) 
			$periods[$j].= "s";
   			$text = "$difference $periods[$j] ago";
   			return $text;
    }
	
} // END CLASS


//***********load content functions*******//
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
function display_classes($page,$startFrom,$recordPerPage,$conn){
	$removed = 'no';	
	$query = "SELECT * FROM classes WHERE removed=? ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
    $stmt = mysqli_prepare($conn,$query);
    mysqli_stmt_bind_param($stmt,"s",$removed);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
	

	echo"	
  <table class='table table-hover table-sm table-bordered'>
    <thead class='thead-light'>
      <tr>
        <th class=''>No.</td>
        <th class=''>Class</td>
        <th class=''>Arm</td>
        <th class=''>Edit</td>
        <th class=''>Delete</td>
      </tr>
    </thread>
	";
	
	$num_rows = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result)){
	  //$startFrom;
	  
	  $class_id = htmlentities($row['id']);
	  $class_name = htmlentities($row['class']);
	  $arm_name = htmlentities($row['arm']);
	  $admin_username = htmlentities($row['admin_username']);
	  $date = htmlentities($row['date']);
	  $time = htmlentities($row['time']);
	  
	  echo"
		<tr id='$class_id'>
		  <td class=''>";
		  while($startFrom < $recordPerPage * $page){
			  $startFrom = $startFrom + 1;
			  echo $startFrom;
			  break;
			  }
			  
			  echo"</td>
		  <td class=''>$class_name</td>
		  <td class=''>$arm_name</td>
		  <td class=''><a class='edit-btn modal-action' data-edit-id='$class_id' data-edit-type='edit-class' id='edit-class' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
		  <td class=''><a class='delete-btn modal-action' data-delete-id='$class_id' data-delete-type='delete-class' id='delete-class' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
		</tr>
	  ";
	}//end while loop
	echo"
	  </table>";
	
	 /////////////////////////////////////////	
	  $page_query = "SELECT * FROM classes WHERE removed='no' ORDER BY id ASC";
	  $page_result = mysqli_query($conn,$page_query);
	  $total_records = mysqli_num_rows($page_result);
	  $total_pages = ceil($total_records/$recordPerPage);
	  
	if($total_pages>1){
		echo"
		<nav aria-label='Page navigation' class='class-navigation'>
		 <ul class='pagination justify-content-end'>
		";
		
		if($page>1){
			echo"
			<li class='page-item'>
			 <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
			  <span aria-hidden='true'>&larr; Previous</span>
			 </a>
			</li>
			";
			}
			
    echo "
    <li class='page-item disabled'>
       <a class='page-link' tabindex='-1' aria-disabled='true'>
       Page ".$page." of ".$total_pages."
       </a>
    </li>";
		
		if($page!=$total_pages){
			echo"
			<li class='page-item'>
			 <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
			  <span aria-hidden='true'>Next &rarr;</span>
			 </a>
			</li>
			";
			}
		echo"
		 </ul>
		</nav>
	";	
	}

}
//display all teachers function//
/////////////////////////////////
function display_all_teachers($page,$startFrom,$recordPerPage,$conn){
// echo $page;	
$query = "SELECT * FROM all_teachers WHERE removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
$result = mysqli_query($conn,$query);


echo"	
<table class='table table-hover table-sm table-bordered'>
  <thead class='thead-light'>
    <tr>
      <th class=''>No.</td>
      <th class=''>Fullname</td>
      <th class=''>Username</td>
      <th class=''>Password</td>
      <th class=''>Email</td>
      <th class=''>Edit</td>
      <th class=''>Delete</td>
    </tr>
  </thread>
";

$num_rows = mysqli_num_rows($result);
while($row = mysqli_fetch_assoc($result)){
//$startFrom;

    $all_teachers_id = htmlentities($row['id']);
    $fullname = htmlentities($row['fullname']);
    $username = htmlentities($row['username']);
    $password = htmlentities($row['password']);
    $email = htmlentities($row['email']);	  
    $date = htmlentities($row['date']);
    $time = htmlentities($row['time']);
    
    echo"
        <tr>
        <td class=''>";
        while($startFrom <$recordPerPage * $page){
            $startFrom = $startFrom + 1;
            echo $startFrom;
            break;
            }
            
            echo"</td>
        <td class=''>$fullname</td>
        <td class=''>$username</td>
        <td class=''>$password</td>
        <td class=''>$email</td>
        <td class=''><a class='edit-btn modal-action' data-edit-id='$all_teachers_id' data-edit-type='edit-all-teachers' id='edit-all-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
        <td class=''><a class='delete-btn modal-action' data-delete-id='$all_teachers_id' data-delete-type='delete-all-teachers' id='delete-all-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
        </tr>
            ";
    }
    echo"
        </table>";
    
    /////////////////////////////////////////	
    $page_query = "SELECT * FROM all_teachers WHERE removed='no' ORDER BY id ASC";
    $page_result = mysqli_query($conn,$page_query);
    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records/$recordPerPage);
    
    if($total_pages>1){
        echo"
        <nav aria-label='Page navigation' class='all-teachers-navigation'>
        <ul class='pagination justify-content-end'>
        ";
        
        if($page>1){
            echo"
            <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
            <span aria-hidden='true'>&larr; Previous</span>
            </a>
            </li>
            ";
            }
            
        echo "
        <li class='page-item disabled'>
          <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
            Page ".$page." of ".$total_pages."
          </a>
        </li>";
        
        if($page!=$total_pages){
            echo"
            <li class='page-item'>
              <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
              <span aria-hidden='true'>Next &rarr;</span>
            </a>
            </li>
            ";
            }
        echo"
        </ul>
        </nav>
        ";	
        }
    
}

function display_search_all_teachers($page,$startFrom,$recordPerPage,$form_search_all_teachers_keyword,$conn){
	// echo $page;	
    $query = "SELECT * FROM all_teachers WHERE ((`fullname` LIKE '%".$form_search_all_teachers_keyword."%') OR (`username` LIKE '%".$form_search_all_teachers_keyword."%')) AND removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
$result = mysqli_query($conn,$query);


echo"	
<table class='table table-hover table-sm table-bordered'>
  <thead class='thead-light'>
    <tr>
      <th class=''>No.</td>
      <th class=''>Fullname</td>
      <th class=''>Username</td>
      <th class=''>Password</td>
      <th class=''>Email</td>
      <th class=''>Edit</td>
      <th class=''>Delete</td>
    </tr>
  </thread>
";

$num_rows = mysqli_num_rows($result);
while($row = mysqli_fetch_assoc($result)){
//$startFrom;

    $all_teachers_id = htmlentities($row['id']);
    $fullname = htmlentities($row['fullname']);
    $username = htmlentities($row['username']);
    $password = htmlentities($row['password']);
    $email = htmlentities($row['email']);	  
    $date = htmlentities($row['date']);
    $time = htmlentities($row['time']);
    
    echo"
        <tr>
        <td class=''>";
        while($startFrom <$recordPerPage * $page){
            $startFrom = $startFrom + 1;
            echo $startFrom;
            break;
            }
            
            echo"</td>
        <td class=''>$fullname</td>
        <td class=''>$username</td>
        <td class=''>$password</td>
        <td class=''>$email</td>
        <td class=''><a class='edit-btn modal-action' data-edit-id='$all_teachers_id' data-edit-type='edit-search-all-teachers' id='edit-search-all-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
        <td class=''><a class='delete-btn modal-action' data-delete-id='$all_teachers_id' data-delete-type='delete-search-all-teachers' id='delete-search-all-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
        </tr>
            ";
    }
    echo"
        </table>";
    
    /////////////////////////////////////////	
    $page_query = "SELECT * FROM all_teachers WHERE ((`fullname` LIKE '%".$form_search_all_teachers_keyword."%') OR (`username` LIKE '%".$form_search_all_teachers_keyword."%')) AND removed='no' ORDER BY id ASC";
    $page_result = mysqli_query($conn,$page_query);
    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records/$recordPerPage);
    
    if($total_pages>1){
        echo"
        <nav aria-label='Page navigation' class='search-all-teachers-navigation'>
        <ul class='pagination justify-content-end'>
        ";
        
        if($page>1){
            echo"
            <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
            <span aria-hidden='true'>&larr; Previous</span>
            </a>
            </li>
            ";
            }
            
            echo "
            <li class='page-item disabled'>
              <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
                Page ".$page." of ".$total_pages."
              </a>
            </li>";
            
        if($page!=$total_pages){
            echo"
            <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
            <span aria-hidden='true'>Next &rarr;</span>
            </a>
            </li>
            ";
            }
        echo"
        </ul>
        </nav>
        ";	
        }
}

//display subject teachers function//
/////////////////////////////////
function display_subject_teachers($page,$startFrom,$recordPerPage,$conn){
    // echo $page;	
    $query = "SELECT * FROM subject_teachers WHERE removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
    $result = mysqli_query($conn,$query);


    echo"	
    <table class='table table-hover table-sm table-bordered'>
      <thead class='thead-light'>
        <tr>
          <th class=''>No.</td>
          <th class=''>Fullname</td>
          <th class=''>Username</td>
          <th class=''>Class</td>
          <th class=''>Subject</td>
          <th class=''>Edit</td>
          <th class=''>Delete</td>
        </tr>
      </thread>
    ";

    $num_rows = mysqli_num_rows($result);
    while($row = mysqli_fetch_assoc($result)){

        $subject_teachers_id = htmlentities($row['id']);
        $teacherId = htmlentities($row['teacher_id']);
        $teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$teacherId' AND removed='no'");
        while($teacher_sql_row = mysqli_fetch_assoc($teacher_sql)){
            $fullname = htmlentities($teacher_sql_row['fullname']);
            $username = htmlentities($teacher_sql_row['username']);
            }

        $class_id = htmlentities($row['class_id']);
        $subjectId = htmlentities($row['subject_id']);
        
        $class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE id='$class_id' AND removed='no'");
        while($class_sql_row = mysqli_fetch_assoc($class_sql)){
            $class_db = htmlentities($class_sql_row['class']);
            $arm_db = htmlentities($class_sql_row['arm']);
            }
        $subject_sql = mysqli_query($conn,"SELECT * FROM subjects WHERE id='$subjectId' AND removed='no'");
        while($subject_sql_row = mysqli_fetch_assoc($subject_sql)){
            $subject_db = nl2br($subject_sql_row['subject_name']);
        }
                    
        $date = htmlentities($row['date']);
        $time = htmlentities($row['time']);
        
        echo"
            <tr>
            <td class=''>";
            while($startFrom <$recordPerPage * $page){
                $startFrom = $startFrom + 1;
                echo $startFrom;
                break;
                }
                
                echo"</td>
            <td class=''>$fullname</td>
            <td class=''>$username</td>
            <td class=''>$class_db $arm_db</td>
            <td class=''>$subject_db</td>
            <td class=''><a class='edit-btn modal-action' data-edit-id='$subject_teachers_id' data-edit-type='edit-subject-teachers' id='edit-subject-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
            <td class=''><a class='delete-btn modal-action' data-delete-id='$subject_teachers_id' data-delete-type='delete-subject-teachers' id='delete-subject-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
            </tr>
                ";
        }
        echo"
            </table>";
        
        /////////////////////////////////////////	
        $page_query = "SELECT * FROM subject_teachers WHERE removed='no' ORDER BY id ASC";
        $page_result = mysqli_query($conn,$page_query);
        $total_records = mysqli_num_rows($page_result);
        $total_pages = ceil($total_records/$recordPerPage);
        
        if($total_pages>1){
            echo"
            <nav aria-label='Page navigation' class='subject-teachers-navigation'>
            <ul class='pagination justify-content-end'>
            ";
            
            if($page>1){
                echo"
                <li class='page-item'>
                <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
                <span aria-hidden='true'>&larr; Previous</span>
                </a>
                </li>
                ";
                }
                
            echo "
            <li class='page-item disabled'>
              <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
                Page ".$page." of ".$total_pages."
              </a>
            </li>";
                    
            if($page!=$total_pages){
                echo"
                <li class='page-item'>
                <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
                <span aria-hidden='true'>Next &rarr;</span>
                </a>
                </li>
                ";
                }
            echo"
            </ul>
            </nav>
            ";
        }
}


//display class teachers function//
/////////////////////////////////
function display_class_teachers($page,$startFrom,$recordPerPage,$conn){
    $query = "SELECT * FROM class_teachers WHERE removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
    $result = mysqli_query($conn,$query);


    echo"	
    <table class='table table-hover table-sm table-bordered'>
      <thead class='thead-light'>
        <tr>
          <th class=''>No.</td>
          <th class=''>Fullname</td>
          <th class=''>Username</td>
          <th class=''>Class</td>
          <th class=''>Edit</td>
          <th class=''>Delete</td>
        </tr>
      </thread>
";

    $num_rows = mysqli_num_rows($result);
    while($row = mysqli_fetch_assoc($result)){
    //$startFrom;
    
        $classTeachersId = htmlentities($row['id']);
        $teacherId = htmlentities($row['teacher_id']);

        $teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE id='$teacherId' AND removed='no'");
        while($teacher_sql_row = mysqli_fetch_assoc($teacher_sql)){
            $fullname = htmlentities($teacher_sql_row['fullname']);
            $username = htmlentities($teacher_sql_row['username']);
            }

        $class_id = htmlentities($row['class_id']);
        
        $class_sql = mysqli_query($conn,"SELECT * FROM classes WHERE id='$class_id' AND removed='no'");
        while($class_sql_row = mysqli_fetch_assoc($class_sql)){
            $class_db = htmlentities($class_sql_row['class']);
            $arm_db = htmlentities($class_sql_row['arm']);
            }
                    
        $date = htmlentities($row['date']);
        $time = htmlentities($row['time']);
        
        echo"
            <tr>
            <td class=''>";
            while($startFrom <$recordPerPage * $page){
                $startFrom = $startFrom + 1;
                echo $startFrom;
                break;
                }
                
                echo"</td>
            <td class=''>$fullname</td>
            <td class=''>$username</td>
            <td class=''>$class_db $arm_db</td>
            <td class=''><a class='edit-btn modal-action' data-edit-id='$classTeachersId' data-edit-type='edit-class-teachers' id='edit-class-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
            <td class=''><a class='delete-btn modal-action' data-delete-id='$classTeachersId' data-delete-type='delete-class-teachers' id='delete-class-teachers' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
            </tr>
                ";
        }
        echo"
            </table>";
        
        /////////////////////////////////////////	
        $page_query = "SELECT * FROM class_teachers WHERE removed='no' ORDER BY id ASC";
        $page_result = mysqli_query($conn,$page_query);
        $total_records = mysqli_num_rows($page_result);
        $total_pages = ceil($total_records/$recordPerPage);
        
        if($total_pages>1){
            echo"
            <nav aria-label='Page navigation' class='class-teachers-navigation'>
            <ul class='pagination justify-content-end'>
            ";
            
            if($page>1){
                echo"
                <li class='page-item'>
                <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
                <span aria-hidden='true'>&larr; Previous</span>
                </a>
                </li>
                ";
                }
                
            echo "
            <li class='page-item disabled'>
              <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
                Page ".$page." of ".$total_pages."
              </a>
            </li>";
                    
            if($page!=$total_pages){
                echo"
                <li class='page-item'>
                <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
                <span aria-hidden='true'>Next &rarr;</span>
                </a>
                </li>
                ";
                }
            echo"
            </ul>
            </nav>
            ";	
                }	

}


//display grades function//
/////////////////////////////////
function display_grades($page,$startFrom,$recordPerPage,$conn){
   // echo $page;	
   $query = "SELECT * FROM grades WHERE removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
   $result = mysqli_query($conn,$query);
   

   echo"	
      <table class='table table-hover table-sm table-bordered'>
        <thead class='thead-light'>
          <tr>
            <th class=''>No.</td>
            <th class=''>Maximum</td>
            <th class=''>Minimum</td>
            <th class=''>Grade Letter</td>
            <th class=''>Remark</td>
            <th class=''>Class Group</td>
            <th class=''>Edit</td>
            <th class=''>Delete</td>
          </tr>
        </thread>
   ";
   
   $num_rows = mysqli_num_rows($result);
   while($row = mysqli_fetch_assoc($result)){
     //$startFrom;
     
       $grades_id = htmlentities($row['id']);
       $minimum = htmlentities($row['minimum']);
       $maximum = htmlentities($row['maximum']);
       $grade_letter = htmlentities($row['grade_letter']);
       $remark = htmlentities($row['remark']);
       $class_group = htmlentities($row['class_group']);
       $removed = htmlentities($row['removed']);
       $date = htmlentities($row['date']);
       $time = htmlentities($row['time']);
   
     echo"
       <tr>
         <td class=''>";
         while($startFrom <$recordPerPage * $page){
             $startFrom = $startFrom + 1;
             echo $startFrom;
             break;
             }
             
             echo"</td>
         <td class=''>$maximum</td>
         <td class=''>$minimum</td>
         <td class=''>$grade_letter</td>
         <td class=''>$remark</td>
         <td class=''>$class_group</td>
         <td class=''><a class='edit-btn modal-action' data-edit-id='$grades_id' data-edit-type='edit-grades' id='edit-grades' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
         <td class=''><a class='delete-btn modal-action' data-delete-id='$grades_id' data-delete-type='delete-grades' id='delete-grades' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
       </tr>
     ";
   }//end while loop
   echo"
     </table>";
   
    /////////////////////////////////////////	
     $page_query = "SELECT * FROM grades WHERE removed='no' ORDER BY id ASC";
     $page_result = mysqli_query($conn,$page_query);
     $total_records = mysqli_num_rows($page_result);
     $total_pages = ceil($total_records/$recordPerPage);
     
   if($total_pages>1){
       echo"
       <nav aria-label='Page navigation' class='grades-navigation'>
        <ul class='pagination justify-content-end'>
       ";
       
       if($page>1){
           echo"
           <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
             <span aria-hidden='true'>&larr; Previous</span>
            </a>
           </li>
           ";
           }
           
       echo "<li class='page-item disabled'>
              <a class='page-link' href='#' tabindex='-1' aria-disabled='true'>
              Page ".$page." of ".$total_pages."
              </a>
            </li>";
       
       if($page!=$total_pages){
           echo"
           <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
             <span aria-hidden='true'>Next &rarr;</span>
            </a>
           </li>
           ";
           }
       echo"
        </ul>
       </nav>
   ";	
   }

}

function display_subjects($page,$startFrom,$recordPerPage,$conn){
    // echo $page;	
    $query = "SELECT * FROM subjects WHERE removed='no' ORDER BY id DESC LIMIT $startFrom,$recordPerPage";	
    $result = mysqli_query($conn,$query);

    echo"	
  <table class='table table-hover table-sm table-bordered'>
    <thead class='thead-light'>
      <tr>
        <th class=''>No.</td>
        <th class=''>Subject Name</td>
        <th class=''>Offered By</td>
        <th class=''>Session Introduced</td>
        <th class=''>Session Removed</td>
        <th class=''>Edit</td>
        <th class=''>Delete</td>
      </tr>
    </thread>
    ";
$num_rows = mysqli_num_rows($result);
    
    while($row = mysqli_fetch_assoc($result)){
    $subjectId = htmlentities($row['id']);
    $subjectName = nl2br($row['subject_name']);
    $offeredBy = htmlentities($row['offered_by']);
    $sessionIntro = htmlentities($row['session_intro']);
    $sessionRemoved = htmlentities($row['session_removed']);
    $removed = htmlentities($row['removed']);
    $date = htmlentities($row['date']);
    $time = htmlentities($row['time']);
    //get proper session detail for sessionIntro
    $session_query = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' AND alt_session='$sessionIntro' ");
    while($session_row = mysqli_fetch_assoc($session_query)){
    $sessionIntro = $session_row['session'];
    }
    //get proper session detail for session_removed
    $session_query = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' AND alt_session='$sessionRemoved' ");
    while($session_row = mysqli_fetch_assoc($session_query)){
    $sessionRemoved = $session_row['session'];
    }

    echo"
        <tr id='$subjectId'>
        <td class=''>";
    while($startFrom <$recordPerPage * $page){
            $startFrom = $startFrom + 1;
            echo $startFrom;
            break;
            }
            
            echo"</td>
        <td class=''>$subjectName</td>
        <td class=''>$offeredBy</td>
        <td class=''>$sessionIntro</td>
        <td class=''>$sessionRemoved</td>
        <td class=''><a class='edit-btn modal-action' data-edit-id='$subjectId' data-edit-type='edit-subject' id='edit-subject' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
        <td class=''><a class='delete-btn modal-action' data-delete-id='$subjectId' data-delete-type='delete-subject' id='delete-subject' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
        </tr>
    ";
    }
    echo"
    </table>";
    
    /////////////////////////////////////////	
    $page_query = "SELECT * FROM subjects WHERE removed='no' ORDER BY id DESC";
    $page_result = mysqli_query($conn,$page_query);
    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records/$recordPerPage);
    
    if($total_pages>1){
        echo"
        <nav aria-label='Page navigation' class='subjects-navigation'>
        <ul class='pagination justify-content-end'>
        ";
        
        if($page>1){
            echo"
            <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
            <span aria-hidden='true'>&larr; Previous</span>
            </a>
            </li>
            ";
            }
            
        echo "
        <li class='page-item disabled'>
          <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
            Page ".$page." of ".$total_pages."
          </a>
        </li>";
            
        if($page!=$total_pages){
            echo"
            <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
            <span aria-hidden='true'>Next &rarr;</span>
            </a>
            </li>
            ";
            }
        echo"
        </ul>
        </nav>
    ";	
    }
}

function display_students($page,$startFrom,$recordPerPage,$conn){
   // echo $page;	
   $query = "SELECT * FROM students WHERE removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
   $result = mysqli_query($conn,$query);
   

   echo"	
   <table class='table table-hover table-sm table-bordered'>
    <thead class='thead-light'>
      <tr>
        <th class=''>No.</td>
        <th class=''>Fullname</td>
        <th class=''>Guardian's Name</td>
        <th class=''>Gender</td>
        <th class=''>Date of Birth</td>
        <th class=''>Reg. No.</td>
        <th class=''>Password</td>
        <th class=''>Status</td>
        <th class=''>Edit</td>
        <th class=''>Delete</td>
      </tr>
    </thread>
   ";
   
   $num_rows = mysqli_num_rows($result);
   while($row = mysqli_fetch_assoc($result)){
     //$startFrom;
     
     $students_id = htmlentities($row['id']);
     $guardian_name = $row['guardian_name'];
     $fullname = htmlentities($row['fullname']);
     $gender = htmlentities($row['gender']);
     $date_of_birth = htmlentities($row['date_of_birth']);
     $reg_no = htmlentities($row['reg_no']);
     $password = $row['password'];
     $status = htmlentities($row['status']);
     $date = htmlentities($row['date']);
     $time = htmlentities($row['time']);
     
     echo"
       <tr>
         <td class=''>";
         while($startFrom <$recordPerPage * $page){
             $startFrom = $startFrom + 1;
             echo $startFrom;
             break;
             }
             
             echo"</td>
         <td class=''>$fullname</td>
         <td class=''>$guardian_name</td>
         <td class=''>$gender</td>
         <td class=''>$date_of_birth</td>
         <td class=''>$reg_no</td>
         <td class=''>$password</td>
         <td class=''>$status</td>
         <td class=''><a class='edit-btn modal-action' data-edit-id='$students_id' data-edit-type='edit-students' id='edit-students' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
         <td class=''><a class='delete-btn modal-action' data-delete-id='$students_id' data-delete-type='delete-students' id='delete-students' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
       </tr>
     ";
   }//end while loop
   echo"
     </table>";
   
    /////////////////////////////////////////	
     $page_query = "SELECT * FROM students WHERE removed='no' ORDER BY id ASC";
     $page_result = mysqli_query($conn,$page_query);
     $total_records = mysqli_num_rows($page_result);
     $total_pages = ceil($total_records/$recordPerPage);
     
   if($total_pages>1){
       echo"
       <nav aria-label='Page navigation' class='students-navigation'>
        <ul class='pagination justify-content-end'>
       ";
       
       if($page>1){
           echo"
           <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
             <span aria-hidden='true'>&larr; Previous</span>
            </a>
           </li>
           ";
           }
           
           echo "
           <li class='page-item disabled'>
             <a class='page-link' tabindex='-1' href='#' aria-disabled='true'>
               Page ".$page." of ".$total_pages."
             </a>
           </li>";
          
       if($page!=$total_pages){
           echo"
           <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
             <span aria-hidden='true'>Next &rarr;</span>
            </a>
           </li>
           ";
           }
       echo"
        </ul>
       </nav>";	
   }
}

function display_search_students($page,$startFrom,$recordPerPage,$form_search_students_keyword,$conn){
	// echo $page;	
    $query = "SELECT * FROM students WHERE ((`fullname` LIKE '%".$form_search_students_keyword."%') OR (`reg_no` LIKE '%".$form_search_students_keyword."%')) AND removed='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
    $result = mysqli_query($conn,$query);
    //result numrows
    $num_rows = mysqli_num_rows($result);
    //if there are results, show them
    if($num_rows>0){	
    echo"	
    <table class='table table-hover table-sm table-bordered'>
      <thead class='thead-light'>
        <tr>
          <th class=''>No.</td>
          <th class=''>Fullname</td>
          <th class=''>Guardian's Name</td>
          <th class=''>Gender</td>
          <th class=''>Date of Birth</td>
          <th class=''>Reg. No.</td>
          <th class=''>Password</td>
          <th class=''>Edit</td>
          <th class=''>Delete</td>
        </tr>
      </thread>
    ";
    
    while($row = mysqli_fetch_assoc($result)){
    //$startFrom;
    
    $students_id = htmlentities($row['id']);
    $guardian_name = ($row['guardian_name']);
    $fullname = htmlentities($row['fullname']);
    $gender = htmlentities($row['gender']);
    $date_of_birth = htmlentities($row['date_of_birth']);
    $reg_no = htmlentities($row['reg_no']);
    $password = htmlentities($row['password']);
    $date = htmlentities($row['date']);
    $time = htmlentities($row['time']);
    
    echo"
        <tr>
        <td class=''>";
        while($startFrom <$recordPerPage * $page){
            $startFrom = $startFrom + 1;
            echo $startFrom;
            break;
            }
            
            echo"</td>
        <td class=''>$fullname</td>
        <td class=''>$guardian_name</td>
        <td class=''>$gender</td>
        <td class=''>$date_of_birth</td>
        <td class=''>$reg_no</td>
        <td class=''>$password</td>
        <td class=''><a class='edit-btn modal-action' data-edit-id='$students_id' data-edit-type='edit-search-students' id='edit-search-students' data-toggle='modal' data-target='#modal' data-page='$page'>Edit</a></td>
        <td class=''><a class='delete-btn modal-action' data-delete-id='$students_id' data-delete-type='delete-search-students' id='delete-search-students' data-toggle='modal' data-target='#modal' data-page='$page'>Delete</a></td>
        </tr>
    ";
    }//end while loop
    echo"
    </table>";
    
    /////////////////////////////////////////	
    $page_query = "SELECT * FROM students WHERE ((`fullname` LIKE '%".$form_search_students_keyword."%') OR (`reg_no` LIKE '%".$form_search_students_keyword."%')) AND removed='no' ORDER BY id ASC";
    $page_result = mysqli_query($conn,$page_query);
    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records/$recordPerPage);
    
    if($total_pages>1){
        echo"
        <nav aria-label='Page navigation' class='search-students-navigation'>
        <ul class='pagination justify-content-end'>
        ";
        
        if($page>1){
            echo"
            <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
            <span aria-hidden='true'>&larr; Previous</span>
            </a>
            </li>
            ";
            }
            
            echo "<li class='page-item disabled'>
            <a class='page-link' href='#' tabindex='-1' aria-disabled='true'>
            Page ".$page." of ".$total_pages."
            </a>
          </li>";
      
        if($page!=$total_pages){
            echo"
            <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
            <span aria-hidden='true'>Next &rarr;</span>
            </a>
            </li>
            ";
            }
        echo"
        </ul>
        </nav>
    ";	
    }
    //else if no results available show error message
  }else{
  echo"<div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No results found!</div>";
  }
}

function display_clubs($reg_no,$session,$term,$conn){
    //if $reg_no, $session or $term is empty
    if($reg_no==''||$session==''||$term==''){

    }else{
          
    //get student details
    $studentDetails = new studentDetails();
    list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

    //check for available subject results
      $check = mysqli_query($conn,"SELECT * FROM clubs WHERE student_id='$studentId' AND session='$session' AND term='$term' AND removed='no' ");
      $num_rows = mysqli_num_rows($check);
     
      if($num_rows<2){
      echo"
        <a class='btn btn-md btn-outline-info modal-action' id='add-clubs' data-toggle='modal' data-target='#modal'>Add Club</a>";
      }
      echo"
        <div class='table-responsive'>
        <table class='table table-hover table-sm table-bordered'>
          <thead class='thead-light'>
            <tr>
              <th class=''>No.</th>
              <th class=''>Organization</th>
              <th class=''>Office Held</th>
              <th class=''>Significant Contribution</th>
              <th class=''>Edit</th>
              <th class=''>Delete</th>
            </tr>
          </thread>";
            //start count
           $count = 0;
        while($row = mysqli_fetch_assoc($check)){
            $id = htmlentities($row['id']);
            $organization = htmlentities($row['organization']);
            $office_held = htmlentities($row['office_held']);
            $significant_contribution = htmlentities($row['significant_contribution']);
            echo"
            <tbody>
             <tr>
               <td class=''>";
          while($count < $num_rows){
              $count = $count + 1;
              echo $count;
              break;
              }		  
              echo"</td>
              <td class=''>$organization</td>
              <td class=''>$office_held</td>
              <td class=''>$significant_contribution</td>
              <td class=''><a class='edit-btn modal-action' data-edit-id='$id' data-edit-type='edit-clubs' id='edit-clubs' data-toggle='modal' data-target='#modal'>Edit</a></td>
              <td class=''><a class='delete-btn modal-action' data-delete-id='$id' data-delete-type='delete-clubs' id='delete-clubs' data-toggle='modal' data-target='#modal'>Delete</a></td>
            </tr>";
            }
            
            echo"
            </tbody>
        </table>
        </div><!--end table-responsive-->
    ";      
    }        
}

//display the results of students in the class teachers portal
function display_subject_result($studentId,$session,$term,$conn){
    $removed = 'no';
    echo"
	<div class='subject-result-list table-responsive'>
  <table class='table table-hover table-sm table-bordered'>
    <thead class='thead-light'>
      <tr>
        <th class=''>No.</th>
        <th class=''>Subject</th>
        <th class=''>CA Score</th>
        <th class=''>Exam Score</th>
        <th class=''>Total</th>
        <th class=''>Grade</th>
        <th class=''>Edit</th>
        <th class=''>Delete</th>
      </tr>
    </thread>";
    $check = mysqli_prepare($conn,"SELECT * FROM subject_result WHERE student_id=? AND session=? AND term=? AND removed=?");
    mysqli_stmt_bind_param($check,"ssss",$studentId,$session,$term,$removed);
    mysqli_stmt_execute($check);
    $check_result = mysqli_stmt_get_result($check);
    $num_rows = mysqli_num_rows($check_result);

    //start count
    $count = 0;
    while($row = mysqli_fetch_assoc($check_result)){
    $id = htmlentities($row['id']);
    $subjectId = htmlentities($row['subject_id']);
    $ca_1 = htmlentities($row['ca_1']);
    $exam = htmlentities($row['exam']);
    $total = htmlentities($row['total']);
    $grade = htmlentities($row['grade']);
    $removed = 'no';

    //set absent result for CA and Exam
    if($ca_1==''){
        $ca_1='-';
    }
    if($exam==''){
        $exam='-';
    }
    if($total==''){
        $total='-';
    }
    echo"
    <tbody>
      <tr>
        <td class=''>";
    while($count < $num_rows){
        $count = $count + 1;
        echo $count;
        break;
        }
    echo"</td>
        <td class=''>";
        //using prepared statements
        $subject_stmt = mysqli_prepare($conn,"SELECT * FROM subjects WHERE id=? AND removed=?");
        //bind the statement with the data type and variables
        mysqli_stmt_bind_param($subject_stmt, "ss", $subjectId, $removed);
        //execute the statement
        mysqli_stmt_execute($subject_stmt);
        //get the result of the query executed
        $subject_result = mysqli_stmt_get_result($subject_stmt);
        //fetch the content from the database table 
        $subject_row = mysqli_fetch_assoc($subject_result);
        $subjectName = htmlentities($subject_row['subject_name']); 
        //end the query prepared statement       
        mysqli_stmt_close($subject_stmt);

        echo"
        $subjectName
        </td>
        <td class=''>$ca_1</td>
        <td class=''>$exam</td>
        <td class=''>$total</td>
        <td class=''>$grade</td>
        <td class=''><a class='edit-btn modal-action' data-edit-id='$id' data-edit-type='";
        //create a condition such that the modal will be for regular term results
        //or for mock exam
        if($term>3){echo'edit-mock-exam-result';}else{echo'edit-subject-result';} echo"' id='"; if($term>3){echo"edit-mock-exam-result";}else{echo"edit-subject-result";} echo"' data-toggle='modal' data-target='#modal'>Edit</a></td>
        <td class=''><a class='delete-btn modal-action' data-delete-id='$id' data-delete-type='";
        if($term>3){echo'delete-mock-exam-result';}else{echo'delete-subject-result';} echo"' id='";if($term>3){echo'delete-mock-exam-result';}else{echo'delete-subject-result';} echo"' data-toggle='modal' data-target='#modal'>Delete</a></td>
    </tr>";
    }
    echo"
    </tbody>
    </table>";	

}

///////////display school fees history/////////////////

function display_school_fees($reg_no,$conn,$type){
    //if $reg_no, $session or $term is empty
    if($reg_no==''){

    }else{
        $studentDetails = new studentDetails();
        list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);
                
      //check for school fees history of the student with that registration number
      $check = mysqli_query($conn,"SELECT * FROM school_fees WHERE student_id='$studentId' AND removed='no' ");
      $num_rows = mysqli_num_rows($check);

                 
      if($type=='search'){    
        echo"
        <div id='$reg_no' class='school-fees-id'>$studentFullname</div>
        <div class='school-fees-action-bar'>
          <a class='btn btn-md btn-outline-info modal-action float-right' id='add-school-fees' data-toggle='modal' data-target='#modal'>
            Add School Fees
          </a>
        </div>
        <div class='table-responsive'>  ";
        }
        echo"
        <table class='table table-hover table-sm table-bordered'>
          <thead class='thead-light'>
            <tr>
              <th class=''>No.</th>
              <th class=''>Session</th>
              <th class=''>Term</th>
              <th class=''>Status</th>
              <th class=''>Edit</th>
              <th class=''>Delete</th>
            </tr>
            </thread>";
            //start count
           $count = 0;
        while($row = mysqli_fetch_assoc($check)){
            $id = htmlentities($row['id']);
            $student_id = htmlentities($row['student_id']);
            $session = htmlentities($row['session']);
            $term = htmlentities($row['term']);
            $status = htmlentities($row['status']);
            //get term
            $terms_sql = mysqli_query($conn,"SELECT * FROM terms WHERE term='$term' AND removed='no' ");
            $terms_sql_row = mysqli_fetch_assoc($terms_sql);
            $term_name = htmlentities($terms_sql_row['term_name']);
            //get session
            $sessions_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE alt_session='$session' AND removed='no' ");
            $sessions_sql_row = mysqli_fetch_assoc($sessions_sql);
            $session_name = htmlentities($sessions_sql_row['session']);

            echo"
            <tbody>
            <tr>
               <td class=''>";
          while($count < $num_rows){
              $count = $count + 1;
              echo $count;
              break;
              }		  
              echo"</td>
              <td class=''>$session_name</td>
              <td class=''>$term_name</td>
              <td class=''>$status</td>
              <td class=''><a class='edit-btn modal-action' data-edit-id='$id' data-edit-type='edit-school-fees' id='edit-school-fees' data-toggle='modal' data-target='#modal'>Edit</a></td>
              <td class=''><a class='delete-btn modal-action' data-delete-id='$id' data-delete-type='delete-school-fees' id='delete-school-fees' data-toggle='modal' data-target='#modal'>Delete</a></td>
            </tr>";
            }
            
            echo"
            </tbody>
        </table>";
        //display responsive table if it is the search result
        if($type=='search'){    
        echo"
        </div><!--end table-responsive-->";
        }      
    }        
}

///////////display school fees history/////////////////

function display_cards($conn,$page){

    $recordPerPage = 24;

    $startFrom = ($page - 1) * $recordPerPage;
    // echo $page;	
     $query = "SELECT * FROM cards WHERE used='no' ORDER BY id ASC LIMIT $startFrom,$recordPerPage";	
     $result = mysqli_query($conn,$query);
     
     $num_rows = mysqli_num_rows($result);
 
         if($num_rows>0){
         echo"
         <div class=''>
          <a id='print' class='btn btn-sm btn-outline-primary'>
           Print
          </a>
         </div>";
         }
         echo"<div id='print-area' class='cards-cover'>
               <table width='100%'>";
 
         //initialise count of displayed cards to zero
         $count = 0;
         
         while($row = mysqli_fetch_assoc($result)){
         //$startFrom;
         $card_id = htmlentities($row['id']);
         $card_no = htmlentities($row['card_no']);
         $serial_no = htmlentities($row['serial_number']);
         //get school details
         $school_query=mysqli_query($conn,"SELECT * FROM school_details WHERE id='1'");
         $school_row=mysqli_fetch_assoc($school_query);
         $school_name=htmlentities($school_row['name']);
         $school_email=htmlentities($school_row['email']);
         $school_address=htmlentities($school_row['address']);
         $school_phone_number=htmlentities($school_row['phone_number']);
         $school_website=htmlentities($school_row['website']);
         
         //if the count value is zero or even, display <tr>
         if($count == 0 || $count % 2 == 0){
             echo "<tr>";
             } 
         echo"
         <td width='50%' class='cards-body'>
           <table width='100%'>
             <tr>
               <td width='88%'>
                 <table width='100%'>
                   <tr><td class='school-name'>$school_name</td></tr>
                   <tr><td class='address'>$school_address</td></tr>
                   <tr>
                     <td>
                       <table width='100%'>
                         <tr><td class='website'>$school_website</td><td class='phone-number'>$school_phone_number</td></tr>
                       </table>
                     </td>
                   </tr>
                 </table>
               </td>
               <td width='18%'>
                 <img src='img/logo.png' width='100%'/>
               </td>
             </tr>
           </table>
           <table width='100%'>
             <tr><th>Instructions</th></tr>
             <tr>
               <td class='instructions'>
                 <div>Log on to "; echo $school_website; echo"/account</div>
                 <div>1. Login to your student account.</div>
                 <div>2. Enter card PIN and select the desired session and term.</div>
                 <div>3. The pin can be used only five(5) times per term.</div>
               </td>
             </tr>
           </table>
           <table width='100%'>
             <tr>
               <td class='card-pin'>Pin: $card_no</td>
               </tr>
             <tr>
               <td class='card-serial-number'>Serial No.: $serial_no</td>
             </tr>
           </table>
           <table width='100%'>
             <tr>
               <td>
                 <div class='produced-by'>An Oncliqsupport Production</div>
               </td>
             </tr>
           </table>
         </td>
         ";// end <td> echo
         //if the count value is odd, display </tr>
         if($count >1 && $count % 2 !== 0){
             echo "</tr>";
             } 
             //increase $count
             $count = $count + 1;
 
         }//end while loop
 
         //to cover the final <tr> in case the total number or cards is even
         if($count ==0 || $num_rows % 2 !== 0){
             echo "<td width='50%'></td></tr>";
             } 
 
         echo"</table> 
           </div><!--end cards-cover-->";
      /////////////////////////////////////////	
       $page_query = "SELECT * FROM cards WHERE used='no' ORDER BY id ASC";
       $page_result = mysqli_query($conn,$page_query);
       $total_records = mysqli_num_rows($page_result);
       $total_pages = ceil($total_records/$recordPerPage);
       
     if($total_pages>1){
         echo"
         <nav aria-label='Page navigation' class='cards-navigation'>
          <ul class='pagination justify-content-end'>
         ";
         
         if($page>1){
             echo"
             <li class='page-item'>
              <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
               <span aria-hidden='true'>&larr; Previous</span>
              </a>
             </li>
             ";
             }

          echo "<li class='page-item disabled'>
             <a class='page-link' href='#' tabindex='-1' aria-disabled='true'>
             Page ".$page." of ".$total_pages."
             </a>
           </li>";
        
         if($page!=$total_pages){
             echo"
             <li class='page-item'>
              <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
               <span aria-hidden='true'>Next &rarr;</span>
              </a>
             </li>
             ";
             }
         echo"
          </ul>
         </nav>
     ";	
     }
 
}//end display_cards()

///////////display results available for locking/////////////////

function display_lock_result($page,$startFrom,$recordPerPage,$conn){
   // echo $page;	
   $query = "SELECT * FROM lock_result ORDER BY session ASC LIMIT $startFrom,$recordPerPage";	
   $result = mysqli_query($conn,$query);
   

   echo"	
    <table class='table table-hover table-sm table-bordered'>
      <thead class='thead-light'>
        <tr>
          <th class=''>No.</td>
          <th class=''>Session</td>
          <th class=''>Term</td>
          <th class=''>Action</td>
        </tr>
      </thread>
   ";
   
   $num_rows = mysqli_num_rows($result);

   while($row = mysqli_fetch_assoc($result)){
     //$startFrom;
     
     $lock_result_id = htmlentities($row['id']);
     $session = $row['session'];
     //get session name
     $session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE alt_session='$session'");
     $session_sql_row = mysqli_fetch_assoc($session_sql);
     $session_db = htmlentities($session_sql_row['session']);
         
     $term = htmlentities($row['term']);
     //get exam name
     $term_sql = mysqli_query($conn,"SELECT * FROM terms WHERE term='$term'");
     $term_sql_row = mysqli_fetch_assoc($term_sql);
     $term_db = htmlentities($term_sql_row['term_name']);

     $locked = htmlentities($row['locked']);
     $date = htmlentities($row['date']);
     $time = htmlentities($row['time']);
     
     echo"
       <tr>
         <td class=''>";
         while($startFrom <$recordPerPage * $page){
             $startFrom = $startFrom + 1;
             echo $startFrom;
             break;
             }
             
             echo"</td>
         <td class=''>$session_db</td>
         <td class=''>$term_db</td>
         <td class=''>
            <div class='lock_action_holder' id='lock_action_holder_$lock_result_id'>";
            if($locked=='no'){
                echo"<button class='btn btn-sm btn-outline-success' id='$lock_result_id' data-lock-type='lock'>
                <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/> Lock</button>";
            }else{
                echo"<button class='btn btn-sm btn-danger' id='$lock_result_id' data-lock-type='unlock'>
                <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/> Unlock</button>";
            }
         echo"
            </div>
         </td>
       </tr>
     ";
   }//end while loop
   echo"
     </table>";
   
    /////////////////////////////////////////	
     $page_query = "SELECT * FROM lock_result ORDER BY session ASC";
     $page_result = mysqli_query($conn,$page_query);
     $total_records = mysqli_num_rows($page_result);
     $total_pages = ceil($total_records/$recordPerPage);
     
   if($total_pages>1){
       echo"
       <nav aria-label='Page navigation' class='lock-result-navigation'>
        <ul class='pagination justify-content-end'>
       ";
       
       if($page>1){
           echo"
           <li class='page-item'>
            <a aria-label='Previous' class='pagination_link page-link' id='".($page - 1)."'>
             <span aria-hidden='true'>&larr; Previous</span>
            </a>
           </li>
           ";
           }
           
       echo "<li class='page-item disabled'>
           <a class='page-link' href='#' tabindex='-1' aria-disabled='true'>
           Page ".$page." of ".$total_pages."
           </a>
         </li>";
    
       if($page!=$total_pages){
           echo"
           <li class='page-item'>
            <a aria-label='Next' class='pagination_link page-link' id='".($page + 1)."'>
             <span aria-hidden='true'>Next &rarr;</span>
            </a>
           </li>
           ";
           }
       echo"
        </ul>
       </nav>";	
     }
 
}//end 

?>