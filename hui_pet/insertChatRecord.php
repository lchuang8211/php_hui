<?php 
//設定header
header("Content-type:text/json; charset=utf-8"); //Content-type:text/json
mb_internal_encoding('UTF-8');
mb_http_input("UTF-8");
mb_http_output('UTF-8');

$data = json_decode(file_get_contents('php://input'), true);



if ($data != null) {
    echo "insert chat record connect ok";    
} else{
    echo "insert chat record connect not ok";
}









?>
