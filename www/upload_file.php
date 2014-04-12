<?php
session_start ();

$selected_radio = $_POST['element'];

$con = mysqli_connect ( "localhost", "host", "", "test" );

if (mysqli_connect_errno ()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error ();
}

$allowedExts = array (
		"gif",
		"jpeg",
		"jpg",
		"png",
		"mp3",
		"ogg" 
);
$picExts = array (
		"gif",
		"jpeg",
		"jpg",
		"png" 
);
$audioExts = array (
		"mp3",
		"ogg" 
);
$temp = explode ( ".", $_FILES ["file"] ["name"] );
$extension = end ( $temp );
if ((($_FILES ["file"] ["type"] == "image/gif") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/jpg") || ($_FILES ["file"] ["type"] == "image/pjpeg") || ($_FILES ["file"] ["type"] == "image/x-png") || ($_FILES ["file"] ["type"] == "image/png") || ($_FILES ["file"] ["type"] == "audio/mpeg3") || ($_FILES ["file"] ["type"] == "audio/ogg") || ($_FILES ["file"] ["type"] == "audio/x-mpeg-3") || ($_FILES ["file"] ["type"] == "audio/mpeg") || ($_FILES ["file"] ["type"] == "video/mpeg") || ($_FILES ["file"] ["type"] == "video/x-mpeg") || ($_FILES ["file"] ["type"] == "audio/mp3") || ($_FILES ["file"] ["type"] == "audio/x-mp3") || ($_FILES ["file"] ["type"] == "audio/x-mpeg3") || ($_FILES ["file"] ["type"] == "audio/mpg") || ($_FILES ["file"] ["type"] == "audio/x-mpg") || ($_FILES ["file"] ["type"] == "auido/x-mpegaudio")) && in_array ( $extension, $allowedExts )) {
	if ($_FILES ["file"] ["error"] > 0)
		{
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  elseif (in_array($extension,$picExts))
	{
		echo "Upload: " . $_FILES ["file"] ["name"] . "<br>";
		echo "Type: " . $_FILES ["file"] ["type"] . "<br>";
		echo "Size: " . ($_FILES ["file"] ["size"] / 1024) . " kB<br>";
		echo "Temp file: " . $_FILES ["file"] ["tmp_name"] . "<br>";
		
		if (file_exists ( "upload/" . $_POST ["name"] )) {
			echo $_FILES ["file"] ["name"] . " already exists. ";
		} else {
			move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "upload/" . $_POST ["name"] );
			echo "Stored in: " . "upload/" . $_FILES ["file"] ["name"];
			$gridNumber = intval ( $_POST ["gridNum"] );
			$gridPosition = intval ( $_POST ["gridPos"] );
			$sqlq = "INSERT INTO GRID (PID, Path, Position, GridNumber) VALUES ('0','$_POST[name]','$gridPosition','$gridNumber')";
			if (mysqli_query ( $con, $sqlq )) {
				echo "Added to database";
				$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
				header ( 'Location: index2.php' );
			} else {
				echo mysqli_error ( $con );
			}
		}
	}
	else{
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "upload/" . $_POST ["name"] );
		$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
		header ( 'Location: index2.php' );
	}
} else {
	echo "failed";
}
mysqli_close ( $con );
?> 