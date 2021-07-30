<?php
//設定header
header("Content-type:text/html; charset=utf-8"); //Content-type:text/json
mb_internal_encoding('UTF-8');
mb_http_input("UTF-8");
mb_http_output('UTF-8');

$guest_ip=$_SERVER['REMOTE_ADDR'];
echo "IP是：".$guest_ip."<br />";

echo "HTTP_HOST：".$_SERVER['HTTP_HOST']."<hr />";
echo "SERVER_NAME：".$_SERVER['SERVER_NAME']."<hr />";
echo "REQUEST_URI：".$_SERVER['REQUEST_URI']."<hr />";
echo "PHP_SELF：".$_SERVER['PHP_SELF']."<hr />";
echo "QUERY_STRING：".$_SERVER['QUERY_STRING']."<hr />";
/**
 * $_SERVER['REMOTE_ADDR']：取得訪客IP
 * $_SERVER['HTTP_HOST']：當前請求的Host頭中的內容(與取得Server的Port)
 * $_SERVER['SERVER_NAME']：當前運行網頁檔案所在的主機名稱
 * $_SERVER['REQUEST_URI']：訪問此頁面需要的URL
 * $_SERVER['PHP_SELF']：當前正在執行的網頁檔案名稱
 * $_SERVER['QUERY_STRING']：查詢的變數值
 */
require "dbconnect.php";
?>

<script>
    var tt = new XMLHttpRequest();

</script>

<?PHP
$uploaddir = 'D:\xampp\htdocs\getfile';
// ini_set('file_uploads','ON');
// echo ini_set('file_uploads');

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

    if ($_FILES['userfile']['error'] > 0) {
        echo "Return Code:" . $_FILES["userfile"]["error"] . "<br>";
    }
    $uploadfile = $uploaddir . "\\" . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "Upload OK : ";
    } else {
        echo "Upload failed<br>";
    }

    require "removeBOM.php";

    $myFile = substr(basename($_FILES['userfile']['name']), 0, -strlen(".json")); //輸入檔案名稱

    echo $myFile . "<br>";
    $myDir = $uploaddir; //輸入檔案的路徑(絕對或相對)
    $handle = fopen($uploaddir . "\\" . $myFile . ".json", "rb"); // "rb" 讀取二進位檔並寫入資料

    $jsonArray = "";
    while (!feof($handle)) {
        $jsonArray .= fread($handle, 10000); //讀取檔案複製過去
    }
    fclose($handle);

    $jsonArray = urldecode($jsonArray); // 必要條件 避免中文亂碼
    
    //去BOM檔頭
    if (substr($jsonArray, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
        $jsonArray = substr($jsonArray, 3);
    }

    // var_dump($jsonArray);
    // $jsonArray = utf8_encode($jsonArray);
    $jsonArray = mb_convert_encoding($jsonArray,'UTF-8','UTF-8');
    $jsonArray = json_decode($jsonArray, JSON_NUMERIC_CHECK | true); //json解析
    // $jsonArray = json_decode($jsonArray);
    echo '<hr>';
    // var_dump($jsonArray);
    echo json_last_error() . "<br>";
    echo json_last_error_msg() . "<br>";
    // var_dump($jsonArray);
    
    // echo urldecode($jsonArray);

    // require "gsonInclude.php";

    // echo 'jsonarray size: '.$jsonArray.'<br>';

    if (is_array($jsonArray)) {
        echo 'jsonarray size: ' . sizeof($jsonArray) . '<br>';
    } else {
        echo 'jsonarray size: 0<br>';
    }

    $schema = array();
    $dataType = array();

    $firstInfo = array_values($jsonArray)[0];

    $schema = array_values(array_keys($firstInfo[0]));
    $data = array_values(array_values($firstInfo[0]));

    // array_keys( array_value($jsonArray[0]));

    echo 'Schema size: ' . sizeof($schema) . '<br>';
    echo 'data size: ' . sizeof($data) . '<br>';

    $sqlSchema = "";
    $sqlInsertSchema = "";
    $sqlCreateTable = "";
    $sqlInsert = "";

    for ($i=0; $i < sizeof($schema); $i++) { 
        echo 'key: '.$schema[$i].' type: '.gettype($data[$i]).'<br>';
        $type = gettype($data[$i]);
        if ($type=="NULL") $type = " text ";

        $sqlSchema = $sqlSchema."`".$schema[$i]."` ".$type." NOT NULL,";
        
        
        $sqlInsertSchema = $sqlInsertSchema . "`".$schema[$i]."`,";
    }

    $sqlSchema = substr($sqlSchema,0,-strlen(","));
    //因為MySQL沒有Array的資料型態所以存沒TEXT (STRING)
    $sqlSchema = str_replace('string', 'text', $sqlSchema);

    $sqlInsertSchema = substr($sqlInsertSchema,0,-strlen(","));
    $myDataBase = 'appview_data';
    $tableName = $myFile;
    $sqlCreateTable = "create table if not exists `$myDataBase`.`".$tableName."` (". $sqlSchema .") ENGINE = InnoDB;";
    

    // echo $sqlCreateTable.'<br><br>';
    // echo $sqlInsertSchema.'<br>';

    echo '<br>';
    echo '<br>';
    echo "<hr>";

    
    getDBConnect();
    createTable($sqlCreateTable);
    closeDBConnect();


    $dataList = array_values($jsonArray)[0];
    echo "dataList size: ".sizeof($dataList).'<br>';

    getDBConnect();
    for ($i=0; $i < sizeof($dataList); $i++) { 
        $sqlInsertValue = "";
        $item = array_values($dataList[$i]);

        for ($j=0; $j < sizeof($item); $j++) { 
            $sqlInsertValue .= "'".$item[$j]."',";
        }
        $sqlInsertValue = substr($sqlInsertValue,0,-strlen(","));
        echo $sqlInsertValue.'<br>';
        $sqlInsertCommand = "INSERT INTO `".$dbname."`.`".$tableName."` (".$sqlInsertSchema.") VALUES (".  $sqlInsertValue  .");";
        
        insertDataTable($sqlInsertCommand);
    }
    closeDBConnect();
}
