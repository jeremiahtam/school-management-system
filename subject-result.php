<?php 
include("./inc/session.inc.php"); 
include("./inc/db.inc.php");
include("./classes/develop-php-library.php"); 

//redirect if user is not logged in
if(!isset($loggedin_user)){
	header("Location:". base_url()."login-class-teacher");
}
//redirect if loggend in user is not admin
if($loggedin_user_type!=='class-teacher'){
 header("Location:". base_url()."login-class-teacher");
}
#get student reg_no
if (isset($_GET['reg_no'])){
	$reg_no=mysqli_real_escape_string($conn,$_GET['reg_no']);
  }else{
	  $reg_no='';
	  }
#get student session
if (isset($_GET['session'])){
	$session=mysqli_real_escape_string($conn,$_GET['session']);
  }else{
	  $session='';
	  }
#get student term	  
if (isset($_GET['term'])){
	$term = mysqli_real_escape_string($conn,$_GET['term']);
  }else{
	  $term='';
	  }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="IE=9">
<base href="<?php echo base_url();?>" />

<title>Subject Result | School</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/ionicons.min.css" rel="stylesheet">
<link href="fonts/flaticon1/flaticon.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href='css/datepicker.css' rel='stylesheet'>
</head>

<body>
  <div class='custom-container'>
    <?php include("./inc/menu.inc.php");?>
      
    <div class='modal fade' id='modal' tabindex='-1' role='dialog'>
       <div class='modal-dialog modal-lg' role='document'> 
        
      </div><!--modal-dialog-->
    </div><!--end modal-->
    

	<section class='category-body'>
	  <div class='container-fluid'>
		<div class='row'>          
          <div class='col-md-3'>              
          <?php include("./inc/side-menu.inc.php");?>          
          </div><!--end col-md-3-->          
          <div class='col-md-9'>   
                     
            <div class='category-content'>
              <div class='category-content-heading'>
							<?php 
							$class_info = new classTeacherDetails();
							list($class_name,$arm_name) = $class_info->get_arm($conn,$loggedin_user);
							echo $class_name." ".$arm_name;
							?>
							Assessment Sheet</div> 
							<div class='alert alert-danger alert-dismissable'>
							<?php 
							//check if the surrent session and term is correct 
								$query = mysqli_query($conn,"SELECT * FROM resumption LIMIT 1");	
								$row = mysqli_fetch_assoc($query);
									$current_session = htmlentities($row['current_session']);
									$current_term = htmlentities($row['current_term']);
									//get term name that is similar to the current term from database
									$term_query = mysqli_query($conn,"SELECT * FROM terms WHERE term='$current_term' AND removed='no' LIMIT 1");
									while($term_row = mysqli_fetch_assoc($term_query)){
										$term_name = htmlentities($term_row['term_name']);
									}
									//get session name that is similar to the current term from database
									$session_query = mysqli_query($conn,"SELECT * FROM sessions WHERE alt_session='$current_session' AND removed='no' LIMIT 1");
									while($session_row = mysqli_fetch_assoc($session_query)){
										$session_name = htmlentities($session_row['session']);
									}
									echo"Current Session: <strong>$session_name</strong> Current Term: <strong>$term_name</strong>. If the session and term are not correct, contact your admin to make changes!";
									?>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							</div> 
              <div class='data-input-action find-result-form-container'>
                <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
              </div><!-- endinput-action -->
                
			<?php if($reg_no!==''||$session!==''||$term!==''){
            //check if reg_no exists
            //check if the result $reg_no exists
            $check_student_query = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$reg_no' AND removed='no'");	
            $student_num_rows=mysqli_num_rows($check_student_query);
            if($student_num_rows==1){
								
							//check if the result session exists
                $check_session_query = mysqli_query($conn,"SELECT * FROM sessions WHERE alt_session='$session'");	
                $session_num_rows=mysqli_num_rows($check_session_query);
                if($session_num_rows==1){
									
									//check if the result term exists
                  $check_term_query = mysqli_query($conn,"SELECT * FROM terms WHERE term='$term'");	
                  $term_num_rows=mysqli_num_rows($check_term_query);
                  if($term_num_rows==1){

										//check if the result has been locked by the administrator
										$check_lock_result = mysqli_query($conn,"SELECT * FROM lock_result WHERE locked='yes' AND session='$session' AND term='$term'");	
										$lock_result_num_rows = mysqli_num_rows($check_lock_result);
										if($lock_result_num_rows==0){											
                ?>
              
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-body'>
                    <div class='student-info'>
                    <?php
										//get student details
										$studentDetails = new studentDetails();
										list($studentId,$studentFullname,$studentGuardianName,$studentGender,$studentDateOfBirth,$studentRegNo,$studenyPassword,$studentstatus,$studentRemoved) =$studentDetails->student_info($conn,$reg_no);

                    //display student name
                    echo"<div class='text-uppercase'>$studentFullname</div>";
                      
                    //check if the result has been made available by the teacher
										$available_result = mysqli_query($conn,"SELECT * FROM available_result WHERE student_id='$studentId' AND session='$session' AND term='$term' ");
                    $available_result_num_row = mysqli_num_rows($available_result);
                      if($available_result_num_row==0){
                       echo"<div class='make-result-available'>
                       <a class='btn btn-md btn-danger pull-right submit-result'>Submit Result!
                       <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                       </a></div>";
                      }
                      if($available_result_num_row==1){
                       echo"<div class='make-result-available'><a class='text-success pull-right'>This Result Has Been Submitted</a></div>";
                      }
                    ?>
                    </div><!--end student-info-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
                  
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Attendance</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='school-attendance-content'>
                      <div class='school-attendance-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end school-attendance-list-->                    
                    </div><!--end school-attendance-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->

                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Conduct</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='conduct-content'>
                      <div class='conduct-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end conduct-list-->                    
                    </div><!--end conduct-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->

                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Physical Development, Health and Hygiene</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='physical-dev-content'>
                      <div class='physical-dev-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end physical-dev-list-->                    
                    </div><!--end physical-dev-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
                
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Performance in subjects</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='subject-result-content'>
                      <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                    </div><!--end subject-result-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
                
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Sporting Activities</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='sports-content'>
                      <div class='sports-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end sports-list-->                    
                    </div><!--end sports-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->

                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Clubs, youth, organizations, etc</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='clubs-content'>
                      <div class='clubs-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end clubs-list-->                    
                    </div><!--end clubs-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
                
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Class teacher's comments</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='class-teacher-comment-content'>
                      <div class='class-teacher-comment-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end class-teacher-comment-list-->                    
                    </div><!--end class-teacher-comment-content-->
                  </div><!--end class-teacher-comment-body-->
                </div><!-- end panel-->
                
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Principals's comments</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='principal-comment-content'>
                      <div class='principal-comment-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end principal-comment-list-->                    
                    </div><!--end principal-comment-->
                  </div><!--end principal-comment-body-->
                </div><!-- end panel-->
              </div><!--end data-content-->
              
			<?php  
								}else{
							echo"<div class='bg-danger text-danger'>This result has been locked by the administrator!</div>";
								}
						}else{
					echo"<div class='bg-danger text-danger'>The term chosen does not exist!</div>";
					  }
				}else{
				  echo"<div class='bg-danger text-danger'>The session chosen does not exist!</div>";
					}
			  }else{
				  echo"<div class='bg-danger text-danger'>The student's registration number does not exist!</div>";
			  }
			}
            ?>
              
            </div><!--category-content-->
        
            </div><!--end col-md-9-->
        </div><!--end row-->
        
	  </div><!--end container-->
	</section><!--end section-->
    
  </div><!--end container-fluid-->
