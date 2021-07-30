
<?php
	//設定header 
    header("Content-type:text/html; charset=utf-8");  //Content-type:text/json
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');

    /////////// input data from android ///////////
    $input1 = file_get_contents("php://input",'rb');
    if($input1!=null){
        $input1 = urldecode($input1);   // 必要條件 避免中文亂碼
        if(substr($input1,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
            $input1 = substr($input1,3);
        $input1 = json_decode( $input1,  true);//解 JSON
        $input1 = $input1["phoneDataJson"];  //取物件的名稱(value) 
        // phoneDataJson = JSON 的 KEY   依據傳的 JSON 自訂取的 KEY 和 VALUE
    /////////// input data from android ///////////   
        // echo var_dump($input1)."---++--";  
        if ($input1!="") {            
        
            // 連接資料庫 //
            $servername = "localhost";  // localhost=本地端 ( 127.0.0.1 )
            $username = " ";  //自訂登入使用者帳號
            $password = "";   //自訂登入使用者密碼
            $dbname = "";     //儲存的"資料庫"名稱
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } else{
                    // print_r("Connection successfully"."<br>");
                }
         	// 連接資料庫 //
                             
            // `name` as 景點, `lat`as 緯度,`long` as 經度
            $sql_ViewSelect = "select `name`,`lat`,`long` from 台南景點 where name like '%". $input1 ."%';";    
            $result = $conn->query($sql_ViewSelect);
            if ( $result->num_rows > 0) {
                // output data of each row  fetch_array
                // while($row = $result->fetch_assoc()) { //如果多行
                	$row = $result->fetch_assoc();
                	$phpJson = json_encode($row,true);
                	echo $phpJson;
                    // echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " .   $row["description"];
                // }
            }
            mysqli_close($conn);
        }
    }
?>
      					 