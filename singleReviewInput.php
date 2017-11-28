<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="font.css">
<link rel="stylesheet" href="cdnjs.css">
<style>
input[type=text] {
    width: 100%;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    background-color: white;
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 12px 20px 10px 20px;
}

fieldset { 
    display: block;
	width: 50%;
    margin-left: 3px;
    margin-right: 3px;
    padding-top: 0.35em;
    padding-bottom: 0.625em;
    padding-left: 0.75em;
    padding-right: 0.75em;
    border: 5px groove (internal value);
}
select {
    width: 100%;
    padding: 20px 50px;
    border: none;
    border-radius: 4px;
    background-color: #f1f1f1;
}    

/* Create two unequal columns that floats next to each other */
.column {
    float: left;
    padding: 150px;
	padding-left: 20px;
	padding-right: 20px;
	height:100vh;
	overflow: auto;
}

/* Left column */
.column.left {
    width: 40%;
	height:100vh;
}

/* right column */
.column.right {
    width: 60%;
	height:100vh;
}
hr.style-one {
    border: 0;
    height: 1px;
    background: #333;
    background-image: linear-gradient(to right, #ccc, #333, #ccc);
}
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
	width : 50%;
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
textarea {
    width: 100%;
    height: 150px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
}
</style>

<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-black w3-card">
    <a href="Homepage.php" class="w3-bar-item w3-button w3-padding-large">HOME</a>
    <a href="search.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SEARCH</a>
	<div class="w3-dropdown-hover w3-hide-small">
      <button class="w3-padding-large w3-button" title="More">BROWSE <i class="fa fa-caret-down"></i></button>     
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="browseActor.php" class="w3-bar-item w3-button">Actors</a>
        <a href="browseMovie.php" class="w3-bar-item w3-button">Movies</a>
	  </div>
	</div>
    <div class="w3-dropdown-hover w3-hide-small">
      <button class="w3-padding-large w3-button" title="More">ADD <i class="fa fa-caret-down"></i></button>     
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="actorDirectorInput.php" class="w3-bar-item w3-button">Add Actor or Director</a>
        <a href="movieInput.php" class="w3-bar-item w3-button">Add Movie</a>
        <a href="actorToMovieInput.php" class="w3-bar-item w3-button">Add Actor to Movie</a>
		<a href="directorToMovieInput.php" class="w3-bar-item w3-button">Add Director to Movie</a>
      </div>
	</div>
	<a href="reviewInput.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">MAKE REVIEW!</a>
   </div>
</div>

<div class = "column left" style="background-color:black"> 
 <form method = "get" action = "browseMovie.php?<?php echo $_GET["search"];?>">
 <center><font size = 4 color = "white">Search Actors and Directors!</font><center>
 <br><br>
 <input type="text" name="search" placeholder="Search.." value = "<?php echo $_GET["search"];?>">
 <br><br>
 <input type = "submit" value = "GO!">
 </form>
</div>
<div class = "column right">
<?php
	if (!$_GET["identifier"]) {
		if (!$_POST["hasSubmitted"]) {
			exit();
		}
		$_GET["identifier"] = $_POST["hasSubmitted"];
	}else {
		$identifier = $_GET["identifier"];
	}
?>
<form method = "Post" action = "singleReviewInput.php">
 <font size = 5>User Name</font><br>
 <input type = 'text' name = 'name' value = "<?php echo isset($_POST["name"]) ? $_POST["name"] : "Dr.Anonymous";?>">
 <br><br>
 <font size = 5>Rating</font><br>
 <select name = "rating" style = "width : 10%; float:left; padding-left :20px; padding-right:2px; background-color: white;border: 2px solid #ccc;">
 <option>0</option>
 <option>1</option>
 <option>2</option>
 <option>3</option>
 <option>4</option>
 <option>5</option>
 </select>
 <br><br><br><br>
 <font size = 5>Comments</font><br>
 <textarea name = "comment" >
 Write Comments here...
 </textarea>
 <br><br>
 <input type = "hidden" name = "hasSubmitted" value = "<?php echo $_GET["identifier"];?>">
 <input type = "submit" value = "ADD REVIEW!">
 </form>
<?php

	if (!$_POST["hasSubmitted"]) {
		exit();
	}
	$movieID = $_GET["identifier"];
	if ($_POST["name"]) {
		$name = "'".$_POST["name"]."'";
	}else {
		$name = "null";
	}
	$reviewDate = "'".date('Y-m-d H:i:s')."'";
	
	if ($_POST["rating"]) {
		$rating = $_POST["rating"];
	} else {
		$rating = "null";
	}
	
	if ($_POST["comment"]) {
		$comment = '"'.$_POST["comment"].'"';
	} else {
		$comment = "null";
	}
    $user = 'root';
    $password = 'root';
    $db = 'CS143';
    $host = 'localhost';
    $port = 8889;
    $db_connection = mysqli_connect($host, $user, $password, $db, $port);
    
	$reviewQuery = "INSERT INTO Review VALUES($name,$reviewDate, $movieID, $rating,$comment)";
	$reviewResult = mysqli_query($db_connection, $reviewQuery);
	if ($reviewResult) {
		echo "<br><font color = red>SUCCESS!</font><br>";
	} else {
		echo "FAILED<br>";
		echo mysqli_error($db_connection);
    }
 ?>
 </div>
</body>



</html>