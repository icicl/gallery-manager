<?php
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
  header("location: login.php");
  exit;
}

function createThumbs( $pathToImages )
{
    $titles = date("M").' '.date('j').' '.date('Y');
    $moveTo = 'images/'.$titles.'/';
    $pathToThumbs = 'images/'.$titles.'_thumbs/';
    $pathToThumbsSq = 'images/'.$titles.'_thumbs_square/';
    if (!file_exists($moveTo)){mkdir($moveTo);}
    if (!file_exists($pathToThumbs)){mkdir($pathToThumbs);}
    if (!file_exists($pathToThumbsSq)){mkdir($pathToThumbsSq);}

  // open the directory
  $dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  $exts = array("jpg","jpeg","png");
  while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is an image
    if ( in_array(strtolower($info['extension']),$exts))
    {
      echo "Creating thumbnail for {$fname} <br />";

      // load image and get image size
      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      $width = imagesx( $img );
      $height = imagesy( $img );
      $x = 0;//320x200
      $y = 0;
      $xadd = 0;
      $yadd = 0;
      $xs = 0;//200x200
      $ys = 0;
      $xadds = 0;
      $yadds = 0;
      if ($width*5 === $height*8){
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


    $im = imagecrop($img, ['x' => $x, 'y' => $y, 'width' => $x+$xadd, 'height' => $y+$yadd]);
    $ims = imagecrop($img, ['x' => $xs, 'y' => $ys, 'width' => $xs+$xadds, 'height' => $ys+$yadds]);
    // create a new temporary image
      $tmp_img = imagecreatetruecolor( 320, 200 );

      // copy and resize old image into new image
      imagecopyresized( $tmp_img, $im, 0, 0, 0, 0, 320, 200, $xadd, $yadd );

      // save thumbnail into a file
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );

      // create a new temporary image
      $tmp_img = imagecreatetruecolor( 200, 200 );

      // copy and resize old image into new image
      imagecopyresized( $tmp_img, $ims, 0, 0, 0, 0, 200, 200, $xadds, $yadds );

      // save thumbnail into a file
      imagejpeg( $tmp_img, "{$pathToThumbsSq}{$fname}" );

      rename($pathToImages.$fname,$moveTo.$fname);

    }
  }
  // close the directory
  closedir( $dir );
}
// call createThumb function and pass to it as parameters the path
// to the directory that contains images, the path to the directory
// in which thumbnails will be placed and the thumbnail's width.
// We are assuming that the path will be a relative path working
// both in the filesystem, and through the web for links
createThumbs("uploads/");
?>
takes all of the newly uploaded images out of purgatory and generates thumbnails for them.<br />
<a href="regenhtml.php">regen new html</a>
<br />
<a href="admin.php">Admin Portal</a>
