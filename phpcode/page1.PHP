
<!DOCTYPE html>
<html>
    <head> <!--網頁設定 標頭-->
      <meta charset="utf-8">
      <meta name="description" content="傳接資料"> <!--這個網頁的描述/形容-->
      <meta name="keywords" content="html, web, 網頁, 學習, 教學, post, get"> <!--搜尋KEYWORD-->
      <meta name="author" context="huang"> <!--作者是誰-->
      <title>PHP PAGE1</title>
      <style type="text/css">
      	.label_1{
      		font-size: 55px;
      	}
      </style>
      <script>

      </script>
    </head>
    
    <body> <!-- 主要內容 -->
    	
    	<label class="label_1">Page 1</label>
	     <hr>
	     <form action="page2.php" method="post">
	      	<fieldset action>
	      		<div>
	      			<legend>傳值TEST</legend>
		      		<ul>
		      			<li>
		      				<label>input</label>
		      				<input type="text" name="inputTxt">
		      			</li>
		      			<li>
		      				<input type="submit" name="submit" value="送出">
		      			</li>
		      		</ul>
		      		<?php   $number = $_POST['number'];//android將會傳值到number 
		      		echo $number ?>
	      		</div>
	      	</fieldset>
	     </form>
    </body>
</html>