</body>
<!--all javascript and jquery plugin-->
<script src="js/jquery-ui.js"></script>
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/jQuery.print.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/croppie.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/ajax-script.js"></script>
<!--<script src="js/ajax-script.js"></script>-->
<script type="text/javascript">
//***********opening modal and setting its purpose****************//
///////////////////////////////////////////////////////////////////
$(document).ready(function(){
  $('body').on('click','.modal-action',function(){
	var data_page = $(this).attr('data-page');
	var modal_content = $(this).attr('id');
	var delete_type = $(this).attr('data-delete-type');
	var delete_id = $(this).attr('data-delete-id');
	var edit_type = $(this).attr('data-edit-type');
	var edit_id = $(this).attr('data-edit-id');
	var display_type = $(this).attr('data-display-type');
	var display_id = $(this).attr('data-display-id');
	$.ajax({
	   url:'php_load/load-modal.php',
	   type:'POST',
	   data:{
		   data_page:data_page,
		   modal_content:modal_content,
		   delete_type:delete_type,
		   delete_id:delete_id,
		   edit_type:edit_type,
		   edit_id:edit_id,
		   display_type:display_type,
		   display_id:display_id
		   },
	   beforeSend: function(){
		 //$('.modal-dialog').attr('hidden',false);
		 $('.modal-dialog').html("<div class='modal-content'><img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px'/></div>");
		   },		
	   success: function(info){
		  $('.modal-dialog').html(info);
		   }
	   })
  }) 
})


