<?php
	//設定header 
    header("Content-type:text/html; charset=utf-8");  //Content-type:text/json
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');

    /////////// input data from android ///////////
    $input = file_get_contents("php://input",'rb');
    //var_dump($input);
    if($input!=null){ 
        $input = urldecode($input);   // 必要條件 避免中文亂碼    
        // echo $input1."**++--";    
        if(substr($input,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
            $input = substr($input,3);
        $input = json_decode( $input,  true); //解json
        ; // 搜尋區域/人名/帳號
    }

    require("dbconnect.php");
    $sql_innerjoin = "SELECT `list`.*, `member`.`head_img`, `member`.`account`,`member`.`user_name` FROM `user_travel_plan`.`plan_list` as `list`, `user_travel_plan`.`member` as `member` where `list`.`id` = `member`.`id`";
    $innerjoin_result = mysqli_query($conn,$sql_innerjoin);
    $sql_select_list = "select * from `user_travel_plan`.`plan_list`";
    $result = mysqli_query($conn,$sql_select_list);
    $phpJsonArray=array();
    $phpJsonJoin = array();
    while ($row = $result->fetch_assoc()) {
        // echo $row["TRAVEL_LIST_SCHEMA_PLAN_NAME"];
        $joinrow = $innerjoin_result->fetch_assoc();
        // echo ($innerjoin_result);
        array_push($phpJsonJoin,$joinrow);
        // $getID = $row["id"];
        // $sql_getheadImg = "select `head_img` from `user_travel_plan`.`member` where `id`='".$getID."'";        
        // $result = mysqli_query($conn,$sql_getheadImg);
        // $himg = $result->fetch_assoc();

        // array_push($phpJsonArray,$row);
        // array_push($phpJsonArray,$himg);
    }

    // echo json_encode($phpJsonArray,JSON_UNESCAPED_UNICODE|true);
    echo json_encode($phpJsonJoin,JSON_UNESCAPED_UNICODE|true);
    // var_dump($phpJson);
            
?>