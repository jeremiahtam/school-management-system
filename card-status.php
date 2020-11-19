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

<title>Check Card Status| School</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet" media="print">
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
      
	<section class='category-body'>
	  <div class='container-fluid'>

		<div class='row'>
          
          <div class='col-md-3'>
              
          <?php include("./inc/side-menu.inc.php");?>
          
          </div><!--end col-md-3-->
          
          <div class='col-md-9'>
              
            <div class='category-content'>
              <div class='category-content-heading'>Check Validity of Card</div>
                
              <div class='data-input-action'>
                <form id='search_card_form' name='search_card_form'>
                  <div class='row'>
                    <div class='form-group col-sm-4'>
                      <label for='serial_no'>Enter Card Serial Number</label><br/>
                      <input type='text' class='form-control serial_no' id='serial_no' name='serial_no' placeholder='Card Serial Number'>
                    </div><!-- /end-column -->
                    <div class='form-group col-sm-3'>
                      <button class='btn btn-outline-primary form-control' type='submit' id='search_card' name='search_card'>Search 
                      <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </button>
                    </div><!-- /end-column -->
                  </div><!-- /end row -->
                </form>
              </div><!-- endinput-action -->
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Cards</div><!--panel-title-->
                    <div class='alert alert-warning alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span>
                      </button>
                      Use serial number to check the validity of result checking cards.
                    </div>
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='search-card-list table-responsive' id="search-card-list">
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </div><!--end class-list-->                    
                    </div><!--end class-list-->                    
                  </div><!--end panel-body-->
                </div>
              </div><!--end row-->
              
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
$(document.body).on('click','#print',function(){  
  $("#print-area").print({
	  addGlobalStyles : true,
	  stylesheet : null,
	  rejectWindow : true,
	  noPrintSelector : ".no-print",
	  iframe : true,
	  append : null,
	  prepend : null
  });
});

//validate activation code
$(document.body).on('click','#search_card_form',function() {
	$("#search_card_form").validate({
	  rules:{
		   serial_no:{
		     required:true,
			 maxlength:20
			  }
	    },//end rules
	  messages:{
		   serial_no:{
		     required:'Please enter your card serial number!',
			 maxlength:'Your activation code must be twenty(20) digits long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/load-card-status.php',
			 type:'POST',
			 data:$('#search_card_form').serializeArray(),
			 beforeSend: function(){
				 $('#search_card_form input').attr('disabled',true);
				 $('#search_card .preloader').attr('hidden',false);
				 $('.search-card-list .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 $('.search-card-list .preloader').attr('hidden',true);
				 $('.search-card-list').html(info)
				 $('#search_card_form input').attr('disabled',false);
				 $('#search_card .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler
    })
});  
</script>
</html>