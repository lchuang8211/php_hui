<!DOCTYPE html>
<html>
    <head> <!--網頁設定 標頭-->
      <meta charset="utf-8">     
      <title>檔案上傳</title>
      <style>
      
      </style>
      <script>

      </script>
    </head>
<body>
	<h1>上傳JSON檔到資料庫</h1>
	
		<p>請選擇.json檔案</p>
		<!--loadfilejsonArray hhlc.ddnsking.com-->
		<form action="http://127.0.0.1/gsonTest.php" method="post" enctype="multipart/form-data">
			
			<input type="file" name="userfile"/><br>
			<input type="submit" value="submit" name="submit">
			
		</form>
		<form method="post" enctype="text/plain">
			<input action="http://127.0.0.1/gsonTest.php" type="submit" value="檢查(沒作用)" name="checkTable">
			<?php
				if(isset($_POST['checkTable'])){
					$this->checkTable();
					print_r("123");
				}
				function checkTable(){
					print_r("123");
				}				
			?>
			</form>
    </body>
</html>