//*********load find_result_form**********//
////////////////////////////////////////////
$(document).ready(function(){
  //call load_find_result_form()
  load_find_result_form()
  function load_find_result_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-find-result-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.find-result-form-container .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.find-result-form-container').html(data)	
			}				  
		 });//end ajax request
  }//end load_find_result_form()
})
//validate find result form
$(document.body).on('click','#find_result',function() {
	$("#find_result_form").validate({
	  rules:{
		   reg_no:{
		     required:true
			  },
		   session:{
		     required:true
			  },
		   term:{
		     required:true
			  }
	    },//end rules
	  messages:{
		   reg_no:{
		     required:'Please enter a registration number!'
			   },
		   session:{
		     required:'Please select a session!'
			   },
		   term:{
		     required:'Please select a term!'
			   }
		},//end messages
	  submitHandler:function(form){
			var session = $('#find_result_form #session').val();
			var term = $('#find_result_form #term').val();
			var reg_no = $('#find_result_form #reg_no').val();
			window.location.href="subject-result/"+reg_no+"/"+session+"/"+term;
		 }//end submitHandler
    })
});  


//*******load school_attendance_form******//
////////////////////////////////////////////
$(document).ready(function(){
  //call load_school_attendance_form()
  load_school_attendance_form()
  function load_school_attendance_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-school-attendance-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.school-attendance-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.school-attendance-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_school_attendance_form()
})

