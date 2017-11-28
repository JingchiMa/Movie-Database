<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="font.css">
<link rel="stylesheet" href="cdnjs.css"><style>
input[type=text] {
    padding: 12px 25px;
    margin: 5px 0;
	background-color: white;
	width: 100%;
}

fieldset { 
    display: block;
	width: 100%;
    margin-left: 3px;
    margin-right: 3px;
    padding-top: 0.35em;
    padding-bottom: 0.625em;
    padding-left: 0.75em;
    padding-right: 0.75em;
    border: 5px groove (internal value);
	color : white;
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
    padding: 145px;
	height:100vh;
	overflow:auto;
}

/* Left column */
.column.left {
    width: 40%;
	height:100vh;
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
<form method = "GET" action = "movieInput.php">
<div class="w3-content" style="max-width:2000px;margin-top:46px">
 <div class = "column left" style="background-color:black">
 <font size = 5 color = "yellow">Required Info:</font>
 <input type = "text" name = "title" placeholder = "Input Movie Title" value = "<?php echo $_GET["title"];?>">
 <input type = "text" name = "year" placeholder = "Input Year" value = "<?php echo $_GET["year"];?>">
 <br><br><br>

 <font size = 5 color = "yellow">Movie Genre:</font>
 <input type = "text" name = "genreInput" placeholder = "Input Genre" value = "<?php echo $_GET["genre[]"]; ?>">
    <fieldset>
	 <input type="checkbox" name="genre[]" value="Action">Action</input><br>
     <input type="checkbox" name="genre[]" value="Adult">Adult</input><br>
	  <input type="checkbox" name="genre[]" value="Adventure">Adventure</input><br>
      <input type="checkbox" name="genre[]" value="Animation">Animation</input><br>
	   <input type="checkbox" name="genre[]" value="Comedy">Comedy</input><br>
	   <input type="checkbox" name="genre[]" value="Crime">Crime</input><br>
	   <input type="checkbox" name="genre[]" value="Documentary">Documentary</input><br>
	   <input type="checkbox" name="genre[]" value="Drama">Drama</input><br>
	   <input type="checkbox" name="genre[]" value="Family">Family</input><br>
	   <input type="checkbox" name="genre[]" value="Fantasy">Fantasy</input><br>
	   <input type="checkbox" name="genre[]" value="Horror">Horror</input><br>
	   <input type="checkbox" name="genre[]" value="Musical">Musical</input><br>
	   <input type="checkbox" name="genre[]" value="Mystery">Mystery</input> <br>
	   <input type="checkbox" name="genre[]" value="Romance">Romance</input><br>
	   <input type="checkbox" name="genre[]" value="Sci-Fi">Sci-Fi</input><br>
       <input type="checkbox" name="genre[]" value="Short">Short</input><br>
	   <input type="checkbox" name="genre[]" value="Thriller">Thriller</input><br>
       <input type="checkbox" name="genre[]" value="War">War</input><br>
	   <input type="checkbox" name="genre[]" value="Western">Western</input><br>
      </fieldset>
 </div>
 <div class = "column right" style="background-color:white">
  <font size = 5>Movie Rating:</font>
   <select name="rating">
    <option selected value="G">G</option>
    <option value="NC-17">NC-17</option>
    <option value="PG">PG</option>
    <option value="PG-13">PG-13</option>
    <option value="R">R</option>
    <option value="surrendere">surrendere</option>
   </select>
  <br><br>
  <font size = 5>Company:</font>
  <input type = "text" name = "company" value = "<?php echo $_GET["company"];?>">
  <br><br>
  <center><input type = "submit" value = "ADD!"><center>
</form>
<br><br>
  <?php
	  function maxMovieID($db_connection) {
		  $query = "SELECT * FROM MaxMovieID";
		  $result = mysqli_query( $db_connection, $query);
		  $row = mysqli_fetch_row($result);
		  return $row[0];
	  }
	  // function to update maxMovieID: ONLY USED AFTER INSERTION
	  function updateMaxMovieID($db_connection) {
		  $query = "UPDATE MaxMovieID SET id = id + 1";
		  $result = mysqli_query($db_connection, $query);
	  }
      if (!$_GET["title"] || !$_GET["year"]) {
		  exit("<font color = red >Please Input at least Movie Title and Production year</font>");
	  }
      $user = 'root';
      $password = 'root';
      $db = 'CS143';
      $host = 'localhost';
      $port = 8889;
      $db_connection = mysqli_connect($host, $user, $password, $db, $port);
      
	  $maxMovieID = maxMovieID($db_connection);
	  $maxMovieID = $maxMovieID + 1;
	  $title = '"'.$_GET["title"].'"';
	  $year = "'".$_GET["year"]."'";
	  $rating = '"'.$_GET["rating"].'"';
	  $company = '"'.$_GET["company"].'"';
	  $genreArray = $_GET["genre"];
	  $genre.='"';
	  foreach ($genreArray as $genreType) {
		  $genre = $genre.$genreType." ";
	  }
	  $genre .= '"';
	  $movieInputQuery = "INSERT INTO Movie VALUES($maxMovieID, $title, $year, $rating, $company)";
	  $movieGenreQuery = "INSERT INTO MovieGenre VALUES($maxMovieID,$genre)";
	  $movieInputResult = mysqli_query($db_connection,$movieInputQuery);
	  $movieGenreResult = mysqli_query($db_connection,$movieGenreQuery);
	  if ($movieInputResult) {
		  echo "<center>SUCCESS!</center><br>";
		  $naturalJoinQuery = "SELECT id, title, year,rating,company,genre FROM Movie join MovieGenre on mid = id WHERE id = $maxMovieID";
		  $naturalJoinResult = mysqli_query($db_connection, $naturalJoinQuery);
		  $naturalJoinRow = mysqli_fetch_row($naturalJoinResult);
		  echo "Movie Information";
		  echo "<table width='100%' border=1 align='center' cellpadding=5 cellspacing=0>";
		  echo "<tr><td>id</td><td>title</td><td>year</td><td>rating</td><td>company</td><td>genre</td></tr>";
		  echo "<tr>";
		  foreach($naturalJoinRow as $value) {
			  echo "<td>$value</td>";
		  }
		  echo "</tr></table><br>";
		  updateMaxMovieID($db_connection);
	  } else {
		  exit(mysqli_error($db_connection));
	  }
  ?>
 </div>
</div>
</body>
</html>