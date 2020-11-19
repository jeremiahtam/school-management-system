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

<title>Generate Cards | School</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet" media="print">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/ionicons.min.css" rel="stylesheet">
<link href="fonts/flaticon1/flaticon.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/style.css" media='screen' rel="stylesheet">
<link href="css/print.css" media='print' rel="stylesheet">
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
              <div class='category-content-heading'>Generate Cards</div>
                
              <div class='data-input-action'>
                <form id='generate_card_form' name='generate_card_form'>
								<div class='display-notice'></div>
                  <div class='row'>
                    <div class='form-group col-sm-4'>                     
                      <label for='activation_code'>Enter Activation Code</label><br/>
                      <input type='text' class='form-control activation_code' id='activation_code' name='activation_code' placeholder='Enter Activation Code'>
                    </div><!-- /form-group -->
                    <div class='form-group col-sm-3'>                     
                      <button class='btn btn-outline-primary form-control' type='submit' id='generate_card' name='generate_card'>Generate 
                      <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </button>
                    </div><!-- /form-group -->
                  </div><!-- /end row -->
                </form>
              </div><!-- end input-action -->
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Available Cards</div><!--panel-title-->
                    <div class='alert alert-warning alert-dismissible'>
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span>
                      </button>
                      Generate and print out result checking cards.
                    </div>
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='cards-list table-responsive' id="cards-list">                        
                        
                        <div class='cards-cover'>
                        <table width='100%'>
                          <tr>
                          </tr>
                        </table>											
                        </div>
  
                            
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
<!--<script src="js/jQuery.print.js"></script>-->
<script src="js/printPreview.js"></script>
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
$('#print').printPreview({
  obj2print:'#print-area',
	resizable : 'yes'
});
});



//loading already generated cards
$(document).ready(function(){
  //call load_cards_action()
  load_cards_action()
  function load_cards_action(){
	  var page = 1;
		$.ajax({
		  type:'POST',
		  url:'php_load/load-cards.php',
		  data:{
			page:page
			},
		  global:false,
		  beforeSend: function(){
			$('.cards-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.data-content-loaded .cards-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_cards_action()
})

//***when the user clicks on the number navigation buttons...***//
//it would load automatically without refreshing
$(document.body).on('click','.cards-navigation .pagination_link',function(){
  var page = $(this).attr('id');		 
  $.ajax({
	type:'POST',
	url:'php_load/load-cards.php',
	data:{
	  page:page
	  },
	global:false,
	beforeSend: function(){
	  $('.cards-list .preloader').attr('hidden',false);
		},
	success:function(data){
		$('.cards-list').html(data)
	 }
   });//end ajax request
})

//validate activation code
$(document.body).on('click','form',function() {
	$("#generate_card_form").validate({
	  rules:{
		   activation_code:{
		     required:true,
			 maxlength:4
			  }
	    },//end rules
	  messages:{
		   activation_code:{
		     required:'Please enter your activation code!',
			 maxlength:'Your activation code must be four(4) digits long!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/generate-cards.php',
			 type:'POST',
			 data:$('#generate_card_form').serializeArray(),
			 beforeSend: function(){
				 $('#activation_code').attr('disabled',true);
				 $('#generate_card .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				if(info=='dne'){
				   $('.display-notice').html("<div class='text-danger bg-danger'>Invalid activation code! It does not exist!</div>")
				   }else{
				 $('.data-content-loaded .cards-list').empty();
				 $('.data-content-loaded .cards-list').html(info);
					}
				 $('#activation_code').attr('disabled',false);
				 $('#generate_card .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler
    })
});  
</script>
</html>