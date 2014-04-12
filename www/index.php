<!doctype html>
<html lang="en">

<head>
<title>Grid Game</title>
<link href="bootstrap-3.1.0-dist\dist\css\bootstrap.css"
rel="stylesheet">
<link href="sitecss.css" rel="stylesheet">
<script src="jquery-2.1.0.js" type="text/javascript"> </script>
</head>
<body>
<?php

session_start();

$con=mysqli_connect("localhost","host","","test");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
$result = mysqli_query($con,"SELECT * FROM GRID");
$resulttwo = mysqli_query($con,"SELECT * FROM GRID WHERE GridNumber = 1 ORDER BY Position");
$index = '1';
echo "<div class = 'grid'></div> <br>";
while($row = mysqli_fetch_array($resulttwo)){
//	echo '<img src="/upload/' . $row['Path'] . '" height ="100" width="200"> <br>';
	echo $row['Path'] . $row['PID'];
	echo "<div class=col-lg-4 col-md-4><a href=# class=thumbnail><img class = play" . $index . " img-responsive src='/upload/" . $row['Path'] . "' height = 100 width = 200></a></div>";
}

mysqli_close($con);

$redirect_url = (isset($_SESSION['redirect_url'])) ? $_SESSION['redirect_url'] : '/';
unset($_SESSION['redirect_url']);
header("Location: $redirect_url", true, 303);
exit; 

?>
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
<label for="filler">Filler:</label>
<input type="text" name="filler" id="filler"><br>
<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>