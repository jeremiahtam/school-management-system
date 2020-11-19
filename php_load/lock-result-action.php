<?php   
//set the number of output per page
$record_per_page = 30;
//$page = '';
//$output = '';
if(isset($_POST["page"]))
{
	$page = $_POST['page'];
	}
	else
	{
		$page = 1;
		}
	$start_from = ($page - 1) * $record_per_page;

//check to see which action to carry out: load, lock or unlock
switch(true){
	//execute this only if the 
	case isset($_POST['lock_id'])&&isset($_POST['lock_type']):

	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
    include("../inc/db.inc.php");

    $lock_id = htmlentities($_POST['lock_id']);
    $lock_type = htmlentities($_POST['lock_type']);
    $date= date('Y-m-d');
    $time= date('H:i:s');

    //if lock_type is lock
    if($lock_type=='lock'){
        //update locked to yes
        $update_sql = mysqli_query($conn,"UPDATE lock_result SET locked='yes',date='$date',time='$time' WHERE id='$lock_id' ");
        //echo unlock button
        echo"<button class='btn btn-sm btn-danger' id='$lock_id' data-lock-type='unlock'>
        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/> Unlock</button>";
}
    //if lock_type is unlock
    if($lock_type=='unlock'){
        //update locked to no
        $update_sql = mysqli_query($conn,"UPDATE lock_result SET locked='no',date='$date',time='$time' WHERE id='$lock_id' ");
        //echo lock bubbon
        echo"<button class='btn btn-sm btn-outline-success' id='$lock_id' data-lock-type='lock'>
        <img src='img/ajax-loader.gif' class='preloader' width='22px' height='22px' hidden='true'/> Lock</button>";
    }
    
 
	break;

    default :
	include("../classes/develop-php-library.php");
	include("../inc/session.inc.php");
	include("../inc/db.inc.php");
    //call the display lock function
    display_lock_result($page,$start_from,$record_per_page,$conn);
	break;

}

?>