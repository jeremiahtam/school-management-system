<?php 
include("./inc/session.inc.php"); 
include("./inc/db.inc.php"); 
include("./classes/develop-php-library.php"); 
//redirect if user is not logged in
if(!isset($loggedin_user)){
	header("Location:". base_url()."login-admin");
}
//redirect if loggend in user is not admin
if($loggedin_user_type!=='admin'){
 header("Location:". base_url()."login-admin");
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="IE=9">
<base href="<?php echo base_url();?>" />

<title>Settings | School</title>
<link rel="icon" href="img/logo.png">
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
              <div class='category-content-heading'>Settings</div>
              <div class='data-content'>
              
              <div class="row">
              <div class='col-md-8'>
                
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>School Personal Information</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='settings-content'>
                      <div class='settings-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>         
                      </div><!--end settings-list-->                    
                    </div><!--end settings-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->

                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Resumption Day</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='resumption-selector-form-content'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>         
                    </div><!--end settings-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
                
              </div><!-- end col-->

              <div class='col-md-4'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Change Password</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    <div class='password-content'>
                      <div class='password-list'>
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>         
                      </div><!--end settings-list-->                    
                    </div><!--end settings-content-->
                  </div><!--end panel-body-->
                </div><!-- end panel-->
              </div><!-- end col-->
              </div><!-- end row-->
                
              </div><!--settings-data-content-->
            </div><!--category-content-->
          </div><!--end col-md-9-->
        </div><!--end row-->
        
	  </div><!--end container-->
	</section><!--end profile-body-->
    
  </div><!--end container-fluid-->
</body>
<!--all javascript and jquery plugin-->
<script src='js/jquery-ui.js'></script>
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/croppie.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
//loading settings panel
$(document).ready(function(){
  //call load_settings_action()
  load_settings_action()
  function load_settings_action(){  
	  var page = 1;	  
		$.ajax({
		  type:'POST',
		  url:'php_load/load-settings.php',
		  data:{
			page:page
			},
		  global:false,
		  beforeSend: function(){
			$('.settings-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.settings-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_settings_action()
})
//load update-password
$(document).ready(function(){
  //call load_change_password_action()
  load_change_password_action()
  function load_change_password_action(){  
		$.ajax({
		  type:'POST',
		  url:'php_load/load-change-password.php',
		  data:{},
		  global:false,
		  beforeSend: function(){
			$('.password-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.password-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_change_password_action()
})

//load resumption selector form
$(document).ready(function(){
  //call load_resumption_selector()
  load_resumption_selector()
  function load_resumption_selector(){  
		$.ajax({
		  type:'POST',
		  url:'php_load/load-resumption-selector.php',
		  data:{},
		  global:false,
		  beforeSend: function(){
			$('.resumption-selector-form-content .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.resumption-selector-form-content').html(data)	
			}				  
		 });//end ajax request
  }//end 
})

//load resumption form
$(document.body).on('click','#load_resumption_form',function() {
  $("#resumption_selector_form").validate({
	rules:{
		session_selector:{
		   required:true
			},
			term_selector:{
		   required:true
			}
	  },//end rules
	messages:{
		session_selector:{
		   required:'Please select a session!'
			 },
			 term_selector:{
		   required:'Please select a term!'
			 }
	  },//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/load-resumption.php',
		   type:'POST',
		   data:$('#resumption_selector_form').serializeArray(),
		   beforeSend:function(){
			   $('#resumption_selector_form select').attr('disabled',true)
			   $('#resumption_selector_form .btn').attr('disabled',true)
			   $('#resumption_selector_form .preloader').attr('hidden',false);
			   },
		   success: function(info){
			   $('.resumption-form-content').html(info)
			   $('#resumption_selector_form select').attr('disabled',false)
			   $('#resumption_selector_form .btn').attr('disabled',false)
			   $('#resumption_selector_form .preloader').attr('hidden',true);
			   }
		   })
	   }//end submitHandler
  })  
});  
//attaching datepicker
$(document).on('focus','#resumption_date',function(){
	$("#resumption_date").datepicker({
		calendarWeeks:true,
		todayHighlight:true,
		autoclose:true,
		todayHighlight: true,
		format:'yyyy-mm-dd'
	  })    
});


//update settings 
$(document.body).on('click','#update_settings',function() {
  
  $("#update_settings_form").validate({
	rules:{
		 name:{
		   required:true
			},
		 email:{
		   required:true,
		   email:true
			},
		 address:{
		   required:true
			},
		 phone_number:{
		   required:true
			},
		 website:{
		   required:true
			}
	  },//end rules
	messages:{
		 name:{
		   required:'Please enter the school name!'
			 },
		 email:{
		   required:'Please enter an email address!',
		   email:'Invalid email! Please enter a valid email address!'
			 },
		 address:{
		   required:'Please enter your school address!'
			 },
		 phone_number:{
		   required:'Please enter a phone number!'
			 },
		 website:{
		   required:'Please enter your website address!'
			 }
	  },//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/update-settings.php',
		   type:'POST',
		   data:$('#update_settings_form').serializeArray(),
		   beforeSend:function(){
			   $('#update_settings_form input').attr('disabled',true)
			   $('#update_settings_form .btn').attr('disabled',true)
			   $('#update_settings_form .preloader').attr('hidden',false);
			   },
		   success: function(info){
			   $('.update-settings-info').html(info)
			   $('#update_settings_form input').attr('disabled',false)
			   $('#update_settings_form .btn').attr('disabled',false)
			   $('#update_settings_form .preloader').attr('hidden',true);
			   }
		   })
	   }//end submitHandler
  })  
});

//Change Password
$(document.body).on('click','#change_password',function() {  
  $("#change_password_form").validate({
	rules:{
		 old_password:{
		   required:true,
		   minlength:6
			},
		 new_password:{
		   required:true,
		   minlength:6
			},
		 repeat_new_password:{
		   required:true,
		   minlength:6,
		   equalTo:'#new_password'
			}
	  },//end rules
	messages:{
		 old_password:{
		   required:'Please enter your old password!',
		   minlength:"Your password should contain at least 6 characters."
			 },
		 new_password:{
		   required:'Please enter your new password!',
		   minlength:"Your password should contain at least 6 characters."
			 },
		 repeat_new_password:{
		   required:'Please repeat your new password!',
		   minlength:"Your password should contain at least 6 characters.",
		   equalTo:'Your new password does not match!'
			 }
	  },//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/change-password.php',
		   type:'POST',
		   data:$('#change_password_form').serializeArray(),
		   beforeSend:function(){
			   $('#change_password_form input').attr('disabled',true)
			   $('#change_password_form .btn').attr('disabled',true)
			   $('#change_password_form .preloader').attr('hidden',false);
			   },
		   success: function(info){
			   $('.change-password-info').html(info)
			   $('#change_password_form input').attr('disabled',false)
			   $('#change_password_form .btn').attr('disabled',false)
			   $('#change_password_form .preloader').attr('hidden',true);
			   }
		   })
	   }//end submitHandler
  })  
});  

//Update Resumption
$(document.body).on('click','#update_resumption',function() {
	var selected_session = $('#update_resumption_form #selected_session_info').attr('data-selected-session');
	var selected_term = $('#update_resumption_form #selected_term_info').attr('data-selected-term');
  $.validator.addMethod(
		"dateFormat",
		function (value, element) {
		  // put your own logic here, this is just a (crappy) example 
		  return value.match(/^\d\d\d\d?\-\d\d?\-\d\d$/);
		},
		"Please enter a date in the format yyyy-mm-dd"
	  );
  $("#update_resumption_form").validate({
	rules:{
		 current_session:{
		   required:true
			},
		 current_term:{
		   required:true
			},
		 next_session:{
		   required:true
			},
		 next_term:{
		   required:true
			},
		 resumption_date:{
		   required:true,
		   dateFormat:true
			},
		 result_collection_date:{
		   required:true
			}
	  },//end rules
	messages:{
		 current_session:{
		   required:'Please select current session!'
			 },
		 current_term:{
		   required:'Please select current term!'
			 },
		 next_session:{
		   required:'Please select the next session!'
			 },
		 next_term:{
		   required:'Please select the next term!'
			 },
		 resumption_date:{
		   required:'Please enter resumption date!'
			 },
		 result_collection_date:{
		   required:'Please enter result collection date!'
			 }
	  },//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/update-resumption.php',
		   type:'POST',
		   data:$('#update_resumption_form').serialize()+"&selected_session="+selected_session+"&selected_term="+selected_term,
		   beforeSend:function(){
			   $('#update_resumption_form input').attr('disabled',true)
			   $('#update_resumption_form .btn').attr('disabled',true)
			   $('#update_resumption_form .preloader').attr('hidden',false);
			   },
		   success: function(info){
			   $('.update-resumption-info').html(info)
			   $('#update_resumption_form input').attr('disabled',false)
			   $('#update_resumption_form .btn').attr('disabled',false)
			   $('#update_resumption_form .preloader').attr('hidden',true);
			   }
		   })
	   }//end submitHandler
  })  
});  
</script>
</html>