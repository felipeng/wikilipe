<?php

// Default directories
$DIR = "data";
$DIR_IMGS = "$DIR/imgs";
$DIR_PAGES = "$DIR/pages";

// Load functions
include('lib/functions.php');

// Debug
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// Show page
if(isset($_GET['p'])){
    $PAGE = $_GET['p'];
    $PAGE = preg_replace("#/$#","",$PAGE); // accept URL ended with /
    $PAGE_PATH = page_path($PAGE);
    $FILENAME = "$DIR_PAGES/$PAGE.md";

    //Check if the data directory is writeable
    if (!is_writable($DIR)) {
      $CONTENT = "# Error\nThe directory ($DIR/) is not writable" . permissions();
    } else {
      // Load menu
      $MENU = menu();

      if (file_exists($FILENAME)){
         if (!is_writable($FILENAME)){
            $CONTENT = "# Error\nThe file ($FILENAME) is not writable" . permissions();
          } else {
            $IMGS = list_imgs('preview');
            $CONTENT = file_get_contents($FILENAME);
          }
      } else {
        $CONTENT = "# Error\nPage not found! Do you want to create? <a href='javascript:' onclick='newPage(\"$PAGE\");'>YES!</a>";
      }
    }
}
// Save page
else if(isset($_POST['save'])){
    $PAGE = $_POST['page'];
    $CONTENT = $_POST['code'];
    $CONTENT = preg_replace('/\r\n/', "\n",$CONTENT); // remove EOF ^M
    $CONTENT = preg_replace('/\r/', "\n",$CONTENT);   // remove EOF ^M

    // Create page subdir if not exists
    $PAGE_SUBDIR = $DIR_PAGES."/".substr($PAGE, 0, strrpos($PAGE, '/'));
    if (!is_dir($PAGE_SUBDIR)) {
      mkdir($PAGE_SUBDIR,0775,true);
    }
    file_put_contents("$DIR_PAGES/$PAGE.md",$CONTENT);
    header ("Location: ?p=$PAGE");
}
// Search content
else if(isset($_GET['search'])){
    $QUERY = $_GET['search'];
    $PAGE = "search: $QUERY";
    $PAGE_PATH = $PAGE;
    $list = "";
    $files = array();
    // Load menu
    $MENU = menu();

    // if query is empty
    if($QUERY != ""){
      $ob = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($DIR_PAGES), RecursiveIteratorIterator::SELF_FIRST);
      foreach($ob as $name => $object){
          if (is_file($name)) {
              $tmp = file_get_contents($name);
              if (strpos($tmp,$QUERY) !== false) {
                  $files[] = $name;
              }
          }
      }
      sort($files);
      for($i=0; $i < sizeof($files); $i++){
          $path_file = substr(substr($files[$i], strlen($DIR_PAGES)+1), 0, -3);
          $list .= "<li><a href='?p=$path_file'>$path_file</a></li>";
      }
    }
    $CONTENT = "Matches: $QUERY (".count($files).")<ul>$list</ul>";

}
// Upload images
else if(isset($_GET['upload'])){
  $PAGE = "upload";

  // Load menu
  $MENU = menu();

  if(isset($_POST['upload'])){
    $uploadStatus = upload();
  } else {
    $uploadStatus = '';
  }

  if(isset($_GET['delete'])){
    global $DIR_IMGS;
    $img = $_GET['delete'];
    $img_path = $DIR_IMGS . "/" . $img;

    // check if the filename has the / character (security reason)
    if (strpos($img, '/') !== false) {
      $uploadStatus = "<div style='color: red'>Error: illegal filename.</div>";
    } else {
      if(!file_exists($img_path)){
        $uploadStatus = "<div style='color: red'>Error: file ($img) does not exist.</div>";
      } else {
        // delete image file
        unlink($img_path);
        $uploadStatus = "<div style='color: green'>File ($img) was deleted.</div>";
      }
    }
  }

  $CONTENT = "\n# Upload
   <form action='index.php?upload' method='post' enctype='multipart/form-data'>
      Select an image to upload (.jpg, .jpeg, .png, .gif):
      <input type='file' name='fileToUpload' id='fileToUpload'>
      <input type='submit' value='Upload Image' name='upload'>
    </form>$uploadStatus";

  $CONTENT .= list_imgs('upload');

} // Anything else
else {
  header ("Location: index.php?p=home");
}

// Call the html template
include('lib/page_tpl.html');

?>