//validate Add school attendance
$(document.body).on('click','#add_school_attendance',function() {
  $.validator.addMethod("lessOrEqual",
		function (value, element,param) {
			if(this.optional(element)) return true;
			var i = parseInt(value);
			var j = parseInt($(param).val());
			 return i<=j
			 },
		"Invalid value"
	  );
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_school_attendance_form").validate({
	  rules:{
		   total_days:{
		     required:true,
			 maxlength:3,
			 digits:true
			  },
		   days_present:{
		     required:true,
			 maxlength:3,
			 digits:true,
			 lessOrEqual:'#total_days'
			  },
		   days_punctual:{
		     required:true,
			 maxlength:3,
			 digits:true,
			 lessOrEqual:'#days_present'
			  }
	    },//end rules
	  messages:{
		   total_days:{
		     required:'Please enter a number!',
			 maxlength:'Total days must be less than four (4) digits long!',
			 digits:'Only numbers allowed!'
			   },
		   days_present:{
		     required:'Please enter a number!',
			 maxlength:'Days present must be less than four (4) digits long!',
			 digits:'Only numbers allowed!',
			 lessOrEqual:'Days present must be less than or equal to total number of days!'
			   },
		   days_punctual:{
		     required:'Please enter a number!',
			 maxlength:'Days punctual must be less than four (4) digits long!',
			 digits:'Only numbers allowed!',
			 lessOrEqual:'Days present must be less than or equal to number of days present!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-school-attendance.php',
			 type:'POST',
			 data:$('#add_school_attendance_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_school_attendance_form input').attr('disabled',true);
			   $('#add_school_attendance .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.school-attendance-info').html(info)
			   $('#add_school_attendance_form input').attr('disabled',false);
			   $('#add_school_attendance .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
}); 

//***********load conduct_form*************//
/////////////////////////////////////////////
$(document).ready(function(){
  //call conduct_form()
  load_conduct_form()
  function load_conduct_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-conduct-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.conduct-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.conduct-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_conduct_form()
})
//validate Add conduct
$(document.body).on('click','#add_conduct',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_conduct_form").validate({
	  rules:{
		   comment:{
		     required:true,
			   maxlength:280
			  },
		   remark:{
		     required:true,
			   maxlength:70
			  }
	    },//end rules
	  messages:{
		   comment:{
		     required:'Please enter a comment!',
			 maxlength:'Your comment must be less than two hundred and eighty(280) digits long!'
			   },
		   remark:{
		     required:'Please enter a remark!',
			 maxlength:'Your remark must be less than seventy(70) digits long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-conduct.php',
			 type:'POST',
			 data:$('#add_conduct_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_conduct_form input').attr('disabled',true);
			   $('#add_conduct .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.conduct-info').html(info)
			   $('#add_conduct_form input').attr('disabled',false);
			   $('#add_conduct .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
}); 

//************load physical_dev_form*************//
//////////////////////////////////////////////////
$(document).ready(function(){
  //call physical_dev_form()
  load_physical_dev_form()
  function load_physical_dev_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-physical-dev-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.physical-dev-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.physical-dev-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_physical_dev_form()
})
//validate Add physical_dev
$(document.body).on('click','#add_physical_dev',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_physical_dev_form").validate({
	  rules:{
		   start_height:{
			 digits:true
			  },
		   end_height:{
			 digits:true
			  },
		   start_weight:{
			 digits:true
			  },
		   end_weight:{
			 digits:true
			  },
		   days_of_illness:{
			 digits:true
			  },
		   nature_of_illness:{
		     required:true,
			 maxlength:50
			  }
	    },//end rules
	  messages:{
		  start_height:{
			 digits:'Only numbers allowed!'
			   },
		  end_height:{
			 digits:'Only numbers allowed!'
			   },
		  start_weight:{
			 digits:'Only numbers allowed!'
			   },
		  end_weight:{
			 digits:'Only numbers allowed!'
			   },
		  days_of_illness:{
			 digits:'Only numbers allowed!'
			   },
		   nature_of_illness:{
			 maxlength:'Your text must be less than fifty (50) digits long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-physical-dev.php',
			 type:'POST',
			 data:$('#add_physical_dev_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_physical_dev_form input').attr('disabled',true);
			   $('#add_physical_dev .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.physical-dev-info').html(info)
			   $('#add_physical_dev_form input').attr('disabled',false);
			   $('#add_physical_dev .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
});

//*******************load subject_result for regular terms****************//
/////////////////////////////////////////////////////////
$(document).ready(function(){
  //call subject_result_form()
  load_subject_result_form()
  function load_subject_result_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/subject-result-action.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.subject-result-content .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.subject-result-content').html(data)	
			}				  
		 });//end ajax request
  }//end load_subject_result_form()
})

//validate add-subject-result
$(document.body).on('click','#add_subject_result',function(){
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';

	$("#add_subject_result_form").validate({
	  rules:{
		   subject:{
		     required:true,
			 digits:true
			  },
		   ca_1:{
			 digits:true,
			 max:40
			  },
		   exam:{
			 digits:true,
			 max:60
			  }
	    },//end rules
	  messages:{
		   subject:{
		     required:'Please select a subject!',
			 digits:'It must be a digit!'
			   },
		   ca_1:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to forty (40)!'
			   },
		   exam:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to sixty (60)!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/subject-result-action.php',
			 type:'POST',
			 data:$('#add_subject_result_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
				 $('.add-subject-result-info').html("<a class='text-danger bg-danger'>That entry already exists!</a>")
				 }else if(info=='gna'){
				 $('.add-subject-result-info').html("<a class='text-danger bg-danger'>A grade does not exist for the total score!</a>")
				 }else{
				 $('.subject-result-list').empty();
				 $('.subject-result-list').html(info);
				 $('.close').click();
					 }				
				 //set inputs back to default
				 $('.modal-body input').attr('disabled',false)
				 $('.modal-body .btn').attr('disabled',false)
				 $('.modal-body .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler

    })
});
//validate edit-subject-result
$(document.body).on('click','#edit_subject_result',function() {
	  var edit_id = $(this).attr("data-edit-id") ;
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#edit_subject_result_form").validate({
	  rules:{
		   subject:{
		     required:true,
			 digits:true
			  },
		   ca_1:{
			 digits:true,
			 max:40
			  },
		   exam:{
			 digits:true,
			 max:60
			  }
	    },//end rules
	  messages:{
		   subject:{
		     required:'Please select a subject!',
			 digits:'It must be a digit!'
			   },
		   ca_1:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to forty (40)!'
			   },
		   exam:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to sixty (60)!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/subject-result-action.php',
			 type:'POST',
			 data:$('#edit_subject_result_form').serialize()+"&edit_id="+edit_id+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
				 $('.edit-subject-result-info').html("<a class='text-danger bg-danger'>That entry already exists!</a>")
				 }else if(info=='gna'){
				 $('.edit-subject-result-info').html("<a class='text-danger bg-danger'>A grade does not exist for the total score!</a>")
				 }else{
				 $('.subject-result-list').empty();
				 $('.subject-result-list').html(info);
				 $('.close').click();
					 }				
				 //set inputs back to default
				 $('.modal-body input').attr('disabled',false)
				 $('.modal-body .btn').attr('disabled',false)
				 $('.modal-body .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler

    })
});  
//delete subject-result
$(document.body).on('click','#delete_subject_result',function(e) {
	e.preventDefault();
	var delete_type = $(this).attr('data-delete-type');
	var delete_id = $(this).attr('data-delete-id');
	var reg_no = '<?php echo $reg_no ?>';
	var session = '<?php echo $session ?>';
	var term = '<?php echo $term ?>';
	 $.ajax({
	   url:'php_load/subject-result-action.php',
	   type:'POST',
	   data:{
		reg_no : reg_no,
		term : term,
		session : session,
		delete_id : delete_id,
		delete_type : delete_type   
		   },
	   beforeSend: function(){
		   $('.modal-body input').attr('disabled',true)
		   $('.modal-body .btn').attr('disabled',true)
		   $('.modal-body .preloader').attr('hidden',false);
		   },		
	   success: function(info){
		   $('.subject-result-list').empty();
		   $('.subject-result-list').html(info);
		   $('.close').click();
		   }
		})
})

//*************for adding mock exam result***************//
/////////////////////////////////////////////////
//validate add-mock-exam-result
$(document.body).on('click','#add_mock_exam_result',function(){
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		//CA for mock exams should be empty
		var ca_1 = '';
	$("#add_mock_exam_result_form").validate({
	  rules:{
		   subject:{
		     required:true,
			 digits:true
			  },
		   exam:{
			 digits:true,
			 max:100
			  }
	    },//end rules
	  messages:{
		   subject:{
		     required:'Please select a subject!',
			 digits:'It must be a digit!'
			   },
		   exam:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to one hundred (100)!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/subject-result-action.php',
			 type:'POST',
			 data:$('#add_mock_exam_result_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session+"&ca_1="+ca_1,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
				 $('.add-mock-exam-result-info').html("<a class='text-danger bg-danger'>That entry already exists!</a>")
				 }else if(info=='gna'){
				 $('.add-mock-exam-result-info').html("<a class='text-danger bg-danger'>A grade does not exist for the total score!</a>")
				 }else{
				 $('.subject-result-list').empty();
				 $('.subject-result-list').html(info);
				 $('.close').click();
					 }				
				 //set inputs back to default
				 $('.modal-body input').attr('disabled',false)
				 $('.modal-body .btn').attr('disabled',false)
				 $('.modal-body .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler

    })
});
//validate edit-mock-exam-result
$(document.body).on('click','#edit_mock_exam_result',function() {
	  var edit_id = $(this).attr("data-edit-id") ;
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		//CA for mock exams should be empty
		var ca_1 = '';
	$("#edit_mock_exam_result_form").validate({
	  rules:{
		   subject:{
		     required:true,
			 digits:true
			  },
		   exam:{
			 digits:true,
			 max:100
			  }
	    },//end rules
	  messages:{
		   subject:{
		     required:'Please select a subject!',
			 digits:'It must be a digit!'
			   },
		   exam:{
			 digits:'It must be a digit!',
			 max:'Must be less than or equal to one hundred (100)!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/subject-result-action.php',
			 type:'POST',
			 data:$('#edit_mock_exam_result_form').serialize()+"&edit_id="+edit_id+"&reg_no="+reg_no+"&term="+term+"&session="+session+"&ca_1="+ca_1,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
				 $('.edit-mock-exam-result-info').html("<a class='text-danger bg-danger'>That entry already exists!</a>")
				 }else if(info=='gna'){
				 $('.edit-mock-exam-result-info').html("<a class='text-danger bg-danger'>A grade does not exist for the total score!</a>")
				 }else{
				 $('.subject-result-list').empty();
				 $('.subject-result-list').html(info);
				 $('.close').click();
					 }				
				 //set inputs back to default
				 $('.modal-body input').attr('disabled',false)
				 $('.modal-body .btn').attr('disabled',false)
				 $('.modal-body .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler

    })
});  
//delete smock_exam-result
$(document.body).on('click','#delete_mock_exam_result',function(e) {
	e.preventDefault();
	var delete_type = $(this).attr('data-delete-type');
	var delete_id = $(this).attr('data-delete-id');
	var reg_no = '<?php echo $reg_no ?>';
	var session = '<?php echo $session ?>';
	var term = '<?php echo $term ?>';
	 $.ajax({
	   url:'php_load/subject-result-action.php',
	   type:'POST',
	   data:{
		reg_no : reg_no,
		term : term,
		session : session,
		delete_id : delete_id,
		delete_type : delete_type   
		   },
	   beforeSend: function(){
		   $('.modal-body input').attr('disabled',true)
		   $('.modal-body .btn').attr('disabled',true)
		   $('.modal-body .preloader').attr('hidden',false);
		   },		
	   success: function(info){
		   $('.subject-result-list').empty();
		   $('.subject-result-list').html(info);
		   $('.close').click();
		   }
		})
})
//*************load sports_result***************//
/////////////////////////////////////////////////
$(document).ready(function(){
  //call sports_form()
  load_sports_form()
  function load_sports_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-sports-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.sports-content .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.sports-content').html(data)	
			}				  
		 });//end ajax request
  }//end load_sports_form()
})
//validate Add sports
$(document.body).on('click','#add_sports',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("form#add_sports_form").validate({
	  rules:{
		indoor_games:{
		   required:true
			},
		  balls_games:{
			required:true
			},
		  combat_games:{
			required:true
			},
		  track:{
			required:true
			},
		  jumps:{
			required:true
			},
		  throws:{
			required:true
			},
		  swimming:{
			required:true
			},
		  weight_lifting:{
			required:true
			},
		  comment:{
		   required:true,
		   maxlength:200
			}
	    },//end rules
	  messages:{
		  indoor_games:{
			required:'Please make a selection!'
			   },
		  balls_games:{
			required:'Please make a selection!'
			   },
		  combat_games:{
			required:'Please make a selection!'
			   },
		  track:{
			required:'Please make a selection!'
			   },
		 jumps:{
			required:'Please make a selection!'
			   },
		 throws:{
			required:'Please make a selection!'
			   },
		 swimming:{
			required:'Please make a selection!'
			   },
		 weight_lifting:{
			required:'Please make a selection!'
			   },
		  comment:{
			required:'Please leave a comment!',
			maxlength:'Your text must be less than two hundred (200) characters long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-sports.php',
			 type:'POST',
			 data:$('#add_sports_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_sports_form input').attr('disabled',true);
			   $('#add_sports .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.sports-info').html(info)
			   $('#add_sports_form input').attr('disabled',false);
			   $('#add_sports .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
});

//*******************load clubs_result*************//
//*************************************************//

$(document).ready(function(){
  //call clubs_form()
  load_clubs_form()
  function load_clubs_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/clubs-action.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.clubs-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.clubs-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_clubs_form()
})
//validate add-clubs
$(document.body).on('click','#add_clubs',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_clubs_form").validate({
	  rules:{
		   organization:{
		     required:true,
			 maxlength:30
			  },
		   office_held:{
		     required:true,
			 maxlength:30
			  },
		   significant_contribution:{
			 maxlength:40
			  }
	    },//end rules
	  messages:{
		   organization:{
		     required:'Please enter name of organization!',
			 maxlength:'Must not be more than thirty (30) characters long!'
			   },
		   office_held:{
		     required:'Please enter office held!',
			 maxlength:'Must not be more than thirty (30) characters long!'
			   },
		   significant_contribution:{
			 maxlength:'Must not be more than forty (40) characters long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/clubs-action.php',
			 type:'POST',
			 data:$('#add_clubs_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
					 $('.add-clubs-info').html("<a class='text-danger bg-danger'>Cannot input more than two clubs!</a>")
					 }else{
				 $('.clubs-list').empty();
				 $('.clubs-list').html(info);
				 $('.close').click();
					 }				 
				 }
			 })
		 }//end submitHandler

    })
});
//validate edit-clubs
$(document.body).on('click','#edit_clubs',function() {
	  var edit_id = $(this).attr("data-edit-id") ;
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#edit_clubs_form").validate({
	  rules:{
		   organization:{
		     required:true,
			 maxlength:30
			  },
		   office_held:{
		     required:true,
			 maxlength:30
			  },
		   significant_contribution:{
			 maxlength:40
			  }
	    },//end rules
	  messages:{
		   organization:{
		     required:'Please enter name of organization!',
			 maxlength:'Must not be more than thirty (30) characters long!'
			   },
		   office_held:{
		     required:'Please enter office held!',
			 maxlength:'Must not be more than thirty (30) characters long!'
			   },
		   significant_contribution:{
			 maxlength:'Must not be more than forty (40) characters long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/clubs-action.php',
			 type:'POST',
			 data:$('#edit_clubs_form').serialize()+"&edit_id="+edit_id+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
				 $('.modal-body input').attr('disabled',true)
				 $('.modal-body .btn').attr('disabled',true)
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
					 $('.edit-clubs-info').html("<a class='text-danger bg-danger'>Cannot input more than two clubs!</a>")
					 }else{
				 $('.clubs-list').empty();
				 $('.clubs-list').html(info);
				 $('.close').click();
					 }				 
				 }
			 })
		 }//end submitHandler

    })
});  
//delete clubs
$(document.body).on('click','#delete_clubs',function(e) {
	e.preventDefault();
	var delete_type = $(this).attr('data-delete-type');
	var delete_id = $(this).attr('data-delete-id');
	var reg_no = '<?php echo $reg_no ?>';
	var session = '<?php echo $session ?>';
	var term = '<?php echo $term ?>';
	 $.ajax({
	   url:'php_load/clubs-action.php',
	   type:'POST',
	   data:{
		reg_no : reg_no,
		term : term,
		session : session,
		delete_id : delete_id,
		delete_type : delete_type   
		   },
	   beforeSend: function(){
		   $('.modal-body input').attr('disabled',true)
		   $('.modal-body .btn').attr('disabled',true)
		   $('.modal-body .preloader').attr('hidden',false);
		   },		
	   success: function(info){
		   $('.clubs-list').empty();
		   $('.clubs-list').html(info);
		   $('.close').click();
		   }
		})
})


