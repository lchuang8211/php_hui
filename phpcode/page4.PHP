
<?php
	/////////// input data from android ///////////
    $input1 = file_get_contents("php://input",'r');
    
    // function str_to_utf8 ($str = '') {
    $input1= iconv("GBK", "UTF-8", $input1);
    $current_encode = mb_detect_encoding($input1, array("ASCII","GB2312","GBK",'BIG5','UTF-8')); 
    $encoded_str = mb_convert_encoding($input1, 'UTF-8', $current_encode);
    $input1 = $encoded_str;
    
    echo var_dump($input1)." ";
    

    if($input1!=null && $input1!=""){ 
        // str_replace("\n", "", $input1)
        if(substr($input1,0,3) == pack("CCC",0xEF,0xBB,0xBF)) 
            $input1 = substr($input1,3);
        $input1 = json_decode( trim($input1,chr(239).chr(187).chr(191)),  true);
        echo var_dump($input1)."+ ";
        $input1 = $input1["phoneDataJson"];
        // echo "json_last_error : ".json_last_error()." - ";
    }    
   // echo var_dump($input1);
    /////////// input data from android ///////////
   
    // if(substr($input1,0,3) == pack("CCC",0xEF,0xBB,0xBF)) 
            // $input1 = substr($input1,3);
     // 連接資料庫
 	$servername = "localhost";
    $username = "lchuang";
    $password = "asabee1566";
    $dbname = "appview_data";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
            // echo "Connection successfully"."<br>";
    }
    
    
    
    // echo "<br>";
    // $sql = "select * from fruit";
    
    // if($input1!=null && $input1!=""){
    //   $sql = "insert into fruit (name) Values ('" .  $input1 . "')";
    //   $result = $conn->query($sql);                  
    // }     `name` as 景點, `lat`as 緯度,`long` as 經度
    $sql_ViewSelect = "select `name`,`lat`,`long` from 台南景點 where id = '". $input1 ."';";
    $sql_select = "select * from 台南景點 where name='". $input1 ."'";
    $result = $conn->query($sql_ViewSelect);
    if ( $result->num_rows > 0) {
        // output data of each row
        // while($row = $result->fetch_assoc()) {
    	$row = $result->fetch_assoc();
    	$phpJson = json_encode($row,true);
    	echo $phpJson;
        // echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " . $row["description"];
        // }
    }
    mysqli_close($conn);
    
    // $sql_select ="select * from fruitname where name='". $input1 ."'";
    // $result = $conn->query($sql_select);

    // if ( $result->num_rows > 0) {
        // output data of each row
        // while($row = $result->fetch_assoc()) {
    	// $row = $result->fetch_assoc();
    	// $phpJson = json_encode($row);
    	// echo $phpJson;
        // echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " . $row["description"];
        // }
    // } 
    // else {
    //   echo "0 results";
    // }
    // echo '<hr>';
    
    //列出資料庫資料
    // mydb_connect();
    //  $sql = "select * from fruitname";
    // $result = $conn->query("select * from fruitname");
    // if ( $result->num_rows > 0) {
    //   // output data of each row
    //   while($row = $result->fetch_assoc()) {            
    //     echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " . $row["description"]. "<br>";
    //   }
    // } else {
    //   echo "0 results";
    // } 

    // $data = [ 'name' => 'God', 'age' => 2 ] ;
    // header("Content-Type: application/json;");
	// header("Content-Type: text/html; charset=UTF-8");
    // echo json_encode($data);
    

    // echo '<hr>';
    
    // function json_response(){					                
       //  $data = [ 'name' => 'God', 'age' => 2 ] ;
       //  header("Content-Type: application/json;");
       //  echo json_encode($data);
       //  exit();
    // }
    // echo json_response();
    
    // if ($conn->query($sql) === TRUE) {
    //   echo "Record updated successfully" . "<br>";
    // } else {
    //   echo "Error updating record: " . $sql . $conn->error . "<br>";
    // }
?>
      					 