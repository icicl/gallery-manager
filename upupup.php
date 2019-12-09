<?php
session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){// make sure user is logged in
    header("location: login.php");
    exit;
}?>
<html>
  <head>
    <script src="dropzone.js"></script>
    <link rel="stylesheet" href="dropzone.css">
    <title>epic time #thankstrev</title>
  </head>
  <body>
    <p>drag n drop time</p>
    lets uplaod some files
    <form action="upload_mono.php" class="dropzone"></form>
    <a href="addnew.php">Process all unprocesed images</a>
    <br />
    <a href="admin.php">Admin Portal</a>
  </body>
</html>