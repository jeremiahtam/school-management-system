<?php
include("./inc/session.inc.php"); 
include("./inc/db.inc.php");
include("./classes/develop-php-library.php"); 
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="IE=9">
<base href="<?php echo base_url();?>" />

<title>Home</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/ionicons.min.css" rel="stylesheet">
<link href="fonts/flaticon1/flaticon.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/homepage-style.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href='css/datepicker.css' rel='stylesheet'>
</head>

<body>
  <div class="custom-container">
    <?php include("./inc/menu.inc.php");?>
    <section class="first-section">
      <div class="col-md-7">
      </div>
      <div class="col-md-5">
        <div class='info-box'>
          <div class='info-box-welcome'>Welcome to Votam Life Gate Portal</div>
          <div class='info-box-heading-1'>Access your end of term result using this portal!</div>
          <div class='info-box-heading-2'>To do so, the following steps should be taken:</div>
          <div class='info-box-body'>
            <ul>
              <li>Go to the <u><a href='<?php echo base_url()."account";?>'>login page</a></u> and access your account using your login details.</li>
              <li>Your password should be in the format SURNAME/DATE-OF-BIRTH. For example SAMSON/05/09/1993</li>
              <li>Select 'Term Result' from menu.</li>
              <li>Input your card pin and select the session as well as term of preference then click 'Get Result'.</li>
              <li>Click on the 'PRINT' button to print your result.</li>
              <li>Contact <strong>09082267471</strong> in case of any difficulties.</li>            
            </ul>
          </div><!--end info-box-body-->
          <h5 class='text-warning'>NOTE: You cannot use the scratch card for more than one term and it can only be used up to FIVE (5) times per term.</h5>
          <h5 class='text-danger'>You are advised to use a chrome browser in order to experience maximum performance!</h5>
          <a class='btn btn-block btn-lg btn-primary' href='<?php echo base_url()."account";?>'>LOGIN</a>
          <div class='coy-footer'>An <a href='http://www.oncliqsupport.com'>Oncliqsupport</a> Production</div>
        </div><!--end info-box-->
      </div>
    </section>  
    
    
  </div><!--end container-->
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

</html>