
<nav class='navbar navbar-default navbar-fixed-top'>
  <div class='container-fluid'>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class='navbar-header'>
      <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
        <span class='sr-only'>Toggle navigation</span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </button>
      <a class='navbar-brand' href='<?php echo base_url();?>home'><img src='img/logo.png' alt='schhol badge'></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
      <ul class='nav navbar-nav navbar-right'>
	  <?php
      if(isset($_SESSION["loggedin_user"])){
        //if the user is admin
        if($_SESSION['loggedin_user_type']=='admin'){
          echo"
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Welcome Admin!<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='"; echo base_url(); echo"admin-dashboard'>Dashboard</a></li>
            <li><a href='"; echo base_url(); echo"classes'>Classes</a></li>
            <li><a href='"; echo base_url(); echo"subjects'>Subjects</a></li>
            <li><a href='"; echo base_url(); echo"grades'>Grades</a></li>
            <li><a href='"; echo base_url(); echo"all-teachers'>All Teachers</a></li>
            <li><a href='"; echo base_url(); echo"subject-teachers'>Subject Teachers</a></li>
            <li><a href='"; echo base_url(); echo"class-teachers'>Class Teachers</a></li>
            <li><a href='"; echo base_url(); echo"students'>Students</a></li>
            <li><a href='"; echo base_url(); echo"school-fees'>School Fees</a></li>
            <li><a href='"; echo base_url(); echo"lock-result'>Lock Result</a></li>
            <li><a href='"; echo base_url(); echo"generate-cards'>Generate Cards</a></li>
            <li><a href='"; echo base_url(); echo"card-status'>Card Status</a></li>
            <li><a href='"; echo base_url(); echo"settings'>Settings</a></li>
            <li><a href='"; echo base_url(); echo"logout'><span class='ion-log-out'></span> Logout!</a></li>
          </ul>
        </li>";
            }
        //if the user is class-teacher
        if($_SESSION['loggedin_user_type']=='class-teacher'){
          //get class teacher full name
          $class_teacher_sql = mysqli_query($conn,"SELECT * FROM all_teachers WHERE username='$loggedin_user' AND removed='no'");
          $class_teacher_row = mysqli_fetch_assoc($class_teacher_sql);
          $class_teacher_name = $class_teacher_row['fullname'];
          echo"
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Welcome $class_teacher_name<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='"; echo base_url(); echo"subject-result'>Subject Result</a></li>
            <li><a href='"; echo base_url(); echo"rank'>Rank</a></li>
            <li><a href='"; echo base_url(); echo"logout'><span class='ion-log-out'></span> Logout!</a></li>
          </ul>
        </li>";
            }
        //if the user is student
        if($_SESSION['loggedin_user_type']=='student'){
          $student_details = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$loggedin_user' AND removed='no' ");
          $student_details_row = mysqli_fetch_assoc($student_details);
          $student_fullname = $student_details_row['fullname'];
        echo"
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Welcome $student_fullname<span class='caret'></span></a>
          <ul class='dropdown-menu'>
          <li><a href='"; echo base_url(); echo"student-result'>Student Result</a></li>
          <li><a href='"; echo base_url(); echo"logout'><span class='ion-log-out'></span> Logout!</a></li>
          </ul>
        </li>";
            }
          }else{
		  echo"
        <li class='dropdown'>
          <a href='"; echo base_url(); echo"account'><span class='ion-log-out'></span> Login</a>
        </li>";
		}
      ?>


      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
