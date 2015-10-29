<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script srt="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/is/bootstrap.min.is"></script>
		<style type="text/css"></style>
		<title>Lab 7 Index</title>
	</head>
	<body>
		<div class="container">
			<br>
			<br>
			<div class="row">
				<form action="/lab7/index.php" method="POST" class="col-md-4 col-md-offset-4">
					<div class="row>   <!-- Text box and "Go" button setup -->
						<input class="col-md-12" type="text" name="userinput">
						<input class="btn btn-info col-md-5" type="submit" name="submit" value="Go">
						<a href="insert.php" class="btn btn-primary col-md-5">Insert into city</a>
						<a href="info.php" class="btn btn-default col-md-2">Info</a>
					</div>
					<div class="row">
						<p><input class="col-md-1" checked="check" type="radio" name="radios" value="0">City</p>
						<p><input class="col-md-1" type="radio" name="radios" value="1">Country</p>
						<p><input class="col-md-1" type="radio" name="radios" value="2">Language</p>
					</div>
				</form>
			</div>
			<?php
				if(isset($_POST['submit'])) {
					$link = mysqli_connect("us-cdbr-azure-central-a.cloudapp.net", "bbe6dde30dae93", "143d52a48c68c3f", "cs3380-njzcpb") or die ("Connection Error " . mysqli_error($link));
					switch($_POST['radios']){
						case 0:
							$sql = "SELECT * FROM City WHERE Name LIKE ? ORDER BY Name;";
							$table = "city";
							break;
						case 1:
							$sql = "SELECT * FROM Country WHERE Name LIKE ? ORDER BY Name;";
							$table = "country";
							break;
						case 2:
							$sql = "SELECT * FROM countrylanguage WHERE Language LIKE ? ORDER BY Language;";
							$table = "countryLanguage";
							break;
						default:
							break;
					}
					echo "<br>";
					$stmt = mysqli_prepare($link, $sql);
					$stmt->bind_param("s", $name);
					$name = htmlspecialchars($_POST['userinput']."%");
					echo "Searching for ".$name.".";
					if($stmt->execute()){
						echo "<br>Execution successful.<br>";
					}
					//$result = mysqli_query($link, $sql) or die ("Query Error: ".mysqli_error($link));
					
					
					//$stmt->bind_result($result);
					if($result = $stmt->get_result()){
						echo "Result is set.";
						echo "<h4>Number of rows: ".mysqli_num_rows($result)."</h4>";
					} //end while	
			?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th></th>
									<th></th>
						<?php
									switch($_POST['radios']){
										case 0:		
						?>					<th>ID</th>
											<th>Name</th>
											<th>Country Code</th>
											<th>District</th>
											<th>Population</th>
						<?php				break;
										case 1:	
						?>					<th>Code</th>
											<th>Name</th>
											<th>Continent</th>
											<th>Region</th>
											<th>Surface Area</th>
											<th>Indep Year</th>
											<th>Population</th>
											<th>Life Expectancy</th>
											<th>GNP</th>
											<th>GNP Old</th>
											<th>Local Name</th>
											<th>Form of Govt</th>
											<th>Head of State</th>
											<th>Capital</th>
											<th>Code2</th>
						<?php				break;		
										case 2:				
						?>					<th>Country Code</th>
											<th>Language</th>
											<th>Is Official?</th>
											<th>Percentage</th>
						<?php				break;
										default:
											break;
									}
						?>
								</tr>
							</thead>
							<tbody>
							<?php
								while($row = $result->fetch_assoc()) {
							?>
								<form action="edit.php" method="POST">
								<?php
									echo '<input class="hidden" name="table" value="'.$table.'">';
								?>
									<tr>
										<td><input class="btn btn-info" type="submit" name="update" value="Update"></td>
										<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>
								<?php
									foreach($row as $k=>$field) {
										echo '<td><input type="hidden" name="'.$k.'" value="'.$field.'">'.$field.'</td>';
									} //end foreach
								?>	
									</tr>
								</form>
							<?php
								} //end while
							?>
						</tbody>
					</table>
			<?php 
				} //end if
				mysqli_close($link);
			?>
		</div>
	</body>
</html>