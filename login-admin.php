<?php
include("./inc/session.inc.php"); 
include("./inc/db.inc.php"); 
include("./classes/develop-php-library.php"); 
 ?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="IE=9">
<base href="<?php echo base_url();?>" />

<title>Admin Login | School</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/ionicons.min.css" rel="stylesheet">
<link href="fonts/flaticon1/flaticon.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href='css/croppie.css' rel='stylesheet'>
<link href="css/style.css" rel="stylesheet">
</head>

<body class="login-landing admin-landing">
  <div class="custom-container">
  
    <div class="logo-heading">
      <p><a href="home"><img src="img/logo.png" class='logo'/></a></p>
    </div>

    <section class="login-bg">
      <div class="container">
        <div class="panel login-box">
          
          <div class="panel-heading">Login as administrator</div>
          
          <div class="panel-body">
 
            <div class="login-info"></div>
            
            <form method="post" id="login-form-admin" name="login-form-admin">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>            
              <button class="btn btn-primary btn-block btn-lg" type="submit" id="login" name="login">Login
              <img src='img/ajax-loader.gif' class="preloader" width='22px' height='22px' hidden="true"/></button>
            </form>
          </div>
        </div><!--end panel login-box-->
      </div>
    </section>
    
  </div><!--end container-fluid-->
</body>
<!--all javascript and jquery plugin-->

<script src="js/jquery.xdomainrequest.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/ajax-script.js"></script>
<script>
// login form validation
$(document).ready(function() {
   $('#login-form-admin').validate({
	  rules:{
 	       username:{
		     required:true,
		     minlength:5
		      },
 	       password:{
		     required:true,
		     minlength:5
		      }
	    },//end rules
	  messages:{
		   username:{
		     required:'Please enter your username!',
		     minlength:'Your username should be at least 5 characters long!'
			   },
		   password:{
		     required:'Please enter your password!',
		     minlength:'Your password should be at least 5 characters long!'
			   }
		},//end messages
		
	  submitHandler:function(form){
		 $.ajax({
			 type:'POST',
			 cache:false,
			 url:'php_load/login-val.php',
			 data:$('#login-form-admin').serialize()+"&userType=admin",
			 beforeSend: function(){
				 $('input').attr('disabled',true)
				 $('.btn').attr('disabled',true)
				 $('.preloader').attr('hidden',false);
				 },
			 success: function(info){
				 $('.login-info').empty();
				 $('.login-info').html(info);
				 $('.preloader').attr('hidden',true);
				 $('input').attr('disabled',false)
				 $('.btn').attr('disabled',false)
				 if(!info){
					 window.location.href="admin-dashboard";
					 }
				 
				 }			 
			})
		 }//end submitHandler
	   
    });//end validate
}); // end ready
    
/*$(document).ready(function(){
	var width = $(window).width();
	alert(width);
	})*/
</script>
</html>