<?php
//設定header
header("Content-type:text/html; charset=utf-8"); //Content-type:text/json
mb_internal_encoding('UTF-8');
mb_http_input("UTF-8");
// mb_http_output('UTF-8');

// $uploaddir = 'C:\xampp\htdocs\getfile';
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

    // $jsonArray = urldecode($jsonArray);   // 必要條件 避免中文亂碼

    //去BOM檔頭
    if (substr($jsonArray, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
        $jsonArray = substr($jsonArray, 3);
    }

$jsonArray = json_encode($jsonArray);
    // $jsonArray = json_decode($jsonArray, JSON_NUMERIC_CHECK | true); //json解析

    echo urldecode($jsonArray);

}
