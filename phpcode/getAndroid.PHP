
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
        // echo $input1."**++--";    
        if(substr($input1,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
            $input1 = substr($input1,3);
        $input1 = json_decode( $input1,  true); //解json
        // echo $input1."**++--";
        // $input1 = $input1["phoneDataJson"];  //取物件的名稱(value)     
        $requestCity = $input1["CITY_NAME_REQUEST"];
        $requestCity = urldecode($requestCity);        
        $requestType = $input1["SPOT_TYPE_REQUEST"];
        $getUserLatitude = $input1["USER_LOCATION_LATITUDE"];
        if(!empty($input1["USER_LOCATION_LONGITUDE"])){
            $getUserLongitude = $input1["USER_LOCATION_LONGITUDE"];
        

        }
        // $getSearch = $input1["EDITTXT_SEARCH_INPUT"];
        
        // $requestSearch = $input1["EDITTXT_SEARCH_INPUT"]; EDITTXT_SEARCH_INPUT
    /////////// input data from android ///////////
        // echo var_dump($input1)."---++--";  
    // var_dump($requestSearch);
        if ($input1!="") {
            # code...        
            // 連接資料庫 //
            require("dbconnect.php");
         	// 連接資料庫 //
            $phpJson=array();
            if($requestType=="SPOT_TYPE_VIEW"){                    
                // `name` as 景點, `lat`as 緯度,`long` as 經度  `Name`,`Py`,`Px`,`Add`,`Toldescribe`,`Picture1`, `Picture2`,`Picture3`
                $sql_ViewSelect="select `Name`,`Px`,`Py`,`Add`,`Toldescribe` from 景點觀光資訊資料庫 where Region = '". $requestCity ."';";
                // echo $sql_ViewSelect;
                if(!empty($getUserLatitude) && !empty($getUserLongitude)){
                $sql_ViewSelect = "SELECT `Name`,`Px`,`Py`,`Add`,`Toldescribe` from 景點觀光資訊資料庫 where Region = '". $requestCity ."' && ((`Py`- ".$getUserLatitude."< 0.02) && (`Px`-".$getUserLongitude." < 0.02) && (".$getUserLatitude."-`Py` < 0.02) && (".$getUserLongitude."-`Px`< 0.02));";
                }
            }
            if($requestType=="SPOT_TYPE_HOTEL"){
                // `name` as 景點, `lat`as 緯度,`long` as 經度  `Name`,`Py`,`Px`,`Add`,`Toldescribe`,`Picture1`, `Picture2`,`Picture3`
                $sql_ViewSelect="SELECT `Name`,`Px`,`Py`,`Add`, Description as Toldescribe from 旅館民宿觀光資訊資料庫 where Region = '". $requestCity ."';";
                // echo $sql_ViewSelect;
                
            }

            $count=0;
            $result = mysqli_query($conn,$sql_ViewSelect);
            // echo mysqli_num_rows($result);
            // $result = $conn->query($sql_ViewSelect);
            // if ( $result->num_rows > 0) {
            $phpJsonArray=array();
            if (mysqli_num_rows($result)>0) {                           
                // output data of each row  fetch_array  fetch_assoc
                while($row = $result->fetch_assoc()) { //如果多行
                	// $row = $result->fetch_assoc();
                    // var_dump($row);
                    // $count=count($row);
                    // for( $i=0;$i<$count;$i ){
                    //     unset($row[$i]);//刪除冗餘資料
                    // }
                    
                    array_push($phpJsonArray,$row); 
                	// $phpJson = json_encode($row,JSON_UNESCAPED_UNICODE|true);
                    // $phpJsonArray[$count]=array($row);
                    // $count++;
                    // echo "ID: " . $row["ID"] . " Name: " . $row["Name"]. " 描述: " .   $row["description"];
                }                
                echo json_encode($phpJsonArray,JSON_UNESCAPED_UNICODE|true);
                // var_dump($phpJson);
            }else{
                $empty = array( "empty" => true );
                array_push($phpJsonArray,$empty); 
                echo json_encode($phpJsonArray,JSON_UNESCAPED_UNICODE|true);
            }
            mysqli_close($conn);
        }
    }
?>
      					 