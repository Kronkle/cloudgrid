<?php
	
	session_start ();

	$selected_radio = $_POST['element'];

	$con = mysqli_connect ( "localhost", "host", "", "test" );

	if (mysqli_connect_errno ()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error ();
	}
	
	$pos = intval($_POST['gridPos']);
	$num = intval($_POST['gridNum']);
	
	if($selected_radio == "audio"){
		$abs = mysqli_query($con, "SELECT * FROM grid WHERE GridNumber = " . $num . " AND Position = " . $pos . " AND Audio = 1");
		while($row = mysqli_fetch_array($abs)){
			unlink("upload/" . $row['Path']);
		}
		if(mysqli_query($con, "DELETE FROM grid WHERE GridNumber = " . $num . " AND Position = " . $pos . " AND Audio = 1")){
			echo "File Deleted";
			$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
			header ( 'Location: index2.php' );
		}
	}
	else{
		$resulttwo = mysqli_query($con, "SELECT * FROM grid WHERE GridNumber = " . $num . " AND Position = " . $pos . " AND Audio = 0");
		while($row = mysqli_fetch_array($resulttwo)){
			unlink("upload/" . $row['Path']);
		}
		if(mysqli_query($con, "DELETE FROM grid WHERE GridNumber = " . $num . " AND Position = " . $pos . " AND AUDIO = 0")){
			echo "File Deleted";
			$_SESSION ['redirect_url'] = $_SERVER ['PHP_SELF'];
			header ( 'Location: index2.php' );
		}
	}
	
	mysqli_close($con);
	
?>
				
				