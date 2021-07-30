<?php 

	$myFile = "台南景點";    //輸入檔案名稱
	$myDir = "jsonfile";	//輸入檔案的路徑(絕對或相對)
	$handle = fopen($myDir."/".$myFile.".json","rb"); // "rb" 讀取二進位檔並寫入資料
	$jsonArray = "";
	while (!feof($handle)) {
		$jsonArray .= fread($handle, 10000);  //讀取檔案複製過去
	}
	fclose($handle);
	$jsonArray = json_decode($jsonArray,JSON_NUMERIC_CHECK); //json解析	
	
	require("dbconnect.php");
	$tmparraySchema =  array(); // 儲存jsonArray的所有欄位名稱 ( Schema )
	// echo count($arraySchema);
	$arraySchema="'";
	$getSchemaDataType=array();  //儲存所有欄位的資料型別
	$sqlTableSchema=""; //儲存要上傳的SQL指令
	foreach ($jsonArray[0] as $t1_schema => $t1_value) {
		//透過 $t1_schema 取得欄位名稱
		$tmparraySchema[count($tmparraySchema)]=$t1_schema;
		//透過 $t1_value/$t2_value 取得欄位的資料型態
		// $getSchemaDataType[count($getSchemaDataType)]=gettype($t1_value);
		$DataType = gettype($t1_value);		
		if ( is_array($t1_value) || is_string($t1_value) ) {  // 含 Array 型別再繼續做判斷
			$t2_SchemaDataType=array();	// 儲存Array欄位的內部資料型別	
			// foreach ($t1_value as $t2_schema => $t2_value) {  //讀取內部陣列的欄位的資料型態
			//  	$t2_SchemaDataType[count($t2_SchemaDataType)]=gettype($t2_value);
			// }
			// $getSchemaDataType[count($getSchemaDataType)-1]=gettype("string");
			$DataType = "text";  //因為MySQL沒有Array的資料型態所以存沒TEXT (STRING)
		}
		$arraySchema=$t1_schema;
		// echo $t1_schema." ";
		$sqlTableSchema= $sqlTableSchema."`".$t1_schema."` ". $DataType ." NOT NULL,";
	}
	$sqlTableSchema = substr($sqlTableSchema,0,-strlen(","));
	echo '<hr>';		
	// print_r($tmparraySchema);echo '<br>';
	// print_r($getSchemaDataType);echo '<br>';
	// print_r($t2_SchemaDataType);echo '<br>';
	echo '<hr>';
	// print_r($sqlTableSchema."<br>");
	echo "<hr>";	
	// $sqlTableSchema= "`".$tmparraySchema[0]."` ".$getSchemaDataType[0]." NOT NULL, ";
	// print_r($sqlTableSchema."<br>");
	// $sqlTableSchema1= "`".$tmparraySchema[1]."` ".$getSchemaDataType[1]." NOT NULL, ";
	// print_r($sqlTableSchema1."<br>");

	//create table if not exists 不存在TABLE則建立
	$sqlCreateTable = "create table if not exists `androiddb`.`autoView1` (". $sqlTableSchema .") ENGINE = InnoDB;";
	print_r($sqlCreateTable."<hr>");
	$conn->query($sqlCreateTable);  //執行自動建立Table
		
	foreach ($jsonArray as $key => $value) {
	$sqlInsertValue="";  // 最後要Insert的值
	$insert_value="";	 // 如果有多重陣列要Insert的值
		if($value != "" && $value!=null){
			foreach ($value as $t1_schema => $t1_value) {	
				if ( is_array($t1_value) )  {  //值內含陣列
					$t2_value_array = "";
					foreach ($t1_value as $key2 => $t2_value) {
						$t2_value_array = $t2_value_array . $t2_value . "、";
					}	
						echo $t1_schema . " : " . substr($t2_value_array, 0, -strlen("、"));
						$insert_value=substr($t2_value_array, 0, -strlen("、"));
				}else{  //單純是值
					echo $t1_schema . " : " . $t1_value;
					$insert_value=$t1_value;
				}
				echo "<br>";
				$sqlInsertValue = $sqlInsertValue . "'". $insert_value ."',";				
			}		
			echo "<hr>";
			$sqlInsertValue = substr($sqlInsertValue,0, -strlen(","));
			$sqlInsertCommand = "INSERT INTO `autoview1` (`id`, `lang`, `name`, `summary`, `introduction`, `open_time`, `district`, `address`, `tel`, `fax`, `lat`, `long`, `services`, `category`, `update_time`) VALUES (".  $sqlInsertValue  .");";			
			$conn->query($sqlInsertCommand);  //執行自動加入資料到autoview		
		}
	}
	
	


?>