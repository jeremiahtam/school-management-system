<?php 
include("./inc/session.inc.php"); 
include("./inc/db.inc.php");
include("./classes/develop-php-library.php"); 

//redirect if user is not logged in
if(!isset($loggedin_user)){
	header("Location:". base_url()."account");
}
//redirect if loggend in user is not admin
if($loggedin_user_type!=='student'){
 header("Location:". base_url()."account");
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="IE=9">
<base href="<?php echo base_url();?>" />

<title>Term Result | School</title>
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
              <div class='category-content-heading'>Term Result</div>
                
              <div class='data-input-action'>
                <form method='post' id='view_student_result_form' name='view_student_result_form'>
                <div class='view-student-result-info'></div><!--where alerts are displayed-->           
                <div class='row'>
                  <div class='form-group col-sm-3'>                     
                      <label for='pin_no'>Card Pin</label>
                      <input class='form-control' id='pin_no' name='pin_no' placeholder='Card Pin'/>
                  </div><!--end col-->
                  <div class='form-group col-sm-3'>                 
                      <label for='session' class='col-form-label'>Session</label>
                      <select class='form-control' id='session' name='session'>
                      <option value=''>-select session-</option>
                      <?php
                      $session_sql = mysqli_query($conn,"SELECT * FROM sessions WHERE removed='no' ");
                      while($session_sql_row = mysqli_fetch_assoc($session_sql)){
                          $alt_session_db = htmlentities($session_sql_row['alt_session']);
                          $session_db =htmlentities($session_sql_row['session']);
                          echo "<option value='$alt_session_db'>$session_db</option>";
                          }	   
                      ?>	  
                      </select>
                  </div><!--end col-->
                  <div class='form-group col-sm-3'>                     
                    <label for='term' class='col-form-label'>Term</label>
                    <select class='form-control' id='term' name='term'>
                      <option value=''>-select term-</option>";
                      <?php
                      $terms_sql = mysqli_query($conn,"SELECT * FROM terms WHERE removed='no' ");
                      while($terms_sql_row = mysqli_fetch_assoc($terms_sql)){
                          $term_db = htmlentities($terms_sql_row['term']);
                          $term_name_db = htmlentities($terms_sql_row['term_name']);
                          echo "<option value='$term_db'>$term_name_db</option>";
                          }	   
                      ?>	  
                    </select>
                  </div><!--end col-->
                  <div class='form-group col-sm-3'>
                      <button class='btn btn-outline-primary form-control' type='submit' id='view_student_result' name='view_student_result'>Get Result
                      <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                      </button>
                  </div><!--end col-->
                </div><!--end row-->
              </form>
              </div><!-- endinput-action -->
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>Result Display</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='table-responsive view-student-result-list' id="view-student-result-list">
                    
                      </div><!--end table responsive-->                    
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


//validate activation code
$(document.body).on('click','#view_student_result',function() {
	$("#view_student_result_form").validate({
	  rules:{
            pin_no:{
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
			pin_no:{
		    	required:'Please enter your card serial number!'
			   },
				 session:{
		    	required:'Please select a session!'
			   },
				 term:{
		    	required:'Please seleect a term!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/view-student-result.php',
			 type:'POST',
			 data:$('#view_student_result_form').serializeArray(),
			 beforeSend: function(){
				 $('#view_student_result').attr('disabled',true);
				 $('#view_student_result .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 $('.data-content-loaded .view-student-result-list').empty();
				 $('.data-content-loaded .view-student-result-list').html(info);
                 $('#view_student_result').attr('disabled',false);
                 $('#view_student_result .preloader').attr('hidden',true);
				 }
			 })
		 }//end submitHandler
    })
});  
</script>
</html>