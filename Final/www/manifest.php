<?php

// only cache files in the following folders (avoids other stuff like "app/")
$folders = array('bootstrap-3.1.0-dist', 'upload', 'css');
$files = array('index2.php');

// recursive function
function append_filelist(&$files, $folder) {
  if ($dh = opendir($folder)) {
    while (($file = readdir($dh)) !== false) {
      if ( ! in_array($file, array('.', '..', '.svn')) &&
             (substr($file, -4) != ".swp")) {
        if (is_dir($folder."/".$file))
          append_filelist($files, $folder."/".$file);
        else
          //$files[] = $folder."/".$file."?hash=".md5_file($folder."/".$file);
          $files[] = $folder."/".$file;
      } // if
    } // while
  } // if
}

// init
foreach ($folders as $folder)
  if (is_dir($folder))
    append_filelist($files, $folder);

// generate output
$body = "CACHE MANIFEST\n\nCACHE:\n";
foreach ($files as $file)
  $body .= $file."\n";
$body .= "\nNETWORK:\n*\n";

// render output (the 'Content-length' header avoids the automatic creation of a 'Transfer-Encoding: chunked' header)
header('Content-type: text/cache-manifest');
header('Content-length: '.strlen($body));
echo $body;
echo "\nFALLBACK:\n";
echo "upload_file.php filler.html\n";
echo "delete_file.php filler.html\n";
echo "update_file.php filler.html\n";

?>