<!doctype html>
<html lang="en-gb" manifest="cona.appcache">

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
	 
	$sql = "CREATE TABLE GRID (PID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(PID), Path CHAR(30), Position INT, GridNumber INT, Audio INT)";
	if(mysqli_query($con,$sql)){
		echo "Table Grid created successfully";
		if(mysqli_query($con,"ALTER TABLE GRID ADD CONSTRAINT tb_UQ UNIQUE (Position, GridNumber, Audio)")){
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

$picIndex = array();

$audioToJSON = array();

$audioIndex = array();

while($row = mysqli_fetch_array($resulttwo)){
	$temp = explode ( ".", $row['Path'] );
	$extension = end ( $temp );
	if($extension == 'mp3' || $extension == 'ogg'){
		array_push($audioToJSON,$row['Path']);
		array_push($audioIndex,$row['Position']);
//		$audioToJSON[$row['Position']] = $row['Path'];
	}
	else{
		array_push($arrayToJSON,$row['Path']);
		array_push($picIndex,$row['Position']);
//		$arrayToJSON[$row['Position']] = $row['Path'];
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
	var picIndex = <?php echo json_encode($picIndex); ?>;
	var audioIndex = <?php echo json_encode($audioIndex); ?>;
	var i = 0;
	var x = 0;
	var y = 0;
	var audioElements = [9];
	for(i = 0; i < picArray.length && i < 9; i ++){
		x = picIndex.shift().toString();
		$("<div class=col-lg-4 col-md-4><a href=# class=thumbnail><img class =play" + x + " img-responsive src='\/upload/" + picArray[i] + "\'></a></div>").appendTo(".grid");
		audioElements[i] = document.createElement("audio");
	}
	for(i = 0; i < audioArray.length && i < 9; i ++){
		y = audioIndex.shift();
		audioElements[y].setAttribute("src","upload/" + audioArray[i]);
	}
	console.log(audioArray);
	console.log(audioIndex);
	console.log(picIndex);
	var linkArray = $("img").map(function(){
		return $(this).attr('class');
	}).get();
	console.log(linkArray);
	$(".play1").click(function(){
		if(audioElements[1].paused == true){
			audioElements[1].play();
		}
		else{
			audioElements[1].pause();
		}
	});
	$(".play0").click(function(){
		if(audioElements[0].paused == true){
			audioElements[0].play();
		}
		else{
			audioElements[0].pause();
		}
	});
	$(".play2").click(function(){
		if(audioElements[2].paused == true){
			audioElements[2].play();
		}
		else{
			audioElements[2].pause();
		}
	});
	$(".play3").click(function(){
		if(audioElements[3].paused == true){
			audioElements[3].play();
		}
		else{
			audioElements[3].pause();
		}
	});
	$(".play4").click(function(){
		if(audioElements[4].paused == true){
			audioElements[4].play();
		}
		else{
			audioElements[4].pause();
		}
	});
	$(".play5").click(function(){
		if(audioElements[5].paused == true){
			audioElements[5].play();
		}
		else{
			audioElement[5].pause();
		}
	});
	$(".play6").click(function(){
		if(audioElements[6].paused == true){
			audioElements[6].play();
		}
		else{
			audioElement[6].pause();
		}
	});
	$(".play7").click(function(){
		if(audioElements[7].paused == true){
			audioElements[7].play();
		}
		else{
			audioElement[7].pause();
		}
	});
	$(".play8").click(function(){
		if(audioElements[8].paused == true){
			audioElements[8].play();
		}
		else{
			audioElement[8].pause();
		}
	});
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