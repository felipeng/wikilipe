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
if (isset($_GET['p'])) {
    $PAGE = $_GET['p'];
    echo "$PAGE";
    echo "<br>";
    $PAGE = rtrim($PAGE, "/"); // accept URL ended with /
    echo "$PAGE";
    $PAGE_PATH = pagePath($PAGE);
    $FILENAME = "$DIR_PAGES/$PAGE.md";

    //Check if the data directory is writeable
    if (!is_writable($DIR)) {
      $CONTENT = "# Error\nThe directory ($DIR/) is not writable" . permissions();
    } else {
      // Load menu
      $MENU = menu();

      if (file_exists($FILENAME)) {
         if (!is_writable($FILENAME)) {
            $CONTENT = "# Error\nThe file ($FILENAME) is not writable" . permissions();
          } else {
            $IMGS = listImages('preview');
            $CONTENT = file_get_contents($FILENAME);
          }
      } else {
        $CONTENT = "# Error\nPage not found! Do you want to create? <a href='javascript:' onclick='newPage(\"$PAGE\");'>YES!</a>";
      }
    }
} elseif (isset($_POST['save_x'])) { // Save page
    $PAGE = $_POST['page'];
    $CONTENT = $_POST['code'];
    $CONTENT = str_replace('/\r\n/', "\n", $CONTENT); // remove EOF ^M
    $CONTENT = str_replace('/\r/', "\n", $CONTENT);   // remove EOF ^M

    // Create page subdir if not exists
    $PAGE_SUBDIR = $DIR_PAGES."/".substr($PAGE, 0, strrpos($PAGE, '/'));
    if (!is_dir($PAGE_SUBDIR)) {
      mkdir($PAGE_SUBDIR, 0775, true);
    }

    // If $CONTENT is empty deletes the file and redirect to home
    if ($CONTENT == "") {
      unlink("$DIR_PAGES/$PAGE.md");
      header("Location: ?p=home");
    } else {
      file_put_contents("$DIR_PAGES/$PAGE.md", $CONTENT);
      header("Location: ?p=$PAGE");
    }
} elseif (isset($_GET['search'])) { // Search content
    // Load menu
    $MENU = menu();

    $QUERY = $_GET['search'];
    $PAGE = "search: $QUERY";
    $PAGE_PATH = $PAGE;
    $list_contents = "";
    $list_files = "";

    // if query is not empty
    if ($QUERY != "") {
      $allfiles = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($DIR_PAGES),
          RecursiveIteratorIterator::SELF_FIRST
      );
      foreach ($allfiles as $file) {
          if (is_file($file)) {
              $filename = substr(str_replace($DIR_PAGES, "", $file), 1, -3);
              if (stripos(basename($file), $QUERY) !== false) {
                  $list_files .= "* [$filename]($filename)\n";
              } else {
                $file_content = file_get_contents($file);
                if (stripos($file_content, $QUERY) !== false) {
                  $list_contents .= "* [$filename]($filename)\n";
                }
              }
          }
      }
    }

    $CONTENT = "# File matches: ".substr_count($list_files, "\n")."\n$list_files
# Content matches: ".substr_count($list_contents, "\n")."\n$list_contents\n";

} elseif (isset($_GET['upload'])) { // Upload images
  $PAGE = "upload";

  // Load menu
  $MENU = menu();

  if (isset($_POST['upload'])) {
    $uploadStatus = upload();
  } else {
    $uploadStatus = '';
  }

  if (isset($_GET['delete'])) {
    global $DIR_IMGS;
    $img = $_GET['delete'];
    $img_path = $DIR_IMGS . "/" . $img;

    // check if the filename has the / character (security reason)
    if (strpos($img, '/') !== false) {
      $uploadStatus = "<div style='color: red'>Error: illegal filename.</div>";
    } else {
      if (!file_exists($img_path)) {
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

  $CONTENT .= listImages('upload');

} else { // Anything else
  header("Location: index.php?p=home");
}

// Call the html template
include_once('lib/page_tpl.html');
