<!DOCTYPE html>
<html>
	<head>
		<title>try try try</title>		
	</head>
	<body>	
		<?php 
			$servername = "localhost";
			$username = "asamaster";
			$password = "a14332695";
			$dbname = "asabee";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else{
					echo "Connection successfully"."<br>";
				}
			//$sql = "ALTER TABLE `appletest` ADD `weaaa` INT NOT NULL AFTER `peach`";						
			$sql = "UPDATE `appletest` SET `weaaa`='312' WHERE apple = 2";
			$sql = "UPDATE `appletest` SET `weaaa`='921' WHERE apple = 4";
			$sql = "UPDATE `appletest` SET `weaaa`='771' WHERE `appletest`.`apple` = 3";
			$sql = "SELECT apple, banana, pineapple, peach, weaaa FROM appletest";										
			if ($conn->query($sql) === TRUE) {
				echo "Record updated successfully";
			} else {
				echo "Error updating record: ".$conn->error."<br>";
			}	
			$result = $conn->query($sql);
					while($row = $result->fetch_assoc()) {  //fetch_array  fetch_assoc 
						echo "apple: ".$row["apple"]." banana: ".$row["banana"].
						" pineapple: ".$row["pineapple"]." peach: ".$row["peach"]." weaaa: ".$row["weaaa"]."<br>";
					}				
			$conn->close();
		?>
	</body>
</html>
