<?php 

$getUserInput = file_get_contents("php://input",'rb');
	$getUserInput = urldecode($getUserInput);
	if(substr($getUserInput,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
            $getUserInput = substr($getUserInput,3);

    $getUserInput = json_decode( $getUserInput,  true);
    $getPlanName = $getUserInput["TRAVEL_LIST_SCHEMA_PLAN_NAME"];
    $getUserAccount = $getUserInput["USER_ACCOUNT"];
    // 1. select schedule from member where account = userAccount
    // 2. select * from schedule.planName
    // 3. echo json

    // echo  $getPlanName."-".$getUserAccount;

	require 'dbconnect.php';
	$getUser_Plan = array();
	$sql_getUserInfo = "select account, user_name, u_id, head_img, schedule from ".$UserDB.".member where account = '".$getUserAccount."';";
	$result_user = mysqli_query($conn,$sql_getUserInfo);		
	$userInfoarray = array();
	$row_user = $result_user->fetch_assoc();
	$userInfo = array("userInfo"=>$row_user);

	// array_push($userInfoarray,$row_user);	
	
	
	$userInfo = json_encode($userInfo, JSON_UNESCAPED_UNICODE | true);
	// echo "schedule : ".$row["schedule"];

	$planTable = $UserDB.".".$row_user["schedule"].$getPlanName;
	$sql_getPlanDetail = "SELECT COLUMN_NAME_DATE, COLUMN_NAME_QUEUE, TABLE_SCHEMA_NODE_NAME, TABLE_SCHEMA_NODE_LATITUDE, TABLE_SCHEMA_NODE_LONGITUDE, TABLE_SCHEMA_NODE_DESCRIBE FROM ".$planTable." WHERE 1 ;";
	$result_plan = mysqli_query($conn,$sql_getPlanDetail);
	$planDatailarray = array();
	while ($row_plan = $result_plan->fetch_assoc()) {
		array_push($planDatailarray, $row_plan);
	}
	$planDatailarray = json_encode($planDatailarray, JSON_UNESCAPED_UNICODE | true);
	$planDatail = array("planDatail"=>$planDatailarray);
	// array_push($getUser_Plan, $userInfo);
	// array_push($getUser_Plan, $planDatail);

	$getUser_Plan= array("userInfo"=>$row_user, "planDatail"=>$planDatailarray);

	$getUser_Plan = json_encode($getUser_Plan, JSON_UNESCAPED_UNICODE | true);
	echo $getUser_Plan;
	// echo $planDatailarray;
?>