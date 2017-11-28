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
	background-color: white;
	width: 100%;
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
    padding: 145px;
	height:100vh;
}

/* Left column */
.column.left {
    width: 40%;
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
</div>

<div class="w3-content" style="max-width:2000px;margin-top:46px">
 <div class = "column left" style="background-color:black">
	<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<center>
	<SELECT name = "personType">
	<OPTION SELECTED DISABLED HIDDEN> 
	<?php 
	if ($_GET["personType"]) {
		echo $_GET["personType"];
	} elseif ($_GET["personTypeChosen"]) {
		echo $_GET["personTypeChosen"];
	} else {
		echo "Please Select";
	}
	?> 
	</OPTION>
	<OPTION> Actor </OPTION>
	<OPTION> Director </OPTION>
	</SELECT>
	<?php 
		if (!$_GET["personType"]) {
			$_GET["personType"] = $_GET["personTypeChosen"];
		}
	?>
	<br><br>
	<INPUT TYPE = "submit" VALUE = "SUBMIT">
	</center>
	</form>
 </div>

	<?php
		$attributesForPerson = array("Last Name", "First Name", "Sex", "Date of Birth", "Date of Death");
		// function to display the schema of a table
		function getSchema($tableName, $db_connection) {
			$attributes = array(array()); // $attributes[0] for attribute name, $attributes[1] for type
			$query = "DESCRIBE $tableName";
			$result = mysqli_query($db_connection, $query);
			$i = 0;
			while ($row = mysqli_fetch_row($result)) {
				$attributes[0][$i] = $row[0];
				$attributes[1][$i] = $row[1];
				$i++;
			}	
			return $attributes;
		}
		// function to get the maxPersonID
		function maxPersonID($db_connection) {
			$query = "SELECT * FROM MaxPersonID";
			$result = mysqli_query($db_connection, $query);
			$row = mysqli_fetch_row($result);
			return $row[0];
		}
		// function to update maxPersonID: ONLY USED AFTER INSERTION
		function updateMaxPersonID($db_connection) {
			$query = "UPDATE MaxPersonID SET id = id + 1";
			$result = mysqli_query($query, $db_connection);
		}
	?>
 <div class = "column right">
	<?php
        $PORT = 'localhost:8889';
        $USER = 'root';
        $PASSWORD = 'root';
        $DB = 'CS143';
		if ($_GET["personType"]) {
			$personType = $_GET["personType"];
            $user = 'root';
            $password = 'root';
            $db = 'CS143';
            $host = 'localhost';
            $port = 8889;
            $db_connection = mysqli_connect($host, $user, $password, $db, $port);
            
			$attributes = getSchema($personType, $db_connection);
			$maxPersonID = maxPersonID($db_connection);
			$maxPersonID = $maxPersonID + 1;
			echo "<form method = get action = $_SERVER[PHP_SELF]>
				  <table width='100%' border=1 align='center' cellpadding=5 cellspacing=0>";
			$i = 0; // help to correspond to attributesForPerson
			foreach($attributes[0] as $attribute) {
				if ($attribute == 'id') {
					continue;
				}
				if ($i == 2 && $personType == 'Director') {
					$i++; // director doesnt have sex attribute, so we skip it(its index is 2)
				}
				if ($attribute == 'sex') {
					echo "<tr><td>sex</td><td>
					<input type= radio name=sex value= male checked> Male
					<input type= radio name= sex value=female> Female
					<input type= radio name= sex value=other> Other
					</td></tr>";
					$i++;
					continue;
				}
				echo "<tr>";
				echo "<td>$attributesForPerson[$i]</td>";
				$oldValue = $_GET["$attribute"];
                if ($attribute == 'dod') {
                   echo "<td><input type = text name = $attribute value = $oldValue></td>";   
                } else {
                    echo "<td><input type = text required name = $attribute value = $oldValue ></td>";  
                }
				echo "</tr>";
				$i++;
			}
			echo "</table>";
			echo "<INPUT TYPE= hidden NAME= personTypeChosen VALUE= $_GET[personType]>
				  <br><center><INPUT TYPE = submit VALUE = SUBMIT></center>
				  </form>";	
				  
			// construct the insertion query
			$insertQuery = "INSERT INTO $_GET[personType] VALUES($maxPersonID,";
			$i = 1;// help to know the type (Attention : starts from 1 since 0 represents id)
			foreach($attributes[0] as $attribute) {
				if ($attribute == "id") {
					continue;
				}
				if ($_GET["$attribute"]) {
					if (stristr($attributes[1][$i], "char")) {
						$insertQuery = $insertQuery.'"'."$_GET[$attribute]".'"'.",";
					} elseif ($attribute == 'dob' || $attribute == 'dod') {
						$insertQuery = $insertQuery."'"."$_GET[$attribute]"."',";
					} else {
						$insertQuery = $insertQuery."$_GET[$attribute]".",";
					}
				} else {
					$insertQuery = $insertQuery."null,";
				}
				$i++;
			}
			$insertQuery = substr($insertQuery,0,-1);
			$insertQuery.= ")";
			//finish constructing

			//make sure user has press the submit
			if (!$_GET["personTypeChosen"]) {
				exit();
			}
			$resultAfterInsertion = mysqli_query($db_connection,$insertQuery);
			if ($resultAfterInsertion == false) { 
				exit("<font size = 6>".mysqli_error($db_connection)."</font>");
			}
			$checkQuery = "SELECT * FROM $personType WHERE id = $maxPersonID";
			$checkResult = mysqli_query($db_connection,$checkQuery);
			$rowResultForCheck = mysqli_fetch_row($checkResult);
			echo "<br>";
			echo "<table width='100%' border=1 align='center' cellpadding=5 cellspacing=0>";
			echo "<tr>";
			// output the head of the table
			foreach($attributes[0] as $attribute) {
				echo "<td>";
				echo "$attribute";
				echo "</td>";
			}
			echo "</tr>";
			echo "<tr>";
			// output the inserted values of the table
			foreach($rowResultForCheck as $insertedValue) {
				echo "<td>$insertedValue</td>";
			}
			echo "</tr></table>";
			updateMaxPersonID($db_connection);
		} else {
			echo "<center><font size = 10> Please Search in the Left!</font></center>";
		}
	//echo maxPersonID($db_connection);
?>
</div>
</div>

</body>



</html>