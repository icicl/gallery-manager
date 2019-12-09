<?php
session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){// make sure user is logged in
    header("location: login.php");
    exit;
}?>

<a href="upupup.php">Upload new images</a>
<br />
<a href="addnew.php">Process all unprocesed images</a>
<br />
<a href="regenhtml.php">Regenerate the gallery file</a>
<br />
<a href="delete.php">Delete images from the server</a>
<br />
<a href="logout.php">Logout</a>
