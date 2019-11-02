<?php
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
  header("location: login.php");
  exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $year = '2020';
    if(!empty(trim($_POST["year"]))){
        $year = trim($_POST["year"]);
    }
$html = file_get_contents('start.html');
$imgslist = "var imgs = [";
$imgslistnospace = "var imgsns = [";
$cycle = ["gallery_thin","gallery_wide","gallery_thin","gallery_wide","gallery_thin","gallery_wide","gallery_wide","gallery_wide","gallery_wide"];#css cycle
$cycle2 = ["_thumbs_square","_thumbs","_thumbs_square","_thumbs","_thumbs_square","_thumbs","_thumbs","_thumbs","_thumbs"];
$c = 0;

if ($handle = opendir('imgAliases/'.$year)) {
    while (false !== ($entry = readdir($handle))) {
        if (!is_dir('imgAliases/'.$entry) && $entry != '.DS_Store') {
            $c = 0;
            $html .= "\n<div class = \"gallery_label\">".file_get_contents('imgAliases/'.$year.'/'.$entry)."</div>\n";
//
            if ($handle2 = opendir('images/'.$entry)) {
                while (false !== ($entry2 = readdir($handle2))) {
                    if (!is_dir('images/'.$entry2) && $entry2 != '.DS_Store') {
                        $html .= "<div onclick=\"mediashow('".$entry."/".$entry2."')\" class=\"".$cycle[$c]."\"><img src=\"images/".$entry.$cycle2[$c]."/".$entry2."\"></div>\n";
                        $imgslistnospace .= str_replace(' ','%20',"\"".$entry.'/'.$entry2.'",');
                        $imgslist .= "\"".$entry.'/'.$entry2.'",';
                        $c = ($c+1)%count($cycle);
                                
                    }
                }
                closedir($handle2);
            }
//
        }
    }
    closedir($handle);
}
/*
if ($handle = opendir('images/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && is_dir('images/'.$entry) && substr($entry,strlen($entry)-5,1) == ' ') {
            $c = 0;
            $html .= "\n<div class = \"gallery_label\">".$entry."</div>\n";
//
            if ($handle2 = opendir('images/'.$entry)) {
                while (false !== ($entry2 = readdir($handle2))) {
                    if (!is_dir('images/'.$entry2) && $entry2 != '.DS_Store') {
                        $html .= "<div onclick=\"mediashow('".$entry."/".$entry2."')\" class=\"".$cycle[$c]."\"><img src=\"images/".$entry.$cycle2[$c]."/".$entry2."\"></div>\n";
                        $imgslistnospace .= str_replace(' ','%20',"\"".$entry.'/'.$entry2.'",');
                        $imgslist .= "\"".$entry.'/'.$entry2.'",';
                        $c = ($c+1)%count($cycle);
                                
                    }
                }
                closedir($handle2);
            }
//
        }
    }
    closedir($handle);
}
*/

$html .= "<script>\nvar path = \"images/\";//var path = \"gallery".$year."/\";\n".substr($imgslist,0,strlen($imgslist)-1)."];\n".substr($imgslistnospace,0,strlen($imgslistnospace)-1)."];\n";
$html .= file_get_contents('end.html');
$f = fopen('gallery'.$year.'.html','w');
fwrite($f,$html);
fclose($f);
echo "Mission passed, <br /><a href=\"gallery".$year.".html\">View gallery</a>";}
?> 

<form action="" method="post">
            <div>
                <label>Year</label>
                <input type="text" name="year" value="2020">
            </div>    

            <div class="form-group">
                <input type="submit" class="btn" value="Confirm">
            </div>
        </form>

<br />regenerates the html file for the gallery



<br />
<a href="admin.php">Admin Portal</a>
<br />

