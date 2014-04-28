<!doctype html>
<html lang="en-gb" manifest=manifest.php>

<head>
<title>Grid Game</title>
<link href="bootstrap-3.1.0-dist/dist/css/bootstrap.css"
	rel="stylesheet">
<link href="css/sitecss.css" rel="stylesheet">
<script src="jquery-2.1.0.js" type="text/javascript"> </script>
<script src="js/jquery.hoverIntent.js" type="text/javascript"> </script>
</head>
<body>

	<!-- Bootstrap template I modified to work with our website ~Evan -->

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container1">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index2.php">Grid Game</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="javascript:updateToggle()">Update</a></li>
            <li><a href="javascript:authorToggle()">Author</a></li>
            <li><a href="javascript:deleteToggle()">Delete</a></li>
			<li><a href="index2.php?q=1">Grid One</a></li>
			<li><a href="index2.php?q=2">Grid Two</a></li>
			<li><a href="index2.php?q=3">Grid Three</a></li>
			<li><a href="index2.php?q=4">Grid Four</a></li>
			<li><a href="index2.php?q=5">Grid Five</a></li>
			<li><a href="index2.php?q=6">Grid Six</a></li>
			<li><a href="index2.php?q=7">Grid Seven</a></li>
			<li><a href="index2.php?q=8">Grid Eight</a></li>
			<li><a href="index2.php?q=9">Grid Nine</a></li>
			<li><a href="index2.php?q=10">Grid Ten</a></li>
	
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
	

<?php
//grab grid number from url ~Evan
if(isset($_GET["q"])){
	$q =intval($_GET["q"]);
}
else{
	$q = 1;
}
$con=mysqli_connect("localhost","host","","test");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
/*
Create database if it does not exist, the important thing here is the unique triple position, grid number, and audio which allows us to
link a picture and a sound file together but prevents us from adding duplicate pictures or sounds to the same place audio is a boolean
where 1 will mean it is a sound file and anything else means it is a picture.
Path is path to file location, position is position in grid, and PID is something I though would be useful but wasn't, could easily be removed.
~Evan
*/
  
