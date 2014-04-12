<!doctype html>
<html lang="en">

<head>
<title>Grid Game</title>
<link href="bootstrap-3.1.0-dist/dist/css/bootstrap.css"
	rel="stylesheet">
<link href="sitecss.css" rel="stylesheet">
<script src="jquery-2.1.0.js" type="text/javascript"> </script>
</head>
<body>
<?php


$con=mysqli_connect("localhost","host","","test");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if(mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE 'GRID'"))!=1){
	 
	$sql = "CREATE TABLE GRID (PID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(PID), Path CHAR(30), Position INT, GridNumber INT)";
	if(mysqli_query($con,$sql)){
		echo "Table Grid created successfully";
		if(mysqli_query($con,"ALTER TABLE GRID ADD CONSTRAINT tb_UQ UNIQUE (Position, GridNumber)")){
			echo "Alteration of table Grid successful";
		}
		else{
			echo mysqli_error($con);
		}
	}
	
	else{
		echo mysqli_error($con);
	}
} 
  
$result = mysqli_query($con,"SELECT * FROM GRID");

$resulttwo = mysqli_query($con,"SELECT * FROM GRID WHERE GridNumber = 1 ORDER BY Position ASC");

$index = '1';

$arrayToJSON = array();

$audioToJSON = array();

while($row = mysqli_fetch_array($resulttwo)){
	$temp = explode ( ".", $row['Path'] );
	$extension = end ( $temp );
	if($extension == 'mp3' || $extension == 'ogg'){
		array_push($audioToJSON,$row['Path']);
	}
	else{
		array_push($arrayToJSON,$row['Path']);
	}
}

mysqli_close($con);


?>

<div class='container'>
	<div class='grid'></div>
</div>
<script type="text/javascript">
	var picArray = <?php echo json_encode($arrayToJSON); ?>;
	var audioArray = <?php echo json_encode($audioToJSON); ?>;
	var i = 0;
	for(i = 0; i < picArray.length; i ++){
		$("<div class=col-lg-4 col-md-4><a href=# class=thumbnail><img class =play" + i + " img-responsive src='\/upload/" + picArray[i] + "\'></a></div>").appendTo(".grid");	
	}
</script>



<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<label for="name">NewFileName:</label>
<input type="text" name="name" id="name"><br>
<label for="gridNum">gridNum:</label>
<input type="text" name="gridNum" id="gridNum"><br>
<label for="gridPos">GridPosition:</label>
<input type="text" name="gridPos" id="gridPos"><br>
<label for="audio">Audio:</label>
<input type="radio" name="element" value="audio"><br>
<label for="photo">Photo:</label>
<input type="radio" name="element" value="photo"><br>
<input type="submit" name="submit" value="Submit">
</form>



</body>
</html>