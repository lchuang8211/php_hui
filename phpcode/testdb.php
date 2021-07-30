
		<?php 

			$servername = "localhost";
			$username = "lchuang";
			$password = "asabee1566";
			$dbname = "androiddb";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else{
					echo "Connection successfully"."<br>";
				}

		?>