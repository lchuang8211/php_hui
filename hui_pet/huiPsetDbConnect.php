<?php

	// $pdoconn = new PDO('mysql:host=localhost;dbname=appview_data;charset=utf8', 'lchuang', 'asabee1566');
	$UserDB = "user_travel_plan";
	$servername = "localhost";
	$username = "lchuang";
	$password = "asabee1566";
	$dbname = "appview_data";
	$conn;

	function setDBConfig(String $sname, String $uname, String $pwd, String $db){
		global $servername, $username, $password, $dbname;
		$servername = $sname;
		$username = $uname;
		$password = $pwd;
		$dbname = $db;
	}

	function getDBConnect(){
		// Create connection
		global $servername, $username, $password, $dbname, $conn;
		$conn = new mysqli($servername, $username, $password, $dbname);
		$conn->query("SET NAMES UTF8"); // 設定使用 UTF-8 編碼
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			// print_r("Connection successfully" . "<br>");
		}
	}

	function closeDBConnect(){
		global $conn;
		$conn->close();
		// echo 'close DB Connect<br>';
	}

	function createTable(String $sql){
		global $conn;
		$conn->query($sql);
		echo $sql.'<br>';
		// echo 'create table ok<br>';
	}

	function insertDataTable(String $sql){
		global $conn;
		$conn->query($sql);
		// echo $sql.'<br>';
		// echo 'insert data ok<br>';
	}

	function selectDataTable(String $sql){
		global $conn;
		$result = mysqli_query($conn,$sql);
		return $result;
	}


?>