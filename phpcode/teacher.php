<!DOCTYPE html>
<html>
	<head>
		<title>teacher schedule</title>		
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

			$sql="create table teacher(Monday char(1), Tuesday char(1), Wednesday char(1))";

			$sql="insert into teacher values('0', '1', '1')";
			$sql="insert into teacher values('0', '0', '0')";
			$sql="insert into teacher values('0', '1', '1')";
			$sql="insert into teacher values('1', '1', '0')";
			$sql="insert into teacher values('1', '0', '0')";
			$sql="insert into teacher values('0', '1', '1')";
			$sql="sum(teacher.Monday), teacher.Tuesday=count(*), teacher.Wednesday=count(*)";
			$sql="select sum(teacher.Monday), sum(teacher.Tuesday), sum(teacher.Wednesday) from teacher";
			

			// multi_query($sql)


			// if ($conn->query($sql) === TRUE) {
			// 	echo "Record updated successfully"."<br>";
			// } else {
			// 	echo "Error updating record: ". $sql .$conn->error."<br>";
			// }
			// 执行多个 SQL 语句
			// multi_query($sql);
			/*if ($conn->multi_query($sql)){
				do{
					if ($result=$con->store_result()){

					}
				}
			}*/

			/*$sql9="drop table teacher";
			if ($conn->query($sql9) === TRUE) {
				echo "2 Record updated successfully"."<br>";
			} else {
				echo "2 Error updating record: ".$conn->error."<br>";
			}	*/
			
		?>

	</body>
</html>