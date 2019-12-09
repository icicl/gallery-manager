<?php
session_start();
 
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){// make sure user is logged in
  header("location: login.php");
  exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["files"]) && !empty(trim($_POST["files"]))){
        foreach(explode(",http",trim($_POST["files"])) as $file_){
            $file = preg_replace("/.*\/images\//",'',urldecode($file_));
            unlink('images/'.$file);
            unlink('images/'.str_replace('/','_thumbs/',$file));
            unlink('images/'.str_replace('/','_thumbs_square/',$file));
        }
    }
    $year = '2020';//default to 2020
    if(isset($_POST["year"]) && !empty(trim($_POST["year"]))){
        $year = trim($_POST["year"]);
    }
$html = file_get_contents('start_del.html');//this is the html "code" that will be written to the file. start.html contains some one time stuff like <head> and css
$cycle = ["gallery_thin","gallery_wide","gallery_thin","gallery_wide","gallery_thin","gallery_wide","gallery_wide","gallery_wide","gallery_wide"];// css cycle
$cycle2 = ["_thumbs_square","_thumbs","_thumbs_square","_thumbs","_thumbs_square","_thumbs","_thumbs","_thumbs","_thumbs"];// filename cycle. these cycles give the page the square/8:5 structure
$c = 0;//cycle index

if ($handle = opendir('imgAliases/'.$year)) {
    while (false !== ($entry = readdir($handle))) {
        if (!is_dir('imgAliases/'.$entry) && $entry != '.DS_Store') {//ds store because i have a mac and that file always gets generated and cant be removed
            $c = 0;
            $html .= "\n<div class = \"gallery_label\">".file_get_contents('imgAliases/'.$year.'/'.$entry)."</div>\n";
            if ($handle2 = opendir('images/'.$entry)) {
                while (false !== ($entry2 = readdir($handle2))) {
                    if (!is_dir('images/'.$entry2) && $entry2 != '.DS_Store') {
                        $html .= "<div onclick=\"select_(this)\" class=\"".$cycle[$c]."\"><img class=\"unselected\" src=\"images/".$entry.$cycle2[$c]."/".$entry2."\"></div>\n";
                        $c = ($c+1)%count($cycle);
                        if($c==0||$c==5){
                            $html .= "\n<div class = \"gallery_label\"></div>";
                        }
                    }
                }
                closedir($handle2);
            }
        }
    }
    closedir($handle);
}


$html .= file_get_contents('end_del.html');
echo $html;} else {echo "


<form action=\"\" method=\"post\">
    <div>
        <label>Year</label>
        <input type=\"text\" name=\"year\" value=\"2020\">
    </div>    
    <div class=\"form-group\">
        <input type=\"submit\" class=\"btn\" value=\"Confirm\">
    </div>
</form>
<br />

<br />
<a href=\"admin.php\">Admin Portal</a>
<br />";}; ?>