//*********************load class_teacher_comment*******************//
//******************************************************************//
$(document).ready(function(){
  //call class_teacher_comment_form()
  load_class_teacher_comment_form()
  function load_class_teacher_comment_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-class-teacher-comment-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.class-teacher-comment-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.class-teacher-comment-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_class_teacher_comment_form()
})
//validate Add teacher_comment
$(document.body).on('click','#add_class_teacher_comment',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_class_teacher_comment_form").validate({
	  rules:{
		   class_teacher_comment:{
		     required:true,
			 maxlength:220
			  }
	    },//end rules
	  messages:{
		   class_teacher_comment:{
		     required:'Please enter a comment!',
			 maxlength:'Your comment must be less than two hundred and twenty (220) characters long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-class-teacher-comment.php',
			 type:'POST',
			 data:$('#add_class_teacher_comment_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_class_teacher_comment_form input').attr('disabled',true);
			   $('#add_class_teacher_comment .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.class-teacher-comment-info').html(info)
			   $('#add_class_teacher_comment_form input').attr('disabled',false);
			   $('#add_class_teacher_comment .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
});


//load principal_comment
$(document).ready(function(){
  //call principal_comment_form()
  load_principal_comment_form()
  function load_principal_comment_form(){  
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
		$.ajax({
		  type:'POST',
		  url:'php_load/load-principal-comment-form.php',
		  data:{
			reg_no:reg_no,
			session:session,
			term:term
			},
		  global:false,
		  beforeSend: function(){
			$('.principal-comment-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.principal-comment-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_principal_comment_form()
})
//validate Add principal_comment
$(document.body).on('click','#add_principal_comment',function() {
	  var reg_no = '<?php echo $reg_no ?>';
	  var session = '<?php echo $session ?>';
	  var term = '<?php echo $term ?>';
	$("#add_principal_comment_form").validate({
	  rules:{
		   principal_comment:{
		     required:true,
			 maxlength:220
			  }
	    },//end rules
	  messages:{
		   principal_comment:{
		     required:'Please enter a comment!',
			 maxlength:'Your comment must be less than two hundred and twenty (220) characters long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/add-principal-comment.php',
			 type:'POST',
			 data:$('#add_principal_comment_form').serialize()+"&reg_no="+reg_no+"&term="+term+"&session="+session,
			 beforeSend: function(){
			   $('#add_principal_comment_form input').attr('disabled',true);
			   $('#add_principal_comment .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   $('.principal-comment-info').html(info)
			   $('#add_principal_comment_form input').attr('disabled',false);
			   $('#add_principal_comment .preloader').attr('hidden',true);
			 }
		 })
    }//end submitHandler
  }); 
}); 
///////////////////////////////////////
//////////////////////////////////////    
///////////submit-result/////////////
$(document.body).on('click','.submit-result',function(e) {
	e.preventDefault();
	var reg_no = '<?php echo $reg_no ?>';
	var session = '<?php echo $session ?>';
	var term = '<?php echo $term ?>';
	 $.ajax({
	   url:'php_load/submit-result.php',
	   type:'POST',
	   data:{
		reg_no : reg_no,
		term : term,
		session : session,
		   },
	   beforeSend: function(){
		   $('.submit-result').attr('disabled',true)
		   $('.submit-result .preloader').attr('hidden',false);
		   },		
	   success: function(info){
		   $('.make-result-available').html(info);
		   }
		})
})
    
</script>
</html>