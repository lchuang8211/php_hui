<!DOCTYPE html>
<html>
    <head> <!--網頁設定 標頭-->
      <meta charset="utf-8">
      <meta name="description" content="傳接資料"> <!--這個網頁的描述/形容-->
      <meta name="keywords" content="html, web, 網頁, 學習, 教學, post, get"> <!--搜尋KEYWORD-->
      <meta name="author" context="huang"> <!--作者是誰-->
      <title>PHP PAGE2</title>
      <style type="text/css">
      	.label_1{
      		font-size: 25px;
      	}
      </style>
      <script>
      </script>
    </head>
    
    <body> <!-- 主要內容 -->
    	
    	<label class="label_1">Database Data</label>
      	<hr>
      	<fieldset>
      		<div>
      			<ul>
      				<li>
      					<label>Fruitname Table :</label><hr>
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
			                  // echo "Connection successfully"."<br>";
			                }

			                $sql = "select * from fruitname";
			                $result = $conn->query($sql);
			                if ( $result->num_rows > 0) {
			                  // output data of each row
			                  while($row = $result->fetch_assoc()) {
echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " . $row["description"] . "<br>";
			                  }
			                } else {
			                  echo "0 results";
			                } 
			                echo '<hr>';               
                
      					 ?>
      				</li>
      			</ul>
      		</div>
      	</fieldset>
      	
    </body>
</html>
