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
hr.style-two {
    height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
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
 <form method = "get" action = "browseMovie.php?<?php echo $_GET["search"];?>">
 <center><font size = 4 color = "white">Search Movies!</font><center>
 <br><br>
 <input type="text" name="search" placeholder="Search.." value = "<?php echo $_GET["search"];?>">
 <br><br>
 <input type = "submit" value = "GO!">
 </form>
</div>
<div class = "column right">
<?
	if (!$_GET["identifier"]) {
		exit();
	}
?>
<?php
    $user = 'root';
    $password = 'root';
    $db = 'CS143';
    $host = 'localhost';
    $port = 8889;
    $db_connection = mysqli_connect($host, $user, $password, $db, $port);
    
	$movieID = $_GET["identifier"];
	$movieQuery = "SELECT * FROM Movie WHERE id = $movieID";
	$movieResult = mysqli_query($db_connection, $movieQuery);

	if(mysqli_num_rows($movieResult)==0){
		exit("No Match Found");
	}	
	$movieRow = mysqli_fetch_row($movieResult);
	$movieTitle = $movieRow[1];
	$movieYear = $movieRow[2];
	$movieRating = $movieRow[3];
	$movieCompany = $movieRow[4];
	
	$movieGenreQuery = "SELECT * FROM MovieGenre WHERE mid = $movieID";
	$movieGenreResult = mysqli_query($db_connection, $movieGenreQuery);
	$movieGenreRow = mysqli_fetch_row($movieGenreResult);
	$movieGenre = $movieGenreRow[1];
	
	$movieDirectorQuery = "SELECT CONCAT(first,' ',last) as DirectorName, id FROM Director join MovieDirector on id = did WHERE mid = $movieID";
	$movieDirectorResult = mysqli_query($db_connection, $movieDirectorQuery);
	$movieDirectorRow = mysqli_fetch_row($movieDirectorResult);
	$movieDirector = $movieDirectorRow[0];

	$movieActorQuery = "SELECT CONCAT(first,' ',last) as ActorsName, role ,id FROM MovieActor join Actor on aid = id WHERE mid = $movieID";
	$movieActorResult = mysqli_query($db_connection, $movieActorQuery);
	
	$movieScoreQuery = "SELECT AVG(rating), COUNT(*) FROM Review WHERE mid = $movieID GROUP BY mid";
	$movieScoreResult = mysqli_query($db_connection, $movieScoreQuery);
	$movieScoreRow = mysqli_fetch_row($movieScoreResult);
	$movieScore = $movieScoreRow[0];
	$reviewNums = $movieScoreRow[1];
	if (!$reviewNums || !$movieScore) {
		$averageScore = "No Average score yet";
	} else {
		$averageScore = "The average rating of $movieTitle is <font color = blue>$movieScore</font> based on <font color = blue>$reviewNums</font> reviews";
	}
	
	$movieReviewQuery = "SELECT name, time, comment FROM Review WHERE mid = $movieID";
	$movieReviewResult = mysqli_query($db_connection, $movieReviewQuery);
	
 ?>

 <font size = 5> Movie Information</font><br>
 <hr class = "style-two">
 <table>
  <tr><td>MovieTitle</td><td><?php echo $movieTitle ?></td><tr>
  <tr><td>Production Year</td><td><?php echo $movieYear ?></td><tr>
  <tr><td>MPAA Rating</td><td><?php echo $movieRating ?></td><tr>
  <tr><td>Producer</td><td><?php echo $movieCompany ?></td><tr>
  <tr><td>Director</td><td><?php echo $movieDirector ?></td><tr>
  <tr><td>Genre</td><td><?php echo $movieGenre ?></td><tr>
  
 </table>
 <hr class = "style-two">
 <font size = 5> Related Actors</font><br>
 <hr class = "style-two">
 <table>
  <tr><td>Actor</td><td>Role</tr>
 <?php
	while ($movieActorRow = mysqli_fetch_row($movieActorResult)) {
		echo "<tr><td><a href=showActor.php?identifier=$movieActorRow[2]>$movieActorRow[0]</a></td><td>$movieActorRow[1]</td></tr>";
	}
 ?>
 </table>
 <hr class = "style-two">
 <font size = 5> Average Score</font><br>
 <hr class = "style-two">
 <?php echo $averageScore;?>
 <hr class = "style-two">
 <font size = 5> User Comments</font><br>
 
 <?php
	while ($movieReviewRow = mysqli_fetch_row($movieReviewResult)) {
		echo "$movieReviewRow[0] comments at $movieReviewRow[1] <br>";
		echo "$movieReviewRow[2]<br>";
		echo "<hr class = style-one>";
	}
 ?>
 <hr class = "style-two">

 <font size = 5 >Add Your Review?</font><br>
 <button type="button"><a href="singleReviewInput.php?identifier=<?php echo $movieRow[0]?>">Click Me!</a></button>
</div>
</body>

</html>