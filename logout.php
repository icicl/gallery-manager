<?php
session_start();
$_SESSION = array();
session_destroy();// F
header("location: login.php");
exit;
?>