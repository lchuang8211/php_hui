<?php
//設定header 
    header("Content-type:text/html; charset=utf-8");  //Content-type:text/json
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');
// $uploaddir = 'C:\xampp\htdocs\getfile';
$uploaddir = 'D:\xampp\htdocs\getfile';
// ini_set('file_uploads','ON');
// echo ini_set('file_uploads');

if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
	if ($_FILES['userfile']['error'] > 0)
    {
    	echo "Return Code:" . $_FILES["userfile"]["error"] . "<br>";
    }
	$uploadfile = $uploaddir."\\".basename($_FILES['userfile']['name']);
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		echo "Upload OK : ";
	} else {
		echo "Upload failed<br>";
	}

	require("removeBOM.php");

	$myFile = substr(basename($_FILES['userfile']['name']),0,-strlen(".json"));    //輸入檔案名稱
	echo $myFile."<br>";
	$myDir = $uploaddir;	//輸入檔案的路徑(絕對或相對)
	$handle = fopen($uploaddir."\\".$myFile.".json","rb"); // "rb" 讀取二進位檔並寫入資料

	$jsonArray = "";
	while (!feof($handle)) {
		$jsonArray .= fread($handle, 10000);  //讀取檔案複製過去
	}
	fclose($handle);
	
	// $jsonArray = urldecode($jsonArray);   // 必要條件 避免中文亂碼

    if(substr($jsonArray,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
    	$jsonArray = substr($jsonArray,3);

	// $jsonArray = json_encode($jsonArray);
	$jsonArray = json_decode($jsonArray,JSON_NUMERIC_CHECK | true); //json解析	

	function printValues($arr) {
	    global $count;
	    global $values;
	    
	    // Check input is an array
	    if(!is_array($arr)){
	        die("ERROR: Input is not an array");
	    }
	    
	    /*
	    Loop through array, if value is itself an array recursively call the
	    function else add the value found to the output items array,
	    and increment counter by 1 for each value found
	    */
	    foreach($arr as $key=>$value){
	        if(is_array($value)){
	            printValues($value);
	        } else{
	            $values[] = $value;
	            $count++;
	        }
		}	

    
	    // Return total count and values found in array
	    return array('total' => $count, 'values' => $values);
	}
	// $jsonArray=printValues($jsonArray);

	// str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $jsonArray);
	// var_dump($jsonArray);

	require("dbconnect.php");

	$tmparraySchema =  array(); // 儲存jsonArray的所有欄位名稱 ( Schema )
	// echo count($arraySchema);
	$arraySchema="";
	$getSchemaDataType=array();  //儲存所有欄位的資料型別
	$sqlTableSchema=""; //儲存要上傳的SQL指令
	$sqlInsertSchema="";
	echo "<hr>";
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
		if ($DataType=='NULL') 
			$DataType = "text";
		$arraySchema = $t1_schema;
		// echo $t1_schema." ";
		$sqlTableSchema = $sqlTableSchema."`".$t1_schema."` ". $DataType ." NOT NULL,";
		$sqlInsertSchema =$sqlInsertSchema . "`".$t1_schema."`,";
	}
	$sqlTableSchema = substr($sqlTableSchema,0,-strlen(","));
	$sqlInsertSchema = substr($sqlInsertSchema,0,-strlen(","));
	// echo '<hr>';		
	// print_r($tmparraySchema);echo '<br>';
	// print_r($getSchemaDataType);echo '<br>';
	// print_r($t2_SchemaDataType);echo '<br>';
	// echo '<hr>';
	// print_r($sqlTableSchema."<br>");
	// echo "<hr>";	
	// $sqlTableSchema= "`".$tmparraySchema[0]."` ".$getSchemaDataType[0]." NOT NULL, ";
	// print_r($sqlTableSchema."<br>");
	// $sqlTableSchema1= "`".$tmparraySchema[1]."` ".$getSchemaDataType[1]." NOT NULL, ";
	// print_r($sqlTableSchema1."<br>");

	// create table if not exists 不存在TABLE則建立
	$tableName = $myFile;
	$sqlCreateTable = "create table if not exists `appview_data`.`".$tableName."` (". $sqlTableSchema .") ENGINE = InnoDB;";
	// // print_r($sqlCreateTable."<hr>");
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
						// echo $t2_value_array;
					}	
						echo $t1_schema . " : " . substr($t2_value_array, 0, -strlen("、"));
						$insert_value=substr($t2_value_array, 0, -strlen("、"));
						// echo $insert_value;
				}else{  //單純是值
					echo $t1_schema . " : " . $t1_value;
					$insert_value=$t1_value;
					// echo $insert_value;
				}
				// echo $insert_value;
				echo "<br>";
				$sqlInsertValue = $sqlInsertValue . "'". $insert_value ."',";
			}		
			echo "<hr>";			
			$sqlInsertValue = substr($sqlInsertValue,0, -strlen(","));
			$sqlInsertCommand = "INSERT INTO `".$dbname."`.`".$tableName."` (".$sqlInsertSchema.") VALUES (".  $sqlInsertValue  .");";
			$conn->query($sqlInsertCommand);
			// echo $sqlInsertCommand ;
		}

	}
}
?>