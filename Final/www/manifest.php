<?php

// only cache files in the following folders (avoids other stuff like "app/")
$folders = array('bootstrap-3.1.0-dist', 'upload', 'css');
$files = array('index2.php');

// recursively grab files
function append_filelist(&$files, $folder) {
  if ($dh = opendir($folder)) {
    while (($file = readdir($dh)) !== false) {
      if ( ! in_array($file, array('.', '..', '.svn')) &&
             (substr($file, -4) != ".swp")) {
        if (is_dir($folder."/".$file))
          append_filelist($files, $folder."/".$file);
        else
          $files[] = $folder."/".$file;
      }
    }
  } 
}


foreach ($folders as $folder)
  if (is_dir($folder))
    append_filelist($files, $folder);

// generate the data for the cache manifest file
$body = "CACHE MANIFEST\n\nCACHE:\n";
foreach ($files as $file)
  $body .= $file."\n";
$body .= "\nNETWORK:\n*\n";

// echo data to create file
header('Content-type: text/cache-manifest');
header('Content-length: '.strlen($body));
echo $body;
echo "\nFALLBACK:\n";
echo "upload_file.php filler.html\n";
echo "delete_file.php filler.html\n";
echo "update_file.php filler.html\n";

?>