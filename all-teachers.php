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

<title>All Teachers | School</title>
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
              <div class='category-content-heading'>All Teachers</div>
                
              <div class='data-input-action'>
								<div class='row'>
								<div class="col-md-10">
                    <form method='post' id='search_all_teachers_form' name='search_all_teachers_form' class=''>
                      <div class='search-all-teachers-info'></div><!--where alerts are displayed--> 
                      <div class='row'>
                        <div class='form-group col-md-6'>
                          <input class='form-control' class='search_all_teachers_keyword' id='search_all_teachers_keyword' name='search_all_teachers_keyword' placeholder='Name of class teacher'/>
                        </div><!--end column-->      
                        <div class='form-group col-md-2'>
                          <button class='btn btn-outline-primary form-control' type='submit' id='search_all_teachers' name='search_all_teachers'>Search
                          <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/>
                          </button>
                        </div><!--end column-->
                      </div><!--end row-->
                    </form>
                  </div><!--end col-->

                  <div class="col-md-2">
	  	              <a class="btn btn-md btn-outline-primary modal-action" id='add-all-teachers' data-toggle='modal' data-target='#modal'><span class='glyphicon glyphicon-plus'></span> Add Teachers</a>
									</div><!--end col-->
                </div><!--end row-->
              </div>
                
              <div class='data-content'>
                <div class='panel'>
                  <div class='panel-heading'>
                    <div class='panel-title'>All Available Teachers</div><!--panel-title-->
                  </div><!--panel-heading-->
                  <div class='panel-body'>
                    
                    <div class='data-content-loaded'>
                      <div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
											Create personal accounts for all teachers in the school.</div>
                      <div class='all-teachers-list table-responsive'>
                        
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
//loading profile pages based on the get url value on first loading
//send data to profile-page-load.php so as to produce the necessary content
$(document).ready(function(){
  //call load_grades_action()
  load_all_teachers_action()
  function load_all_teachers_action(){  
	  var page = 1;
		$.ajax({
		  type:'POST',
		  url:'php_load/all-teachers-action.php',
		  data:{
			page:page
			},
		  global:false,
		  beforeSend: function(){
			$('.all-teachers-list .preloader').attr('hidden',false);
			},
		  success:function(data){
			$('.data-content-loaded .all-teachers-list').html(data)	
			}				  
		 });//end ajax request
  }//end load_all_teachers_action()
})

