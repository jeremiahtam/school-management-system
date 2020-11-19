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
<base href="<?php echo base_url();?>/" />

<title>Home Panel | School</title>
<link rel="icon" href="img/logo.png">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/ionicons.min.css" rel="stylesheet">
<link href="fonts/flaticon1/flaticon.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href='css/croppie.css' rel='stylesheet'>
<link href="css/style.css" rel="stylesheet">
<link href='css/datepicker.css' rel='stylesheet'>
</head>

<body>
  <div class="custom-container">
    <?php include("./inc/menu.inc.php");?>
      
    <div class='modal fade' id='modal' tabindex='-1' role='dialog'>
       <div class='modal-dialog' role='document'>  
      </div><!--modal-dialog-->
    </div><!--end modal-->
    

	<section class='category-body'>
	  <div class='container-fluid'>

		<div class='row'>
          
          <div class='col-md-3'>
              
          <?php include("./inc/side-menu.inc.php");?>
          
          </div><!--end col-md-3-->
          
          <div class="col-md-9">
              
            <div class="category-content">
              <div class="category-content-heading">Home Panel</div>
							<?php 
							//check if the the resumption is already past
								$query = mysqli_query($conn,"SELECT * FROM resumption LIMIT 1");	
								$row = mysqli_fetch_assoc($query);
                  $resumption_date = htmlentities($row['resumption_date']);
                  $date= date('Y-m-d');
                  if($resumption_date<$date){
                    echo"<div class='alert alert-danger'>Please go to <strong><a href='settings' class='text-warning'>settings</a></strong> and make sure the next resumtion date is properly set!</div>";
                  }
									?>

              <div class="row">
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail dash-box">
                    <div class="caption">
                      <h3>Resumption Date</h3>
                      <p>
						            <?php echo $resumption_date; ?>                      
                      </p>
                    </div>
                  </div><!--end thumbnail-->
                </div><!--end col-->

                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail dash-box">
                    <div class="caption">
                      <h3>Registered Students</h3>
                      <p>
						<?php
						$students = mysqli_query($conn,"SELECT * FROM students WHERE removed='no'");
						$students_row = mysqli_num_rows($students);
						if($students_row>0){
						  echo $students_row;
						}else{
						  echo '0';
						}						
                        ?>                      
                      </p>
                    </div>
                  </div><!--end thumbnail-->
                </div><!--end col-->
                  
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail dash-box">
                    <div class="caption">
                      <h3>Class Teachers</h3>
                      <p>
						<?php
						$class_teachers = mysqli_query($conn,"SELECT * FROM class_teachers WHERE removed='no'");
						$class_teachers_row = mysqli_num_rows($class_teachers);
						if($class_teachers_row>0){
						  echo $class_teachers_row;
						}else{
						  echo '0';
						}
						
                        ?>
                      </p>
                    </div>
                  </div><!--end thumbnail-->
                </div><!--end col-->
                  
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail dash-box">
                    <div class="caption">
                      <h3>Subjects</h3>
                      <p>
						<?php
						$subject_list = mysqli_query($conn,"SELECT * FROM subjects WHERE removed='no'");
						$subject_list_row = mysqli_num_rows($subject_list);
						if($subject_list_row>0){
						  echo $subject_list_row;
						}else{
						  echo '0';
						}						
                        ?>
                      </p>
                    </div>
                  </div><!--end thumbnail-->
                </div><!--end col-->
                  
              </div><!--end row-->

            </div><!--category-content-->
        
            </div><!--end col-md-9-->
        </div><!--end row-->
        
	  </div><!--end container-->
	</section><!--end profile-body-->
    
  </div><!--end container-fluid-->

<!--all javascript and jquery plugin-->
<script src="js/jquery-ui.js"></script>
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/croppie.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/ajax-script.js"></script>

</body>
</html>