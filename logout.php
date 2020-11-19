<?php
include("./inc/db.inc.php");
session_start();
include("./inc/session.inc.php");
unset($_SESSION['loggedin_user']);
unset($_SESSION['loggedin_user_type']);
//session_destroy();
header("Location: home");
?>