//***when the user clicks on the number navigation buttons...***//
//it would load automatically without refreshing
$(document.body).on('click','.all-teachers-navigation .pagination_link',function(){
  var page = $(this).attr('id');		 
  $.ajax({
	type:'POST',
	url:'php_load/all-teachers-action.php',
	data:{
	  page:page
	  },
	global:false,
	beforeSend: function(){
	  $('.all-teachers-list .preloader').attr('hidden',false);
		},
	success:function(data){
		$('.all-teachers-list').html(data)
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


//validate add-all-teachers
$(document.body).on('click','form',function() {
	$("#add_all_teachers_form").validate({
	  rules:{
		   fullname:{
		     required:true,
			 maxlength:40
			  },
		   username:{
		     required:true,
			 maxlength:40
			  },
		   password:{
		     required:true
			  },
		   email:{
		     required:true,
			 email:true
			  }
	    },//end rules
	  messages:{
		   fullname:{
		     required:'Please enter a name!',
			 maxlength:'Name should be less than 40 characters'
			   },
		   username:{
		     required:'Please enter a username!',
			 maxlength:'Username should be less than 40 characters'
			   },
		   password:{
		     required:'Please enter a grade letter!'
			   },
		   email:{
		     required:'Please enter an email!',
			 email:'Please enter a valid email address!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/all-teachers-action.php',
			 type:'POST',
			 data:$('#add_all_teachers_form').serializeArray(),
			 beforeSend: function(){
				 $('.modal-body .preloader').attr('hidden',false);
				 },		
			 success: function(info){
				if(info=='ue'){
				   $('.add-all-teachers-info').html("<a class='text-danger bg-danger'>This username already exists!</a>")
				   }else
				if(info=='ee'){
						$('.add-all-teachers-info').html("<a class='text-danger bg-danger'>This email already exists!</a>")
					 }else
				if(info=='subject teacher exists'){
				   $('.add-all-teachers-info').html("<a class='text-danger bg-danger'>The subject already has a teacher for that class!</a>")
				   }else{
				 $('.data-content-loaded .all-teachers-list').empty();
				 $('.data-content-loaded .all-teachers-list').html(info);
				 $('.close').click();	
					 }				 
				 }
			 })
		 }//end submitHandler

    })
});  

//validate edit-all-teachers
$(document.body).on('click','#edit_all_teachers',function() {
  var edit_id = $(this).attr("data-edit-id");
  var page = $(this).attr("data-modal-page");
  
  $("#edit_all_teachers_form").validate({
	  rules:{
		   fullname:{
		     required:true,
			 maxlength:40
			  },
		   username:{
		     required:true,
			 maxlength:40
			  },
		   password:{
		     required:true
			  },
		   email:{
		     required:true,
			 email:true
			  }
	    },//end rules
	  messages:{
		   fullname:{
		     required:'Please enter a name!',
			 maxlength:'Name should be less than 40 characters'
			   },
		   username:{
		     required:'Please enter a username!',
			 maxlength:'Username should be less than 40 characters'
			   },
		   password:{
		     required:'Please enter a grade letter!'
			   },
		   email:{
		     required:'Please enter an email!',
			 email:'Please enter a valid email address!'
			   }
		},//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/all-teachers-action.php',
		   type:'POST',
		   data:$('#edit_all_teachers_form').serialize()+"&edit_id="+edit_id+"&page="+page,
		   beforeSend: function(){
			   $('.modal-body .preloader').attr('hidden',false);
			   },		
		   success: function(info){
				if(info=='ue'){
				   $('.add-all-teachers-info').html("<a class='text-danger bg-danger'>This username already exists!</a>")
				   }else
				if(info=='ee'){
						$('.add-all-teachers-info').html("<a class='text-danger bg-danger'>This email already exists!</a>")
					 }else
			   if(info=='subject teacher exists'){
				   $('.edit-all-teachers-info').html("<a class='text-danger bg-danger'>The subject already has a teacher for that class!</a>")
				   }else{
			   $('.data-content-loaded .all-teachers-list').empty();
			   $('.data-content-loaded .all-teachers-list').html(info);
			   $('.close').click();	
				   }				 
			   }
		   })
	   }//end submitHandler

  })
  
});  

//delete all_teachers entry
$(document).on('click',"#delete_all_teachers",function(e){
	e.preventDefault();
	var page = $(this).attr('data-modal-page');
	var delete_id = $(this).attr('data-delete-id');
	var delete_type = $(this).attr('data-delete-type');
    $.ajax({
	  url:'php_load/all-teachers-action.php',
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
		 $('.data-content-loaded .all-teachers-list').empty();
		 $('.data-content-loaded .all-teachers-list').html(info);
		 $('.close').click();	
		 }
    })
});  

/////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////search for a teacher//////////////////////////////

