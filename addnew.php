<?php
ini_set('memory_limit', '1024M');//IMPORTANT!!!! If you get memory exhausted error when using this page, just boost this number even more.
// ALSO IMPORTANT this file does NOT have security checks in place to make sure input is not malicious because this backend is private and password protected
session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){// make sure user is logged in
  header("location: login.php");
  exit;
}

function createThumbs( $pathToImages , $year , $alias)
{
  if (!file_exists('images/')){mkdir('images/');}
  if (!file_exists('imgAliases/')){mkdir('imgAliases/');}
  $year .= '/';
    $titles = date("Y").' '.date('M').' '.date('j');
    if (file_exists('imgAliases/'.$year.$titles)){
      $titlesuffix=1;
      while (file_exists('imgAliases/'.$year.$titles.'_'.$titlesuffix)){
        $titlesuffix+=1;
      }
      $titles .= '_'.$titlesuffix;
    }
    if ($alias === ''){$alias = date("M").' '.date('j').' '.date('Y');};//if no label for phots is given use current date, might have timezone issues and be off by a day or tqwo, but thats just how dateetime rolls
    $moveTo = 'images/'.$titles.'/';
    $pathToThumbs = 'images/'.$titles.'_thumbs/';//thumbnails with 8:5 ratio
    $pathToThumbsSq = 'images/'.$titles.'_thumbs_square/';//thumbs with 1:1 aspect ratio. both are generated so if an image is removed later regen_html.php will not break
    if (!file_exists($moveTo)){mkdir($moveTo);}
    if (!file_exists($pathToThumbs)){mkdir($pathToThumbs);}
    if (!file_exists($pathToThumbsSq)){mkdir($pathToThumbsSq);}
    if (!file_exists('imgAliases/'.$year)){mkdir('imgAliases/'.$year);}
    $f = fopen('imgAliases/'.$year.$titles,'w');
fwrite($f,$alias);// this maps the label, aka what you decided to title a specific photoset, to the year/date directory
fclose($f);


  $dir = opendir( $pathToImages );
  $exts = array("jpg","jpeg","png");
  while (false !== ($fname = readdir($dir))) {//loops through contents of $dir
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is an image
    if (in_array(strtolower($info['extension']),$exts))
    {
      echo "Creating thumbnail for {$fname} <br />";

      // load image and get image size
      if (strtolower($info['extension'])=="png"){//If other extensions get added make sure to put them in this bit here
        $img = imagecreatefromjpeg("{$pathToImages}{$fname}");//loads jpegs
      } else {
        $img = imagecreatefromjpeg("{$pathToImages}{$fname}");//loads pngs
      }
      if (!$img){//if image didnt load right
        echo "Error while loading {$fname} <br />";
      } else {
        $width = imagesx( $img );
        $height = imagesy( $img );
        $x = 0;//8:5 aspect ratio
        $y = 0;
        $xadd = 0;
        $yadd = 0;
        $xs = 0;//square aspect ratio, s means square
        $ys = 0;
        $xadds = 0;
        $yadds = 0;
        if ($width*5 === $height*8){//these are cases to determine the cropping boundaries for the images
          $xadd = $width;
          $yadd = $height;
        } else if ($width*5 >= $height*8){
          $x = floor($width/2 - (4*$height/5));
          $xadd = floor(8*$height/5);
          $yadd = $height;
        } else {
          $y = floor($height/2 - (5*$width/16));
          $yadd = floor(5*$width/8);
          $xadd = $width;
        }
        if ($width === $height){
          $xadds = $width;
          $yadds = $height;
        } else if ($width >= $height){
          $xs = floor($width/2 - ($height/2));
          $xadds = $height;
          $yadds = $height;
        } else {
          $ys = floor($height/2 - ($width/2));
          $yadds = $width;
          $xadds = $width;
        }

        $im = imagecrop($img, ['x' => $x, 'y' => $y, 'width' => $x+$xadd, 'height' => $y+$yadd]);//8:5
        $ims = imagecrop($img, ['x' => $xs, 'y' => $ys, 'width' => $xs+$xadds, 'height' => $ys+$yadds]);//square

        $tmp_img = imagecreatetruecolor( 640, 400 );// create a new image container with right dimensions
        imagecopyresized( $tmp_img, $im, 0, 0, 0, 0, 640, 400, $xadd, $yadd );// copy cropped image into new image container
        imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );// save thumbnail into a file
        $tmp_img = imagecreatetruecolor( 400, 400 );// create a new image container with right dimensions
        imagecopyresized( $tmp_img, $ims, 0, 0, 0, 0, 400, 400, $xadds, $yadds );// copy cropped image into new image container
        imagejpeg( $tmp_img, "{$pathToThumbsSq}{$fname}" );// save thumbnail into a file

        rename($pathToImages.$fname,$moveTo.$fname);//'moves' file by renaming its path
      }
    }
  }
  closedir( $dir );// close the directory
  echo "SUCCESSFULLY PROCESSED";//i hope so
}
// createThumbs takes the path to images, the year of those images (2020 will be used if empty string), and the alias (what that group of photos should be labeled as)


if($_SERVER["REQUEST_METHOD"] == "POST"){
  $year = "2020";//defaults to 2020
  if(!empty(trim($_POST["year"]))){
      $year = trim($_POST["year"]);
  }
  $alias = "";
  if(!empty(trim($_POST["alias"]))){
      $alias = trim($_POST["alias"]);
  }

createThumbs("uploads/",$year,$alias);}

//this code is Spaghetti Certified
?>
<form action="" method="post">
  <div>
    <label>Year</label>
    <input type="text" name="year" value="2020">
  </div>    
  <div>
    <label>Title / time span</label>
    <input type="text" name="alias" value="">
  </div>    
  <div class="form-group">
    <input type="submit" class="btn" value="Confirm">
  </div>
</form>
<br />
takes all of the newly uploaded images out of purgatory and generates thumbnails for them.<br />
<a href="regenhtml.php">regen new html for the gallery page</a>
<br />
<a href="admin.php">Admin Portal</a>
