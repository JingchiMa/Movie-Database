<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="font.css">
<link rel="stylesheet" href="cdnjs.css"><style>
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
 <form method = "get" action = "search.php">
 <center><font size = 4 color = "white">Search Actors, Directors and Movies!</font><center>
 <br><br>
 <input type="text" name="search" placeholder="Search.." value = "<?php echo $_GET["search"];?>">
 <br><br>
 <input type = "submit" value = "GO!">
 </form>
</div>
<?php
	function createPersonQuery($searchKeys, $personType) {
		$numOfKeys = count($searchKeys);
		if ($numOfKeys <= 2) {
			$searchActorQuery1 = "SELECT CONCAT(first,' ',last) as ActorName, dob, id FROM $personType ";
			for ($i = 0; $i < $numOfKeys; $i++) {
				if ($i == 0) {
					$searchActorQuery1 .= "WHERE ";
					$searchActorQuery1 = $searchActorQuery1."last LIKE "."'%$searchKeys[$i]%'"." ";
				} else {
					$searchActorQuery1 .= "AND ";
					$searchActorQuery1 = $searchActorQuery1."first Like "."'%$searchKeys[$i]%'"." ";
				}
			}
			$searchActorQuery2 = "SELECT CONCAT(first,' ',last) as ActorName, dob, id FROM $personType ";
			for ($i = 0; $i < $numOfKeys; $i++) {
				if ($i == 0) {
					$searchActorQuery2 .= "WHERE ";
					$searchActorQuery2 = $searchActorQuery2."first LIKE "."'%$searchKeys[$i]%'"." ";
				} else {
					$searchActorQuery2 .= "AND ";
					$searchActorQuery2 = $searchActorQuery2."last Like "."'%$searchKeys[$i]%'"." ";
				}
			}
			$searchActorQuery = $searchActorQuery1." UNION ". $searchActorQuery2;
			$searchActorQuery .= " ORDER BY 'first'";
		}	
		return $searchActorQuery;
	}
	function createMovieQuery($searchKeys) {
		$numOfKeys = count($searchKeys);
		$searchMovieQuery = "SELECT title, year, id FROM Movie ";
		for ($i = 0; $i < $numOfKeys; $i++) {
			if ($i == 0) {
				$searchMovieQuery .= "WHERE ";
			} else {
				$searchMovieQuery .= "AND ";
			}
			$searchMovieQuery = $searchMovieQuery."title LIKE '%$searchKeys[$i]%' ";
		}
		$searchMovieQuery .= " ORDER BY 'title'";
		return $searchMovieQuery;
	}
	function displayTablePerson($result) {
		while ($row = mysqli_fetch_row($result)) {
			echo "<tr><td><a href=showActor.php?identifier=$row[2]>$row[0]</a></td><td>$row[1]</td></tr>";
		}
	}
	function displayTableMovie($result) {
		while ($row = mysqli_fetch_row($result)) {
			echo "<tr><td><a href=showMovie.php?identifier=$row[2]>$row[0]</a></td><td>$row[1]</td></tr>";
		}
	}
?>

<div class = "column right">
<?php
	if (!$_GET["search"]) {
		exit("<center><font size = 10> Please Search in the Left!</font></center>");
	}
    $user = 'root';
    $password = 'root';
    $db = 'CS143';
    $host = 'localhost';
    $port = 8889;
    $db_connection = mysqli_connect($host, $user, $password, $db, $port);

	$searchInput = $_GET["search"];
	$searchKeys = explode(" ", $searchInput);
	$searchActorQuery = createPersonQuery($searchKeys, "Actor");
	$searchDirectorQuery = createPersonQuery($searchKeys, "Director");
	$searchMovieQuery = createMovieQuery($searchKeys);

	$actorsMatchedResult = mysqli_query($db_connection, $searchActorQuery);
	$directorsMatchedResult = mysqli_query($db_connection, $searchDirectorQuery);
	$moviesMatchedResult = mysqli_query($db_connection, $searchMovieQuery);
	echo mysqli_error($db_connection);
?>
 <table>
  <tr>
    <th>Actor Name</th>
    <th>Date of Birth</th>
  </tr>
  <?php
	displayTablePerson($actorsMatchedResult);
  ?>
  </table>
  <table>
 <hr class = "style-one">
  <tr>
    <th>Director Name</th>
    <th>Date of Birth</th>
  </tr>
  <?php
	displayTablePerson($directorsMatchedResult);
  ?>
 </table>
 <hr class = "style-one">
 <table>
  <tr>
    <th>Movie Name</th>
    <th>Production Year</th>
  </tr>
  <?php
	displayTableMovie($moviesMatchedResult);
  ?>
 </table>
</div>

</body>
</html>