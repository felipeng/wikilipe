<?php

// Configuration - read and parse configuration file
$CONFIG = parse_ini_file("$DIR/config.ini", true);

// Configuration - wikilipe - menu (show/hide)
if ($CONFIG['wikilipe']['menu_hidden'] == "yes") {
  $menu_hidden = "menu_hidden";
} else {
  $menu_hidden = "";
}


// do the image upload
function upload()
{
  global $DIR_IMGS;
  $file = basename($_FILES["fileToUpload"]["name"]);
  $fullpathfile =  $DIR_IMGS . "/" . $file;
  $uploadOk = 1;
  $imageFileType = pathinfo($fullpathfile, PATHINFO_EXTENSION);

  // Check if image file is a actual image or fake image
  if (isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if ($check !== false) {
          $uploadMsg = "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          $uploadMsg = "File is not an image.";
          $uploadOk = 0;
      }
  }
  // Check if file already exists
  if (file_exists($fullpathfile)) {
      $uploadMsg = "Error: file ($file) already exists.";
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 2000000) {
      $uploadMsg = "Error: file ($file) is too large and was not uploaded.";
      $uploadOk = 0;
  }
  // Allow certain file formats
  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
      $uploadMsg = "Error: only .jpg, .jpeg, .png and .gif files are allowed.";
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
function fileSizeHumanized($img)
{
  $size = filesize($img);
  $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $power = $size > 0 ? floor(log($size, 1024)) : 0;
  return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

// list of images
function listImages($page)
{
  global $DIR_IMGS;
  $imageFiles = array_diff(scandir($DIR_IMGS), array('.', '..'));
  $imgs = "\n<h2>Images</h2>\n<div id='page-images-container'>\n";

  if ($page == "upload") {
    foreach ($imageFiles as $img) {
      $imgs .= "<div><img class='images' src='$DIR_IMGS/$img'>$img (" . fileSizeHumanized("$DIR_IMGS/$img") . ")
      <a href='index.php?upload&delete=$img'><img class='delete_icon' src='lib/imgs/delete.png'></a></div>\n";
    }
  } elseif ($page == 'preview') {
    foreach ($imageFiles as $img) {
      $imgs .= "<div onclick='insertImg(\"$img\");'><img class='images' style='cursor: pointer;' data-src='$DIR_IMGS/$img' alt='$img'><br>$img</div>";
    }
  }
  $imgs .= "</div>";
  return $imgs;
}

// Menu
function menu()
{
  global $DIR_PAGES;
  $menuFile = $DIR_PAGES . "/wikilipe/menu.md";

  // load menu or if not exist create it
  if (!file_exists($menuFile)) {
      copy("lib/menu_tpl.md", $menuFile);
  }
  return file_get_contents($menuFile);
}

// Page Path
function pagePath($page)
{
  $pathExploded = explode('/', $page);
  $titlePath = "";
  $pagePath = "";
  for ($i=0; $i < sizeof($pathExploded); $i++) {
      $titlePath .= "$pathExploded[$i]/";
      $pagePath .= " / <a href='?p=$titlePath'>$pathExploded[$i]</a>";
  }
  return $pagePath;
}

// webserver check right permissions
function permissions()
{
  global $DIR;
  if (extension_loaded('posix')) {
    $WEB_U = posix_getpwuid(posix_geteuid())['name'];
    $WEB_G = posix_getgrgid(posix_geteuid())['name'];
    $RESULT = ", please correct the permissions running the following commands:
<pre><code>chown -R $WEB_U:$WEB_G $DIR\nchmod -R 775 $DIR\nfind $DIR -type f -exec chmod 664 \"{}\" \;</code></pre>";
  } else {
    $RESULT = ", please correct the permission giving the web server write access.";
  }

  return $RESULT;
}

