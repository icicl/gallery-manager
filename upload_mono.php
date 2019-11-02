<?php
session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location: login.php");
    exit;
} else {
if (!empty($_FILES)) { 
    move_uploaded_file($_FILES['file']['tmp_name'] ,"uploads/".$_FILES['file']['name']);

}
}
?>

