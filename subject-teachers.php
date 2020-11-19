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

<title>Subject Teachers | School</title>
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
              <div class='category-content-heading'>Subject Teachers</div>
                
              <div class='data-input-action'>
                <a class="btn btn-md btn-outline-primary modal-action" id='add-subject-teachers' data-toggle='modal' data-target='#modal'><span class='glyphicon glyphicon-plus'></span> Add Subject Teacher</a>
              </div>
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>All Available Subject Teachers</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
											Select from the registered teachers to make a subject teacher.
											</div>
                      <div class='subject-teachers-list table-responsive'>
                        
                      </div><!--end class-list-->                    
                    </div><!--end class-list-->                    
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
  //call load_subject_teachers_action()
  load_subject_teachers_action()
  function load_subject_teachers_action(){  
	  var page = 1;
		$.ajax({
		  type:'POST',
		  url:'php_load/subject-teachers-action.php',
		  data:{
			page:page
			},
		  global:false,
		  beforeSend: function(){
			$('.subject-teachers-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.data-content-loaded .subject-teachers-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_subject_teachers_action()
})

//***when the user clicks on the number navigation buttons...***//
//it would load automatically without refreshing
$(document.body).on('click','.subject-teachers-navigation .pagination_link',function(){
  var page = $(this).attr('id');		 
  $.ajax({
	type:'POST',
	url:'php_load/subject-teachers-action.php',
	data:{
	  page:page
	  },
	global:false,
	beforeSend: function(){
	  $('.subject-teachers-list .preloader').attr('hidden',false);
		},
	success:function(data){
		$('.subject-teachers-list').html(data)
	 }
   });//end ajax request
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


//validate add-subject-teachers
$(document.body).on('click','form',function() {
	$("#add_subject_teachers_form").validate({
	  rules:{
		   teacher_id:{
		     required:true,
			 maxlength:40
			  },
		   class_id:{
		     required:true
			  },
		   subject_id:{
		     required:true
			  }
	    },//end rules
	  messages:{
		   teacher_id:{
		     required:'Please select a name!',
			 maxlength:'Name should be less than 40 characters'
			   },
		   class_id:{
		     required:'Please select a class!'
			   },
		   subject_id:{
		     required:'Please select a subject!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/subject-teachers-action.php',
			 type:'POST',
			 data:$('#add_subject_teachers_form').serializeArray(),
			 beforeSend: function(){
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
			   if(info=='subject teacher exists'){
				   $('.add-subject-teachers-info').html("<a class='text-danger bg-danger'>The subject already has a teacher for that class!</a>")
				   }else{
				 $('.data-content-loaded .subject-teachers-list').empty();
				 $('.data-content-loaded .subject-teachers-list').html(info);
				 $('.close').click();	
					 }				 
				 }
			 })
		 }//end submitHandler

    })
});  

//validate edit-all-teachers
$(document.body).on('click','#edit_subject_teachers',function() {
  var edit_id = $(this).attr("data-edit-id");
  var page = $(this).attr("data-modal-page");
  
  $("#edit_subject_teachers_form").validate({
	  rules:{
		   teacher_id:{
		     required:true,
			 maxlength:40
			  },
		   class_id:{
		     required:true
			  },
		   subject_id:{
		     required:true
			  }
	    },//end rules
	  messages:{
		   teacher_id:{
		     required:'Please select a name!',
			 maxlength:'Name should be less than 40 characters'
			   },
		   class_id:{
		     required:'Please select a class!'
			   },
		   subject_id:{
		     required:'Please select a subject!'
			   }
		},//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/subject-teachers-action.php',
		   type:'POST',
		   data:$('#edit_subject_teachers_form').serialize()+"&edit_id="+edit_id+"&page="+page,
		   beforeSend: function(){
			   $('.modal-body .preloader').attr('hidden',false);
			   },		
		   success: function(info){
			   if(info=='subject teacher exists'){
				   $('.edit-subject-teachers-info').html("<a class='text-danger bg-danger'>The subject already has a teacher for that class!</a>")
				   }else{
				 $('.data-content-loaded .subject-teachers-list').empty();
				 $('.data-content-loaded .subject-teachers-list').html(info);
				 $('.close').click();	
				   }				 
			   }
		   })
	   }//end submitHandler

  })
  
});  

//delete class entry
$(document).on('click',"#delete_subject_teachers",function(e){
	e.preventDefault();
	var page = $(this).attr('data-modal-page');
	var delete_id = $(this).attr('data-delete-id');
	var delete_type = $(this).attr('data-delete-type');
    $.ajax({
	  url:'php_load/subject-teachers-action.php',
	  type:'POST',
	  data:{
		  page : page,
		delete_id : delete_id,
		delete_type : delete_type   
		  },
	  beforeSend: function(){
		 $('.modal-body .preloader').attr('hidden',false);
		 },		
	  success: function(info){				 
		 $('.data-content-loaded .subject-teachers-list').empty();
		 $('.data-content-loaded .subject-teachers-list').html(info);
		 $('.close').click();	
		 }
    })
});  



</script>
</html>