if(mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE 'GRID'"))!=1){
	 
	$sql = "CREATE TABLE GRID (PID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(PID), Path CHAR(30), Position INT, GridNumber INT, Audio INT, Description CHAR(30))";
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

/*
Check to see if our upload folder exists and make it if it doesn't ~Evan
*/

if (!file_exists('upload')) {
    mkdir('upload', 0755, true);
}

//query used for troubleshooting ~Evan  
//$result = mysqli_query($con,"SELECT * FROM GRID");
//query to grab all the rows from our data base that match the grid number specified by the user if no param is passed in url
//grabs grid 1 ~Evan
$resulttwo = mysqli_query($con,"SELECT * FROM GRID WHERE GridNumber = " . $q . " ORDER BY Position ASC");

$index = '1';

$arrayToJSON = array();

$picIndex = array();

$audioToJSON = array();

$audioIndex = array();

$textToJSON = array();

//sort data in rows into arrays based on whether they are audio files or photos
//one array contains the paths while the other contains the indices to make sure
//they are mapped correctly ~Evan
while($row = mysqli_fetch_array($resulttwo)){
	$temp = explode ( ".", $row['Path'] );
	$extension = end ( $temp );
	if($extension == 'mp3' || $extension == 'ogg'){
		array_push($audioToJSON,$row['Path']);
		array_push($audioIndex,$row['Position']);
//		$audioToJSON[$row['Position']] = $row['Path'];
	}
	else{
		//description is linked to photos so it doesn't need its own index ~Evan
		array_push($arrayToJSON,$row['Path']);
		array_push($picIndex,$row['Position']);
		array_push($textToJSON,$row['Description']);
//		$arrayToJSON[$row['Position']] = $row['Path'];
	}
}

mysqli_close($con);

?>

<div class='container'>
	<div class='grid'></div>
</div>
<script type="text/javascript">
	//Json encode our php arrays so we can get the paths to use in our javascript code to create our img, audio, and text elements ~Evan
	var picArray = <?php echo json_encode($arrayToJSON); ?>;
	var audioArray = <?php echo json_encode($audioToJSON); ?>;
	var picIndex = <?php echo json_encode($picIndex); ?>;
	var audioIndex = <?php echo json_encode($audioIndex); ?>;
	var textArray = <?php echo json_encode($textToJSON); ?>;
	var i = 0;
	var x = 0;
	var y = 0;
	var z = 0
	//Brennan's array for holding the audio elements and making indexing easier
	audioElements = new Array(document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'), document
		.createElement('audio'));
	//My loop for creating the img elements, the index is used to make sure I tie the right audio to the image
	//can't use i as the index for the class name because i is only garunteed to corespond with the correct audio element when the grid is full
	//thus we use the index array to make sure that we have them 
	for(i = 0; i < picArray.length && i < 9; i ++){
		x = picIndex.shift().toString();
		$("<div class=col-lg-4 col-md-4 id = playable" + x + "><a href=# class=thumbnail><img class =play" + x + " img-responsive src='\/upload/" + picArray[i] + "\'></a></div>").appendTo(".grid");
		$("#playable"+x).append("<p class=\"player" + x + "\" >" + textArray[i] + "</p>");
	}
	for(i = 0; i < audioArray.length && i < 9; i ++){
		y = audioIndex.shift();
		console.log(y);
		audioElements[y].setAttribute("src","upload/" + audioArray[i]);
	}
	/* Debugging info ~Evan
	console.log(audioArray);
	console.log(audioIndex);
	console.log(picIndex);

	var linkArray = $("img").map(function(){
		return $(this).attr('class');
	}).get();
	console.log(linkArray);
	*/
	//Brennan and Mike's audio function
	
	$(".play7").click(function(){
		if(audioElements[7].paused == true){
			audioElements[7].play();
		}
		else{
			audioElements[7].pause();
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
	$(".play1").click(function(){
		if(audioElements[1].paused == true){
			audioElements[1].play();
		}
		else{
			audioElement[1].pause();
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
	
	//Show the description for the image when the image is moused over
	
	$(".play0").mouseover(function(){
		$(".player0").toggle();
	});
	$(".play1").mouseover(function(){
		$(".player1").toggle();
	});
	$(".play2").mouseover(function(){
		$(".player2").toggle();
	});
	$(".play3").mouseover(function(){
		$(".player3").toggle();
	});
	$(".play4").mouseover(function(){
		$(".player4").toggle();
	});
	$(".play5").mouseover(function(){
		$(".player5").toggle();
	});
	$(".play6").mouseover(function(){
		$(".player6").toggle();
	});
	$(".play7").mouseover(function(){
		$(".player7").toggle();
	});
	$(".play8").mouseover(function(){
		$(".player8").toggle();
	});
	//pretoggle descriptions so they don't show up by default ~Evan
	$(".player0").toggle();
	$(".player2").toggle();
	$(".player3").toggle();
	$(".player4").toggle();
	$(".player5").toggle();
	$(".player6").toggle();
	$(".player7").toggle();
	$(".player8").toggle();
	$(".player1").toggle();
	

</script>

<!-- html forms I made to get data form index2.php to the approprite php file with data for upload, authoring, and deletion of entries ~Evan -->

<form id="delete" action="delete_file.php" method="post" style = "hidden" enctype="multipart/form-data">
<label for="gridNum">gridNum:</label>
<input type="text" name="gridNum" id="gridNum"><br>
<label for="gridPos">GridPosition:</label>
<input type="text" name="gridPos" id="gridPos"><br>
<label for="audio">Audio:</label>
<input type="radio" name="element" value="audio"><br>
<label for="photo">Photo:</label>
<input type="radio" name="element" value="photo"><br>
<input type="submit" name="submit" value="Submit"><br>
</form>

<form id="author" action="upload_file.php" method="post" style = "hidden" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<label for="text">Describe:</label>
<input type="text" name="word" id="word"><br>	
<label for="name">FileName:</label>
<input type="text" name="name" id="name"><br>
<label for="gridNum">GridNumb:</label>
<input type="text" name="gridNum" id="gridNum"><br>
<label for="gridPos">GridPosi:</label>
<input type="text" name="gridPos" id="gridPos"><br>
<label for="audio">Audio: </label>
<input type="radio" name="element" value="audio">
<label for="photo"> Photo:</label>
<input type="radio" name="element" value="photo"><br>
<input type="submit" name="submit" value="Submit">
</form>

<form id="update" action="update_file.php" method="post" style = "hidden" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<label for="up">Description:</label>
<input type="text" name="up" id="up"><br>
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

<button type="button" id="autTog1" style="position: absolute; left: 75%; top: 0px; " onclick="updateToggle()">Update</button>
<button type="button" id="autTog" style="position: absolute; left: 50%; top: 0px; " onclick="authorToggle()">Author</button>
<button type="button" id="autTog2" style="position: absolute; left: 25%; top: 0px; " onclick="deleteToggle()">Delete</button>

<script>
//$('#update').appendTo('.grid');
//$('#delete').appendTo('.grid');
//$('#author').appendTo('.grid');
$('#delete').toggle();
$('#author').toggle();
$('#update').toggle();

//Brennan's old toggle functions I rewrote in jquery for simplicity ~ Evan


function authorToggle(){
	b = document.getElementById("autTog");
	if(b.innerHTML =="Author"){
		b.innerHTML = "Close";
	}
	else{
		b.innerHTML = "Author";
	}
	$('#author').toggle();
}


</script>

<script>
function updateToggle(){
	c = document.getElementById("autTog1");
	if(c.innerHTML =="Update"){
		c.innerHTML = "Close";
	}
	else{
		c.innerHTML = "Update";
	}

	$('#update').toggle();
}

</script>

<script>
function deleteToggle(){
	d = document.getElementById("autTog2");
	if(d.innerHTML =="Delete"){
		d.innerHTML = "Close";
	}
	else{
		d.innerHTML = "Delete";
	}

	$('#delete').toggle();
}

</script>



</body>
</html>