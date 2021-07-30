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
        $getUID = $input["USER_U_ID"];
    }

    require("dbconnect.php");
    $sql_innerjoin = "SELECT FROM WHERE u_id ='".$getUID."'";
    $innerjoin_result = mysqli_query($conn,$sql_innerjoin);
    $sql_getPersonalSchedule = "select schedule from `user_travel_plan`.`member` where u_id = '".$getUID."'";
    $result = mysqli_query($conn,$sql_getPersonalSchedule);
    $row = $result->fetch_assoc();
    $getPersonalPlanTable = $row["schedule"];

    $sql_select_PersonalSchedule = "SELECT `u_id`, `plan_name`, `start_date`, `end_date` FROM ".$UserDB.".".$getPersonalPlanTable." WHERE 1";
    $getAllPlan = mysqli_query($conn,$sql_select_PersonalSchedule);
    $phpJsonArray=array();
    $phpJson=array();
    $eachPlan=array();
    
    while ($rowA = $getAllPlan->fetch_assoc()) {
        // array_push($eachPlan, $rowA);
        // echo "rowA plan_name :".$rowA["plan_name"];
        $getPlanDetail = $getPersonalPlanTable.$rowA["plan_name"];  //get plan table name
        // echo "~~~~~".$getPlanDetail;
        $sql_getplandetail = "SELECT COLUMN_NAME_DATE, COLUMN_NAME_QUEUE, TABLE_SCHEMA_NODE_NAME, TABLE_SCHEMA_NODE_LATITUDE, TABLE_SCHEMA_NODE_LONGITUDE, TABLE_SCHEMA_NODE_DESCRIBE, SPOT_TYPE FROM `".$UserDB."`.`".$getPlanDetail."` WHERE 1;";
        // echo "~~~~~".$sql_getplandetail;
        $result_detail = mysqli_query($conn, $sql_getplandetail);
        // echo "error :".mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $eachPlanDetail=array();
        while($row_detail = $result_detail->fetch_assoc()){
            array_push($eachPlanDetail, $row_detail);
        }

        $eachPlanDetail = json_encode($eachPlanDetail, JSON_UNESCAPED_UNICODE|true);
        // $eachPlan = json_encode($eachPlan, JSON_UNESCAPED_UNICODE|true);

        $phpJson = array("plan_info"=>$rowA,"plan_detail"=>$eachPlanDetail);
        array_push($phpJsonArray, $phpJson);
    }
    echo json_encode($phpJsonArray,JSON_UNESCAPED_UNICODE|true);
    
    
    // var_dump($phpJson);
            
?>