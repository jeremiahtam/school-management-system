<?php 
include("../inc/session.inc.php");
include("../inc/db.inc.php");
include("../classes/develop-php-library.php");
// include autoloader

   $record_per_page = 24;
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
	display_cards($conn,$page);
?>