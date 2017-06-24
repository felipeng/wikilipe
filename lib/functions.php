<?php

// do the image upload
function upload(){
  global $DIR_IMGS;
  $file = basename($_FILES["fileToUpload"]["name"]);
  $fullpathfile =  $DIR_IMGS . "/" . $file;
  $uploadOk = 1;
  $imageFileType = pathinfo($fullpathfile,PATHINFO_EXTENSION);

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          $uploadMsg = "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          $uploadMsg = "File is not an image.";
          $uploadOk = 0;
      }
  }
  // Check if file already exists
  if (file_exists($fullpathfile)) {
      $uploadMsg = "Error: file ($file) already exists and was not uploaded.";
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 2000000) {
      $uploadMsg = "Error: file ($file) is too large and was not uploaded.";
      $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $uploadMsg = "Error: only .jpg, .jpeg, .png and .gif files are allowed and was not uploaded.";
      $uploadOk = 0;
  }

  // Check if $uploadOk is set to 1 and do the upload
  if ($uploadOk == 1) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fullpathfile)) {
          $uploadMsg = "The file $file has been uploaded.";
      } else {
          $uploadMsg = "Error: there was an error uploading the file ($file).";
      }
  }

  return "<div style='color: " . ($uploadOk == 1 ? 'green':'red') . "'>$uploadMsg</div><br />";
}

// size of files
function file_size($img) {
  $size = filesize($img);
  $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $power = $size > 0 ? floor(log($size, 1024)) : 0;
  return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

// list of images
function list_imgs($page){
  global $DIR_IMGS;
  $img_files = array_diff(scandir( $DIR_IMGS), array('.', '..'));
  $imgs = "\n<h2>Images</h2>\n<div id='page-images-container'>\n";

  if ($page == "upload"){
    foreach ($img_files as $img) {
      $imgs .= "<div><img class='images' src='$DIR_IMGS/$img'>$img (" . file_size("$DIR_IMGS/$img") . ") <a href='index.php?upload&delete=$img'><img class='delete_icon' src='lib/imgs/delete.png'></a></div>\n";
    }
  } else if($page == 'preview'){
    foreach ($img_files as $img) {
      $imgs .= "<div onclick='insertImg(\"$img\");'><img class='images' style='cursor: pointer;' data-src='$DIR_IMGS/$img' alt='$img'><br>$img</div>";
    }
  }
  $imgs .= "</div>";
  return $imgs;
}

// Menu
function menu(){
  global $DIR_PAGES;
  $menu_file = $DIR_PAGES . "/wikilipe/menu.md";

  // load menu or if not exist create it
  if (!file_exists($menu_file)){
      copy("lib/menu_tpl.md", $menu_file);
      copy("README.md", $DIR_PAGES . "/wikilipe/readme.md");
  }
  return file_get_contents($menu_file);
}

// Page Path
function page_path($page){
  $path_exploded = explode('/', $page);
  $title_path = "";
  $PAGE_PATH = "";
  for($i=0; $i < sizeof($path_exploded); $i++){
      $title_path .= "$path_exploded[$i]/";
      $PAGE_PATH .= " / <a href='?p=$title_path'>$path_exploded[$i]</a>";
  }
  return $PAGE_PATH;
}

//
function permissions(){
  global $DIR;
  $WEB_U = posix_getpwuid(posix_geteuid())['name'];
  $WEB_G = posix_getgrgid(posix_geteuid())['name'];
  return ", please correct the permissions running the following commands:
<pre><code>chown -R $WEB_U:$WEB_G $DIR\nchmod -R 775 $DIR\nfind $DIR -type f -exec chmod 664 \"{}\" \;</code></pre>";
}

?>
