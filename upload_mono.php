<?php
session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){// make sure user is logged in
    header("location: login.php");
    exit;
} else {
    if (!empty($_FILES)) { 
        if (!file_exists('uploads/')){
            mkdir('uploads/');
        }
        move_uploaded_file($_FILES['file']['tmp_name'] ,"uploads/".$_FILES['file']['name']);
    }
}
?>

