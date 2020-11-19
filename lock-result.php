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

<title>Lock Result | School</title>
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
              <div class='category-content-heading'>Lock Result</div>

              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Results available</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						Lock result to prevent class teachers from editing.
					  </div>
                      <div class='lock-result-list table-responsive'>
                        
                      </div><!--end lock-result-list-->                    
                    </div><!--end lock-result-list-->                    
                  </div><!--end panel-body-->
                </div>
              </div><!--end row-->
              
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
<!--<script src="js/ajax-script.js"></script>-->
<script type="text/javascript">

$(document).ready(function(){
  //call load_lock_result_action()
  load_lock_result_action()
  function load_lock_result_action(){  
	  var page = 1;
		$.ajax({
		  type:'POST',
		  url:'php_load/lock-result-action.php',
		  data:{
			page:page
			},
		  global:false,
		  beforeSend: function(){
			$('.lock-result-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.data-content-loaded .lock-result-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_lock_result_action()
})

//***when the user clicks on the number navigation buttons...***//
//it would load automatically without refreshing
$(document.body).on('click','.lock-result-navigation .pagination_link',function(){
  var page = $(this).attr('id');		 
  $.ajax({
	type:'POST',
	url:'php_load/lock-result-action.php',
	data:{
	  page:page
	  },
	global:false,
	beforeSend: function(){
	  $('.lock-result-list .preloader').attr('hidden',false);
		},
	success:function(data){
		$('.lock-result-list').html(data)
	 }
   });//end ajax request
})

//lock result entry
$(document).on('click',".lock_action_holder .btn",function(e){
	e.preventDefault();
	var lock_id = $(this).attr('id');
	var lock_type = $(this).attr('data-lock-type');
  $.ajax({
	  url:'php_load/lock-result-action.php',
	  type:'POST',
	  data:{
		lock_id : lock_id,
		lock_type : lock_type
		  },
	  beforeSend: function(){
		 $('.preloader',this).attr('hidden',false);
		 },		
	  success: function(info){				 
		 $('#'+'lock_action_holder_'+lock_id).html(info);
		 }
    })
});  



</script>
</html>