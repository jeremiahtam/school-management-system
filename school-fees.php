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

<title>School Fees | School</title>
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
              <div class='category-content-heading'>School Fees</div>
                
              <div class='data-input-action'>
                <div class=''>
                  <form method='post' id='search_school_fees_form' name='search_school_fees_form'>
                    <div class='search-school-fees-info'></div><!--where alerts are displayed--> 
                    <div class='row'>
                      <div class='form-group col-md-6'>
                      </div><!--end column-->
                      <div class='form-group col-md-4'>
                        <input class='form-control' id='search_reg_number' name='search_reg_number' placeholder='Insert Registration Number'/>
                      </div><!--end column-->      
                      <div class='form-group col-md-2'>
                        <button class='btn btn-outline-primary form-control' type='submit' id='search_school_fees' name='search_school_fees'>Search
                        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                        </button>
                      </div><!--end column-->
                    </div><!--end row-->
                  </form>
                </div><!--end div-->
              </div><!--end data-input-action-->
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>School Fees</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                      Find student's school fees history.
                      </div>
                      <div class='school-fees-list'>
                        
                      </div><!--end class-list-->                    
                    </div><!--end class-list-->                    
                  </div><!--end panel-body-->
                </div>
              </div><!--end row-->
              
            </div><!--category-content-->
        
            </div><!--end col-md-9-->
        </div><!--end row-->
        
	  </div><!--end container-->
	</section><!--end category-body-->
    
  </div><!--end container-fluid-->

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

//validate search school fees
$(document.body).on("click","#search_school_fees",function() {
	$("#search_school_fees_form").validate({
	  rules:{
	   search_reg_number:{
		 required:true,
		 maxlength:15
		 }
			},//end rules
	  messages:{
	   search_reg_number:{
		 required:"Please enter a registration number!",
		 maxlength:"Registration number should not be more than 15 characters"
		    }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/school-fees-action.php',
			 type:'POST',
			 data:$('#search_school_fees_form').serializeArray(),
			 beforeSend: function(){
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 $('.data-content-loaded .school-fees-list').empty();
				 $('.data-content-loaded .school-fees-list').html(info);
				 }				 
			 })
		 }//end submitHandler
    })
})


//opening modal and setting its purpose
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

//add school fees
$(document.body).on('click','#add_school_fees',function() {

	var reg_no = $('.school-fees-id').attr('id');
	
	$("#add_school_fees_form").validate({
		rules:{
			session:{
			  required:true
			},
			term:{
			  required:true
			},
			status:{
			  required:true
			}
		},//end rules
	  messages:{
			session:{
		 		required:"Please select a session!"
		  },
			term:{
		 		required:"Please select a term!"
		  },
			status:{
		 		required:"Please select a status!"
		  }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/school-fees-action.php',
			 type:'POST',
			 data:$('#add_school_fees_form').serialize()+"&reg_no="+reg_no,
			 beforeSend: function(){
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				 if(info=='error'){
						$('.add-school-fees-info').html("<a class='text-danger bg-danger'>This entry already exists!</a>")
					 }else{
				 		$('.data-content-loaded .school-fees-list .table-responsive').empty();
				 		$('.data-content-loaded .school-fees-list .table-responsive').html(info);
						 $('.close').click();	
					 }
				 }				 
			 })
		 }//end submitHandler
	})
});

//edit school fees
$(document.body).on('click','#edit_school_fees',function() {
  var edit_id = $(this).attr("data-edit-id") ;

  var reg_no = $('.school-fees-id').attr('id');

$("#edit_school_fees_form").validate({
	rules:{
		session:{
			required:true
		},
		term:{
			required:true
		},
		status:{
			required:true
		}
	},//end rules
	messages:{
		session:{
			 required:"Please select a session!"
		},
		term:{
			 required:"Please select a term!"
		},
		status:{
			 required:"Please select a status!"
		}
	},//end messages
	submitHandler:function(form){
	 $.ajax({
		 url:'php_load/school-fees-action.php',
		 type:'POST',
		 data:$('#edit_school_fees_form').serialize()+"&reg_no="+reg_no+"&edit_id="+edit_id,
		 beforeSend: function(){
			 $('.modal-body .preloader').attr('hidden',false);
			 },		
		 success: function(info){
			if(info=='error'){
						$('.edit-school-fees-info').html("<a class='text-danger bg-danger'>This entry already exists!</a>")
					 }else{
				 		$('.data-content-loaded .school-fees-list .table-responsive').empty();
				 		$('.data-content-loaded .school-fees-list .table-responsive').html(info);
						 $('.close').click();	
					 }
			 }				 
		 })
	 }//end submitHandler
  })
});

//delete school fees
$(document).on('click',"#delete_school_fees",function(e){
	e.preventDefault();
	var reg_no = $('.school-fees-id').attr('id');
	var delete_id = $(this).attr('data-delete-id');
	var delete_type = $(this).attr('data-delete-type');
    $.ajax({
		url:'php_load/school-fees-action.php',
	  type:'POST',
	  data:{
		  reg_no : reg_no,
		delete_id : delete_id,
		delete_type : delete_type   
		  },
	  beforeSend: function(){
		 $('.modal-body .btn').attr('disabled',true)
		 $('.modal-body .preloader').attr('hidden',false);
		 },		
	  success: function(info){
		 $('.data-content-loaded .school-fees-list .table-responsive').empty();
		 $('.data-content-loaded .school-fees-list .table-responsive').html(info);
		 $('.close').click();	
		 }
    })
});  

</script>
</body>
</html>