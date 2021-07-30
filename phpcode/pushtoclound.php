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
        $getSignal = $input["PUBLIC_TO_CLOUND_SIGNAL"];        // 判斷是否新增 還是 刪除
        $getUserID = $input["USER_U_ID"];
        $getUserAccount  = $input["USER_ACCOUNT"];
        $getPlanName  = $input["TRAVEL_LIST_SCHEMA_PLAN_NAME"];
        $getStartDate = $input["TABLE_SCHEMA_DATE_START"];
        $getEndDATE = $input["TABLE_SCHEMA_DATE_END"]; 
        // $getTableName=$input["CLOUND_TABLE_NAME"];
        if($getSignal=="false"){
            $getPlanQueue=$input["planQueue"];
        }
    }

    require("dbconnect.php");
    // var_dump($getTableName);
    // var_dump($getPlanQueue);
    // CREATE DATABASE mydatabase CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    $UserDB = "user_travel_plan";
    $UserInfoTable = "member";
    // $PlanListforSearch = "all_plan_list";
    // $list_schema = "TRAVEL_LIST_SCHEMA_PLAN_NAME";
    $sql_create_UserDB = "create database if not exists `".$UserDB."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";    
    
    mysqli_query($conn,$sql_create_UserDB);
    

    //透用u_id 找 id(pk)
    $sql_getUserInfo = "select `id`,`schedule` from `".$UserDB."`.`".$UserInfoTable."` where `u_id`='".$getUserID."';";
    $result = mysqli_query($conn,$sql_getUserInfo);
    $row = $result->fetch_assoc();
    $getID = $row["id"]; 
    $getSchedule = $row["schedule"];
    echo "id = ".$row["id"]." schedule = ".$row["schedule"];
    //透用u_id 找 id(pk)
   

    if($getSignal){

        //在 plan_list 中放入id 和相關資訊 ( 紀錄全部的行程用 ) for travel search
        $sql_InsertIntoPlanlist = "INSERT INTO `".$UserDB."`.`plan_list` (`id`, `u_id`, `plan_Name`, `start_date`, `end_date`) VALUES ( '".$getID."','".$getUserID."','".$getPlanName."','".$getStartDate."','".$getEndDATE."');";
        echo "sql_InsertIntoPlanlist :".$sql_InsertIntoPlanlist;
        mysqli_query($conn, $sql_InsertIntoPlanlist);
        //在 plan_list 中放入id 和相關資訊
        
        // create personal schedule list

        $sql_insert_personal_table = "INSERT INTO  ".$UserDB.".".$getSchedule." ( u_id, plan_name, start_date, end_dATE) VALUES ( 'u".$getID."','".$getPlanName."','".$getStartDate."','".$getEndDATE."');";
        mysqli_query($conn, $sql_insert_personal_table);
        echo $sql_insert_personal_table;
        // create personal schedule list

        //建立 獨立 Plan 的 Table  ( 使用 Plan_id(schedule) + 行程名稱 當作 Table 名稱 )
        $sql_create_Table = "CREATE TABLE IF NOT EXISTS `".$UserDB."`.`".$getSchedule.$getPlanName."`"
                                                         ." ( COLUMN_NAME_DATE INTEGER NOT NULL,"
                                                         ."COLUMN_NAME_QUEUE INTEGER NOT NULL,"
                                                         ."TABLE_SCHEMA_NODE_NAME TEXT NOT NULL,"
                                                         ."TABLE_SCHEMA_NODE_LATITUDE DOUBLE NOT NULL,"
                                                         ."TABLE_SCHEMA_NODE_LONGITUDE DOUBLE NOT NULL,"
                                                         ."TABLE_SCHEMA_NODE_DESCRIBE TEXT NOT NULL, "
                                                         ."SPOT_TYPE VARCHAR(20) NOT NULL )  ENGINE = InnoDB;" ;  
        mysqli_query($conn,$sql_create_Table); 
        //建立 獨立 Plan 的 Table  ( 使用 Plan_id(schedule) + 行程名稱 當作 Table 名稱 )
        //新增行程記錄進 Plan 的 Table
        foreach ($getPlanQueue as $row) {
            $SingleNodeOfPlan = "";
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    $SingleNodeOfPlan = $SingleNodeOfPlan."'".$value."',";
                }else{
                    $SingleNodeOfPlan = $SingleNodeOfPlan.$value.",";
                }
            }
            $SingleNodeOfPlan = substr($SingleNodeOfPlan,0, -strlen(","));
            // echo $SingleNodeOfPlan;
            $sql_insert_singlenode = "INSERT INTO `".$UserDB."`.`".$getSchedule.$getPlanName."` (`COLUMN_NAME_DATE`, `COLUMN_NAME_QUEUE`, `TABLE_SCHEMA_NODE_NAME`, `TABLE_SCHEMA_NODE_LATITUDE`, `TABLE_SCHEMA_NODE_LONGITUDE`, `TABLE_SCHEMA_NODE_DESCRIBE`, `SPOT_TYPE`) VALUES (". $SingleNodeOfPlan .");";
    //, `TABLE_SCHEMA_NODE_LATITUDE`, `TABLE_SCHEMA_NODE_LONGITUDE`, `TABLE_SCHEMA_NODE_DESCRIBE`
            // echo $sql_insert_singlenode;
            mysqli_query($conn,$sql_insert_singlenode);
        }
        //新增行程記錄進 Plan 的 Table
    }else{
        echo $getSignal;
        //DROP table `user_travel_plan`.`user|神行者|one|2020-5-20|2020-5-21` ;
        $sql_delete_Table = "DROP table `".$UserDB."`.`".$getSchedule.$getPlanName."`;";
        mysqli_query($conn,$sql_delete_Table);
        //DELETE FROM `all_plan_list` WHERE 0
        $sql_delete_lsit= "DELETE FROM `user_travel_plan`.`".$getSchedule."` where plan_Name = '".$getPlanName."';";
        mysqli_query($conn,$sql_delete_lsit);
        // echo $sql_delete_lsit;
        $sql_delete_SearchList = "DELETE FROM user_travel_plan."."plan_list WHERE plan_Name = '".$getPlanName."' 
                                                                && u_id ='".$getUserID."'";
        mysqli_query($conn,$sql_delete_SearchList);        
        $sql_delete_personal_table = "DELETE FROM  ".$UserDB.".".$getSchedule." where `u_id` = '".$getID."';";
        mysqli_query($conn, $sql_delete_personal_table);
    }

// $string_input = [
//                     {
                        
//                         "CLOUND_TABLE_NAME":"user |神行者|one|2020-5-20|2020-5-21",
//                         "planQueue":[
//                                         {"COLUMN_NAME_DATE":1,"COLUMN_NAME_QUEUE":1,"TABLE_SCHEMA_NODE_NAME":"大武崙砲台"},
//                                         {"COLUMN_NAME_DATE":2,"COLUMN_NAME_QUEUE":1,"TABLE_SCHEMA_NODE_NAME":"白沙灣遊客中心"}
//                                     ]
                        
//                     }
//                 ];




?>