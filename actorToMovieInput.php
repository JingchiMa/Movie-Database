
<!DOCTYPE html>
<html>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="font.css">
<link rel="stylesheet" href="cdnjs.css">
<style>
input[type=text] {
    padding: 12px 25px;
    margin: 8px 0;
	background-color: black;
    color: white;
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
    padding: 20px 20px;
    border: none;
    border-radius: 4px;
    background-color: #f1f1f1;
}
/* Create two unequal columns that floats next to each other */
.column {
    float: left;
    padding: 145px;
}

/* Left column */
.column.left {
    width: 40%;
	color: white;
}

/* right column */
.column.right {
    width: 60%;
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

<div class="w3-content" style="max-width:2000px;margin-top:46px">	
<div class = "column left" style="background-color:black"> 
	<center>
	<form method = "GET" action = "actorToMovieInput.php">
	 <fieldset>
	  <font size = 5 color = "yellow"><legend>  Actor </legend></font>
	  LastName  <input type = "text" name = "ActorLastName"> <br>
	  FirstName <input type = "text" name = "ActorFirstName"> <br>
	 </fieldset>
	 <fieldset>
	 <font size = 5 color = "yellow"><legend>MovieTitle</legend></font><br>
	 <input type = "text" name = "MovieTitle"> <br>
	 </fieldset>
	 <br><br>
	<input type = "submit" value = "Search"><br>
	<input type = "hidden" name = "hasSubmitted" value = "yes">
	</form>
	</center>
</div>	
<?php
	$queryActor = "SELECT CONCAT(first,' ',last) as ActorsName, dob,id FROM Actor ";
	$queryMovie = "SELECT title, year, id FROM Movie ";
	if (!$_GET["hasSubmitted"]) {
		$_GET["hasSubmitted"] = $_GET["hidHasSubmitted"];
	}
	if ($_GET["ActorLastName"]) {
		$actorLastName = $_GET["ActorLastName"];
		$queryActor = $queryActor."WHERE last LIKE ".'"%'."$actorLastName".'%"';
	} else {
		if ($_GET["hidActorLN"]) {
			$_GET["ActorLastName"] = $_GET["hidActorLN"];
			$actorLastName = $_GET["ActorLastName"];
			$queryActor = $queryActor."WHERE last = ".'"%'."$actorLastName".'%"';
		}
	}
	if ($_GET["ActorFirstName"]) {
		$actorFirstName = $_GET["ActorFirstName"];
		if ($_GET["ActorLastName"]) {
			$queryActor .= " AND ";
		} else {
			$queryActor .= " WHERE ";
		}
		$queryActor = $queryActor."first LIKE ".'"%'."$actorFirstName".'%"';
	} else {
		if ($_GET["hidActorFN"]) {
			if ($_GET["ActorLastName"]) {
				$queryActor .= " AND ";
			} else {
				$queryActor .= " WHERE ";
			}
			$_GET["ActorFirstName"] = $_GET["hidActorFN"];
			$actorFirstName = $_GET["ActorFirstName"];
			$queryActor = $queryActor."first LIKE ".'"%'."$actorFirstName".'%"';
		}
	}
	if ($_GET["MovieTitle"]) {
		$movieTitle = $_GET["MovieTitle"];
		$queryMovie = $queryMovie."WHERE title LIKE ".'"%'."$movieTitle".'%"';
	} else {
		if ($_GET["hidMovieTitle"]) {
			$_GET["MovieTitle"] = $_GET["hidMovieTitle"];
			$movieTitle = $_GET["MovieTitle"];
			$queryMovie = $queryMovie."WHERE title LIKE ".'"%'."$movieTitle".'%"';
		}
	}
    $user = 'root';
    $password = 'root';
    $db = 'CS143';
    $host = 'localhost';
    $port = 8889;
    $db_connection = mysqli_connect($host, $user, $password, $db, $port);
    
	$actorChosen = mysqli_query($db_connection, $queryActor);	
	$movieChosen = mysqli_query($db_connection, $queryMovie);
	$actorArray = array();
	$movieArray = array();

?>	
<br>
<div class = "column right" style="background-color:white">
 	<?php
		if (!$_GET["hasSubmitted"]) {
			exit("<center><font size = 10> Please Search in the Left!</font></center>");
		}
		if (mysqli_num_rows($actorChosen) == 0) {
			exit("Actor Not Found");
		}
		if (mysqli_num_rows($movieChosen) == 0) {
			exit("Movie Not Found");
		}
		echo "<h1><center> Please Select Below <center></h1>";
	?>
	<form method = "GET" action = "actorToMovieInput.php">
	 <center>
	 <font size = 4>Actors List:</font><br>
	 <select name = "actorCandidates">
	  <?php
		$i = 0;
		while ($eachActor = mysqli_fetch_row($actorChosen)) {
			$actorArray[$i] = $eachActor;
			$i++;
			echo "<OPTION> $eachActor[0] ($eachActor[1]) </OPTION>";
		}
	  ?>
	 </select>
	 <font size = 4>Movies List:</font> <br>
	 <select name = "movieCandidates">
	  <?php
	    $i = 0;
		while ($eachMovie = mysqli_fetch_row($movieChosen)) {
			$movieArray[$i] = $eachMovie;
			$i++;
			echo "<OPTION> $eachMovie[0] ($eachMovie[1]) </OPTION>";
		}
	  ?>
	 </select>
	 <br><br>
	 <font size = 4>Role:</font> <br>
	 <input type = "text" name = "role"><br>
	 <input type = "hidden" name = "hidActorLN" value = "<?php echo $_GET["ActorLastName"]?>">
	 <input type = "hidden" name = "hidActorFN" value = "<?php echo $_GET["ActorFirstName"]?>">
	 <input type = "hidden" name = "hidMovieTitle" value = "<?php echo $_GET["MovieTitle"]?>">
	 <input type = "hidden" name = "hidHasSubmitted" value = "yes">
	 <br>
	 <input type = "submit" value = "Add Relation!">
	 </center>
	</form>
	
	<?php
		if ($_GET["actorCandidates"] && $_GET["movieCandidates"]) {
			foreach ($actorArray as $actor) {
				if (strstr($_GET["actorCandidates"], $actor[0]) 
                    && strstr($_GET["actorCandidates"], $actor[1])) {
					$actorID = $actor[2];
				}
			}
			foreach ($movieArray as $movie) {
				if (strstr($_GET["movieCandidates"], $movie[0]) 
                    && strstr($_GET["movieCandidates"], $movie[1])) {
					$movieID = $movie[2];
				}
			}
			$role = '"unknown"';
			if ($_GET["role"]) {
				$role = '"'.$_GET["role"].'"';
			}
			$queryLink = "INSERT INTO MovieActor VALUES($movieID, $actorID,$role)";
			$linkResult = mysqli_query($db_connection, $queryLink);
			if ($linkResult) {
				echo "SUCCESS!<br>";
				echo "$_GET[actorCandidates]";
				echo "acts as $role in $_GET[movieCandidates]<br>"; 
			} else {
				echo "FAILED<br>";
				echo mysqli_error($db_connection);
			}
		}
	?>
</div>
</div>


</body>



</html>