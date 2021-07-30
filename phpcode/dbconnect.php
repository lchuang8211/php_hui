
		<?php 

			// $pdoconn = new PDO('mysql:host=localhost;dbname=appview_data;charset=utf8', 'lchuang', 'asabee1566');
			// $UserDB="user_travel_plan";
			$servername = "localhost";
			$username = "lchuang";
			$password = "asabee1566";
			$dbname = "appview_data";
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} else{
					// print_r("Connection successfully"."<br>");
				}


		?>