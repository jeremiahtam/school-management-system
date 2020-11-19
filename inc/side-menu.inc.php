<?php

//security measure
//only allow inclusion of this file if a user is logged in
if(isset($loggedin_user)){
?>
<div class="panel category-panel">
  <div class="panel-heading category-panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
      <a>Menu</a>
      <span role="button" class="ion-android-menu pull-right " data-toggle="collapse" data-parent="#accordion" href="#collapseOuterPanel" aria-expanded="true" aria-controls="collapseOne">
      </span>
    </h4>
  </div><!--end panel-heading-->
    
    
    
  <div id="collapseOuterPanel" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      
    <div class="panel-body category-panel-body">
    </div><!--end panel-body-->          
      
    <div class="panel-group inner-panel-group" id="accordion" role="tablist" aria-multiselectable="true">                     

      <div class="list-group">
      <?php
      //Get the basename of the current page
      $title=basename($_SERVER['SCRIPT_FILENAME'],'.php');?>
      
  <?php if($loggedin_user_type=='admin'){?>

        <a href="<?php echo base_url();?>admin-dashboard" class="list-group-item <?php if($title=='admin-dashboard'){echo "active";}?>"><span class="ion-ios-home"></span> Home</a>
        <a href="<?php echo base_url();?>classes" class="list-group-item <?php if($title=='classes'){echo "active";}?>"><span class="ion-android-list"></span> Classes</a>
        <a href="<?php echo base_url();?>subjects" class="list-group-item <?php if($title=='subjects'){echo "active";}?>"><span class="ion-ios-browsers-outline"></span> Subjects</a>
        <a href="<?php echo base_url();?>grades" class="list-group-item <?php if($title=='grades'){echo "active";}?>"><span class="ion-android-star-half"></span> Grades</a>
        <a href="<?php echo base_url();?>all-teachers" class="list-group-item <?php if($title=='all-teachers'){echo "active";}?>"><span class="ion-ios-people-outline"></span> All Teachers</a>
        <a href="<?php echo base_url();?>subject-teachers" class="list-group-item <?php if($title=='subject-teachers'){echo "active";}?>"><span class="ion-ios-personadd"></span> Subject Teachers</a>
        <a href="<?php echo base_url();?>class-teachers" class="list-group-item <?php if($title=='class-teachers'){echo "active";}?>"><span class="ion-man"></span> Class Teachers</a>
        <a href="<?php echo base_url();?>students" class="list-group-item <?php if($title=='students'){echo "active";}?>"><span class="ion-person-stalker"></span> Students</a>
        <a href="<?php echo base_url();?>school-fees" class="list-group-item <?php if($title=='school-fees'){echo "active";}?>"><span class="ion-card"></span> School Fees</a>
        <a href="<?php echo base_url();?>lock-result" class="list-group-item <?php if($title=='lock-result'){echo "active";}?>"><span class="ion-ios-locked-outline"></span> Lock Result</a>
        <a href="<?php echo base_url();?>generate-cards" class="list-group-item <?php if($title=='generate-cards'){echo "active";}?>"><span class="ion-ios-cart-outline"></span> Generate Cards</a>
        <a href="<?php echo base_url();?>card-status" class="list-group-item <?php if($title=='card-status'){echo "active";}?>"><span class="ion-ios-pulse-strong"></span> Card Status</a>
        <a href="<?php echo base_url();?>settings" class="list-group-item <?php if($title=='settings'){echo "active";}?>"><span class="ion-android-options"></span> Settings</a>
        
      <?php }?>

  <?php if($loggedin_user_type=='class-teacher'){?>
      <a href="<?php echo base_url();?>subject-result" class="list-group-item <?php if($title=='subject-result'){echo "active";}?>"><span class="ion-android-clipboard"></span> Subject Result</a>
      <a href="<?php echo base_url();?>rank" class="list-group-item <?php if($title=='rank'){echo "active";}?>"><span class="glyphicon glyphicon-stats"></span> Rank</a>
      <?php }?>

  <?php if($loggedin_user_type=='student'){?>
        <a href="<?php echo base_url();?>student-result" class="list-group-item <?php if($title=='student-result'){echo "active";}?>"><span class="ion-android-clipboard"></span> Term Result</a>
      <?php }?>

      </div><!--end list-group-->
        
    </div><!--end inner-panel-group-->                                
  </div><!--end category-panel-collapse-->
    
</div><!--end category-panel-->         

<?php
//security measure
//only allow inclusion of this file if a user is logged in
  }//end if tag
?>
