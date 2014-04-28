<?php
	session_start ();

	$selected_radio = $_POST['element'];

	$con = mysqli_connect ( "localhost", "host", "", "test" );

	if (mysqli_connect_errno ()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error ();
	}
	
	$num = $_POST['gridNum'];
	$pos = $_POST['gridPos'];
	$text = $_POST['up'];
	
	
	$temp = explode ( ".", $_FILES ["file"] ["name"] );
	$extension = end ( $temp );
	if($extension == "mp3" || $extension == "ogg"){
		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "upload/" . $_POST["name"]);
		$query = mysqli_query($con, "UPDATE grid SET Path = '" . $_POST["name"] . "' WHERE GridNumber = " . $_POST["gridNum"] . " AND Position = " . $_POST["gridPos"] . " AND Audio = 1");
		if($query){
			echo "File uploaded and references changed";
			$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
			header ( 'Location: index2.php' );			
		}
		else{
			echo mysqli_error($con);
		}
	}
	else{
		move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_POST["name"]);
		$query = mysqli_query($con, "UPDATE grid SET Path = '" . $_POST["name"] . "' WHERE GridNumber = " . $_POST["gridNum"] . " AND Position = " . $_POST["gridPos"] . " AND Audio = 0");
		if($query){		
			$querys = mysqli_query($con, "UPDATE grid SET Description = '" . $text . "' WHERE GridNumber = " . $_POST["gridNum"] . " AND Position = " . $_POST["gridPos"] . " AND Audio = 0");
			echo "File uploaded and references changed";
			$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
			header ( 'Location: index2.php' );
		}
		else{
			echo mysqli_error($con);
		}

	}
?>
		