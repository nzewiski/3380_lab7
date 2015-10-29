<?php ob_start(); ?>

<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script srt="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/is/bootstrap.min.is"></script>
		<style type="text/css"></style>
		<title>Insert</title>
	</head>
	<body>
		<div class="container">
			<?php $link = mysqli_connect("us-cdbr-azure-central-a.cloudapp.net", "bbe6dde30dae93", "143d52a48c68c3f", "cs3380-njzcpb") or die ("Connection Error " . mysqli_error($link)); ?>
			<br>
			<br>
			<div class="row">
				<form action="/lab7/insert.php" method="POST" class="col-md-4 col-md-offset-4">
					<div class="row">
						<div class="input group">
							<div class="form-group">
								<label class="inputdefault">Name</label>
								<input class="form-control" type="text" name="Name" value/>
							</div>
							<div class="form-group">
								<label class="inputdefault">District</label>
								<input class="form-control" type="text" name="District" value/>
							</div>
							<div class="form-group">
								<label class="inputdefault">Population</label>
								<input class="form-control" type="text" name="Population" value/>
							</div>
							<div class="form-group">
								<label class="inputdefault">CountryCode</label>
								
									<?php
									$sql = "SELECT DISTINCT Country.Code, Country.Name FROM Country JOIN City WHERE Code=CountryCode;";
									if($stmt = mysqli_prepare($link, $sql)){
										if($stmt->execute()){
											$result = $stmt->get_result();
										}
									} else {
										echo "Execution error";
									}
									?>
								<select name="CountryCode">
								<?php	
									while($fieldinfo = $result->fetch_assoc()){
										foreach($fieldinfo as $i=>$row){
											if($i == "Code"){
												$c = $row;
											}
											else{
												echo "<option value=".$c.">".$row."</option>";
											}
										} //end foreach
									} //end while
									?>
								</select>
							</div>
							<input class="btn btn-info" type="submit" name="submit" value="Go">
						</div>
					</div>
				</form>
				<a href="index.php" class="btn btn-primary">Back to index</a>
			</div>
			<?php
				if(isset($_POST['submit'])){
					$istmt = mysqli_prepare($link, "INSERT INTO City (ID, Name, CountryCode, District, Population) VALUES (default, ?, ?, ?, ?);");
					if($istmt->bind_param("sssi", htmlspecialchars($_POST['Name']), htmlspecialchars($_POST['CountryCode']), htmlspecialchars($_POST['District']), htmlspecialchars($_POST['Population']))){
						echo "bind successful";
					} else {
						echo "bind error";
					}
					if($istmt->execute()) {
						header('Location: http://cs3380-njzcpb.cloudapp.net/lab7/success.php');
					} //end if
					else {
						echo "<p>Insertion error: no data added to table</p>";
					} //end else
				} //end if
			?>
		</div>
	</body>
</html>

<?php 
mysqli_close($link);
ob_end_flush(); 
?>