///search after inputing a keyword
$(document.body).on('click','#search_all_teachers',function() {
	var satk = $('#search_all_teachers_keyword').val();
	$("#search_all_teachers_form").validate({
	  rules:{
		   search_all_teachers_keyword:{
		     required:true
			  }
	    },//end rules
	  messages:{
		   search_all_teachers_keyword:{
		     required:'Please enter a name or registration number!'
			   }
		},//end messages
	  submitHandler:function(form){
		 $.ajax({
			 url:'php_load/search-all-teachers-action.php',
			 type:'POST',
			 data:$('#search_all_teachers_form').serializeArray(),
			 beforeSend: function(){
				 $('#search_all_teachers').attr('hidden',false);
				 },		
			 success: function(info){
				$('.all-teachers-list').attr('id',satk)
				$('.data-content-loaded .all-teachers-list').empty();	
				$('.data-content-loaded .all-teachers-list').html(info)	
				 }
			 })
		 }//end submitHandler

    })
});  
//navigation for search results//
$(document.body).on('click','.search-all-teachers-navigation .pagination_link',function(){
	var satk = $('.all-teachers-list').attr('id');
 	var page = $(this).attr('id');		 
  $.ajax({
	type:'POST',
	url:'php_load/search-all-teachers-action.php',
	data:{
	  page:page,
		search_all_teachers_keyword : satk
	  },
	global:false,
	beforeSend: function(){
	  $('.all-teachers-list .preloader').attr('hidden',false);
		},
	success:function(data){
		$('.all-teachers-list').html(data)
	 }
   });//end ajax request
})
//validate edit-search-students
$(document.body).on('click','#edit_search_all_teachers',function() {
	var satk = $('.all-teachers-list').attr('id');
  var edit_id = $(this).attr("data-edit-id");
  var page = $(this).attr("data-modal-page");
  
  $("#edit_search_all_teachers_form").validate({
	  rules:{
		   fullname:{
		     required:true,
			 maxlength:40
			  },
		   username:{
		     required:true,
			 maxlength:40
			  },
		   password:{
		     required:true
			  },
		   email:{
		     required:true,
			 email:true
			  }
	    },//end rules
	  messages:{
		   fullname:{
		     required:'Please enter a name!',
			 maxlength:'Name should be less than 40 characters'
			   },
		   username:{
		     required:'Please enter a username!',
			 maxlength:'Username should be less than 40 characters'
			   },
		   password:{
		     required:'Please enter a grade letter!'
			   },
		   email:{
		     required:'Please enter an email!',
			 email:'Please enter a valid email address!'
			   }
		},//end messages
	submitHandler:function(form){
	   $.ajax({
		   url:'php_load/search-all-teachers-action.php',
		   type:'POST',
		   data:$('#edit_search_all_teachers_form').serialize()+"&edit_id="+edit_id+"&page="+page+"&search_all_teachers_keyword="+satk,
		   beforeSend: function(){
			   $('.modal-body .preloader').attr('hidden',false);
			   },		
		   success: function(info){
				if(info=='ue'){
				   $('.edit-search-all-teachers-info').html("<a class='text-danger bg-danger'>This username already exists!</a>")
				   }else
				if(info=='ee'){
						$('.edit-search-all-teachers-info').html("<a class='text-danger bg-danger'>This email already exists!</a>")
					 }else
			   if(info=='subject teacher exists'){
				   $('.edit-search-all-teachers-info').html("<a class='text-danger bg-danger'>The subject already has a teacher for that class!</a>")
				   }else{
			   $('.data-content-loaded .all-teachers-list').empty();
			   $('.data-content-loaded .all-teachers-list').html(info);
			   $('.close').click();	
				   }				 
			   }
		   })
	   }//end submitHandler
  })  
});  

//delete search_all-teachers entry
$(document).on('click',"#delete_search_all_teachers",function(e){
	e.preventDefault();
	var satk = $('.all-teachers-list').attr('id');
	var page = $(this).attr('data-modal-page');
	var delete_id = $(this).attr('data-delete-id');
	var delete_type = $(this).attr('data-delete-type');
    $.ajax({
	  url:'php_load/search-all-teachers-action.php',
	  type:'POST',
	  data:{
		  page : page,
		delete_id : delete_id,
		delete_type : delete_type,
		search_all_teachers_keyword : satk
		  },
	  beforeSend: function(){
		 $('.modal-body .preloader').attr('hidden',false);
		 },		
	  success: function(info){				 
		 $('.data-content-loaded .all-teachers-list').empty();
		 $('.data-content-loaded .all-teachers-list').html(info);
		 $('.close').click();	
		 }
    })
});


</script>
</body>
</html>