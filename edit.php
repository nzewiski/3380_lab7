<?php
ob_start();
//connect to db
$link = mysqli_connect("us-cdbr-azure-central-a.cloudapp.net", "bbe6dde30dae93", "143d52a48c68c3f", "cs3380-njzcpb") or die ("Connection Error " . mysqli_error($link));

//display non-editable textbox for attribute $key
function printNonEditable($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='text' name='".$key."' value='".$_POST[$key]."' readonly>";
	echo "</div>";
}

//display editable textbox for attribute $key
function printInput($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='text' name='".$key."' value='".$_POST[$key]."' required>";
	echo "</div>";
}

//display editable textbox for numeric attribute $key
function printNumeric($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='number' name='".$key."' value='".$_POST[$key]."' required>";
	echo "</div>";
}

//editable form for records from the city table
function displayCity() {
	echo "<form action='edit.php' method='POST' >";
	echo "<input type='hidden' name='table' value='city'>";
	printNonEditable('ID');
	printNonEditable('Name');
	printNonEditable('CountryCode');
	printInput('District');
	printNumeric('Population');
	echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
	echo "<a class='btn btn-danger' href='index.php'>Cancel</a>";
	echo "</form>";
}

//editable form for records from the country table
function displayCountry() {
	echo "<form action='edit.php' method='POST' >";
	echo "<input type='hidden' name='table' value='country'>";
	printNonEditable('Code');
	printNonEditable('Name');
	printNonEditable('Continent');
	printNonEditable('Region');
	printNonEditable('SurfaceArea');
	printNumeric('IndepYear');
	printNumeric('Population');
	printNonEditable('LifeExpectancy');
	printNonEditable('GNP');
	printNonEditable('GNPOld');
	printInput('LocalName');
	printInput('GovernmentForm');
	printNonEditable('HeadOfState');
	printNonEditable('Capital');
	printNonEditable('Code2');
	echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
	echo "<a class='btn btn-danger' href='index.php'>Cancel</a>";
	echo "</form>";
}

//editable form for records from the countrylanguage table
function displayCountryLanguage() {
	echo "<form action='edit.php' method='POST' >";
	echo "<input type='hidden' name='table' value='countryLanguage'>";
	printNonEditable('CountryCode');
	printNonEditable('Language');
	printInput('IsOfficial');
	printNumeric('Percentage');
	echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
	echo "<a class='btn btn-danger' href='index.php'>Cancel</a>";
	echo "</form>";
}

//no table was provided, display error message
function fail() {
	header("Location: fail.php");
}

function success() {
	header("Location: success.php");
}

//save changed city values
function saveCity() {
	global $link;
	$sql = "UPDATE city SET District=?, Population=? WHERE id=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "sss", htmlspecialchars($_POST['District']), htmlspecialchars($_POST['Population']), htmlspecialchars($_POST['ID'])) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			success();
		} else { 
			fail(); 
		}
	} else { //prepare failed
		fail(); 
	}
}

//save changed country values
function saveCountry() {
	global $link;
	$sql = "UPDATE country SET IndepYear=?, Population=?, LocalName=?, GovernmentForm=? WHERE Code=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "sssss", htmlspecialchars($_POST['IndepYear']), htmlspecialchars($_POST['Population']), htmlspecialchars($_POST['LocalName']), htmlspecialchars($_POST['GovernmentForm']), htmlspecialchars($_POST['Code'])) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			success();
		} else { 
			fail(); 
		}
	} else { //prepare failed
		fail(); 
	}
}

//save changed language values
function saveCountryLanguage() {
	global $link;
	$sql = "UPDATE countrylanguage SET IsOfficial=?, Percentage=? WHERE CountryCode=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "sss", htmlspecialchars($_POST['IsOfficial']), htmlspecialchars($_POST['Percentage']), htmlspecialchars($_POST['CountryCode'])) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			success();
		} else { 
			fail(); 
		}
	} else { //prepare failed
		fail(); 
	}
}
?>

<html>
	<head>
		<!--  I USE BOOTSTRAP BECAUSE IT MAKES FORMATTING/LIFE EASIER -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"><!-- Optional theme -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script><!-- Latest compiled and minified JavaScript -->
	</head>
	<body>
		<div class="container">

<?php
	
	if(isset($_POST['update'])) {//submit came from index.php
		if(isset($_POST['table'])) {//do we have table information?
			switch($_POST['table']) {//what table are we updating
				case "city":
					echo "<h2>Edit City Record</h2>";
					displayCity();
					break;
				case "country":
					echo "<h2>Edit Country Record</h2>";
					displayCountry();
					break;
				case "countryLanguage":
					echo "<h2>Edit Country Language Record</h2>";
					displayCountryLanguage();
					break;
				default:
					fail();
					break;
			}	
		} else {//no table info found
			noTable();
		}
	} else if(isset($_POST['save'])) {//submit came from request to save form data
		if(isset($_POST['table'])) {//do we have table information?
			switch($_POST['table']) {//what table are we updating
				case "city":
					echo "<h2>Edit City Record</h2>";
					displayCity();
					saveCity();
					break;
				case "country":
					echo "<h2>Edit Country Record</h2>";
					displayCountry();
					saveCountry();
					break;
				case "countryLanguage":
					echo "<h2>Edit Country Language Record</h2>";
					displayCountryLanguage();
					saveCountryLanguage();
					break;
				default:
					//Failed
					fail();
					break;
			}	
		}
	} else if(isset($_POST['delete'])) {
		if(isset($_POST['table'])) {
			echo $_POST['table'];
			switch($_POST['table']) {
				case "city":
					$sql = "DELETE FROM city WHERE id=?;";
					if($stmt = mysqli_prepare($link, $sql)) {
						$stmt->bind_param("s", htmlspecialchars($_POST['ID']));
					} else {
						fail();
					}
					break;
				case "country":
					$sql = "DELETE FROM country WHERE Code=? AND Name=?;";
					if($stmt = mysqli_prepare($link, $sql)) {
						$stmt->bind_param("ss", htmlspecialchars($_POST['Code']), htmlspecialchars($_POST['Name']));
					} else {
						echo "prepare failure";
					}
					break;
				case "countryLanguage":
					$sql = "DELETE FROM countrylanguage WHERE CountryCode=? AND Language=?;";
					if($stmt = mysqli_prepare($link, $sql)) {
						$stmt->bind_param("ss", htmlspecialchars($_POST['CountryCode']), htmlspecialchars($_POST['Language']));
					} else {
						echo "prepare failure";
					}
					break;
				default:
					fail();
					break;
			} //end switch
			if($stmt->execute()){
				success();
			} else {
				echo "execution failure";
			} 
		}
	} 
	mysqli_close($link);
	ob_end_flush();
?>
		</div>
	</body>
</html>